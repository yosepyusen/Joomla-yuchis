<?php
/**
* @package     Joomla.Site
* @subpackage  Templates.Linelabox
* @copyright   Copyright (C) 2018 Linelab.org. All rights reserved.
* @license     GNU General Public License version 2.
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class LinelaboxHelper {

	public static function init(&$template, &$menu, &$lang, &$bootstrap_grid) {
		$doc             = JFactory::getDocument();
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.file');
		switch ($template->params->get('bootstrap_version')) {
			case 2:
				if (JFile::exists(JPATH_ROOT.'/templates/'.$template->template.'/js/jui/bootstrap.min.js')) {
					JFile::delete(JPATH_ROOT.'/templates/'.$template->template.'/js/jui/bootstrap.min.js');
				}
				break;
			case 3:
			default:
				if (!JFile::exists(JPATH_ROOT.'/templates/'.$template->template.'/js/jui/bootstrap.min.js')) {
					JFile::write(JPATH_ROOT.'/templates/'.$template->template.'/js/jui/bootstrap.min.js','');
				}
				$doc->addScript('templates/'.$template->template.'/js/bootstrap.min.js');
				break;
		}
		$doc->addScript('templates/'.$template->template.'/js/modernizr.min.js');
	}

	public static function countModules(&$template, &$menu, &$lang, &$bootstrap_grid) {
		foreach ($bootstrap_grid as $row_id=>&$row) {
			$row['show_modules']=false;
			foreach ($row['positions'] as $position_id=>&$position) {
				if ($position_id=='componentbox') {
					$position['modules_show']=LinelaboxHelper::showComponent($menu, $lang, $bootstrap_grid);
				} else {
					$position['modules_show']=(boolean)$template->countModules($position_id);
				}
				$row['show_modules']|=$position['modules_show'];
				if (!isset($position['enabled'])) $position['enabled']=true;
			}
		}
	}
	public static function showComponent(&$menu, &$lang, &$bootstrap_grid) {
		if ($menu->getActive() === $menu->getDefault($lang->getTag()) && $bootstrap_grid['mainbox']['options']['frontpage-component-enabled']!=1) {
			if (!empty($_POST)) {
				return true;
			} else {
				$homepage=$menu->getActive()->query;
				ksort($homepage);
				$req=$_REQUEST;
				if (isset($req['Itemid'])) unset($req['Itemid']);
				ksort($req);
				if ($req!=$homepage) {
					return true;
				} else {
					return false;
				}
			}
		} else {
			return true;
		}
	}
	public static function renderRow(&$template, $row_id, &$bootstrap_grid, $parallax=false) {
		$row=&$bootstrap_grid[$row_id];
		if ($row['show_modules']) {
			switch ($row['options']['container']) {
				case 'boxed':$container_class="container";break;
				case 'fluid':$container_class="container-fluid";break;
			}
			if (@$row['options']['no-gutters']==1) {
				$row_class='row no-gutters';
				$container_class.=' no-gutters';
			} else {
				$row_class='row';
			}
			$section_classes=$row_id;
			$section_extra_attributes='';

			if ($parallax) {
				$section_classes.=' parallax-window';
				list($width, $height) = getimagesize(JPATH_ROOT.'/'.$parallax);
				if ($width>0 && $height>0) {
					$section_extra_attributes.='data-image-src="'.$parallax.'" data-image-width="'.$width.'" data-image-height="'.$height.'"';
				}
			}

			echo '<section id="section-'.$row_id.'" class="'.$section_classes.'" '.$section_extra_attributes.'><div id="'.$row_id.'" class="'.$container_class.'"><div class="'.$row_class.'">';
			$columnCounter=array('xs'=>13,'sm'=>13,'md'=>13); 
			$offsetCounter=array('xs'=>0,'sm'=>0,'md'=>0); 
			$last_position=null;
			$firstPosition=true;
			LinelaboxHelper::expandPositions($bootstrap_grid, $row_id);

			foreach ($row['positions'] as $position_id=>&$position) {
				LinelaboxHelper::writeClearfix($columnCounter, $position['grid'], $firstPosition, $position['enabled']);
				$firstPosition=false;
				if ($position['enabled'] && $position['modules_show']) {
					$position_classes=LinelaboxHelper::getPositionClasses($position_id, $position);

					echo '<div id="'.$position_id.'" class="labox '.$position_classes.'">';
					echo '<jdoc:include type="modules" name="'.$position_id.'" style="style"/>';
					echo '</div>';
				}
			}
			echo '</div></div></section>';
		}
	}

	public static function expandPositions(&$bootstrap_grid, $row_id, $debug=false) {
		$row=&$bootstrap_grid[$row_id];
		$columnCounter=array('xs'=>13,'sm'=>13,'md'=>13); 
		$offsetCounter=array('xs'=>0,'sm'=>0,'md'=>0); 
		$last_position=null;
			if ($debug) echo "<pre>";
		foreach ($row['positions'] as $position_id=>&$position) {
			if ($debug) print_r($position_id."\n");
			if ($debug) print_r($position);
			$position['grid-changed']=false;
			if ($position['modules_show']) $last_position=$position_id;
			if ($row_id!='mainbox') $position['enabled']=1;
			if ($position_id=='componentbox') {
				$position['modules_show']=true;
				$last_position=$position_id;
			}

			$gridHelper=array(
				'xs'=>array('width'=>$position['grid']['xs'], 'nl'=>false/*'offset'=>0,'start-col'=>0,'end-col'=>0,*/),
				'sm'=>array('width'=>$position['grid']['sm'], 'nl'=>false/*'offset'=>0,'start-col'=>0,'end-col'=>0,*/),
				'md'=>array('width'=>$position['grid']['md'], 'nl'=>false/*'offset'=>0,'start-col'=>0,'end-col'=>0,*/),
			);
			foreach ($columnCounter as $screen=>$p) {
				if (!empty($position['enabled'])) {
					$columnCounter[$screen]+=$position['grid'][$screen]>0?$position['grid'][$screen]:0;
				}
				if ($columnCounter[$screen]>12) {
					$columnCounter[$screen]=$position['grid'][$screen]>0?$position['grid'][$screen]:0;
					$gridHelper[$screen]['nl']=true;
					$offsetCounter[$screen]=0;
				}
				if ($position['modules_show'] && $offsetCounter[$screen]>0) {
					$gridHelper[$screen]['width']+=$offsetCounter[$screen];
					$offsetCounter[$screen]=0;
					$position['grid-changed']=true;
				}
				if (!$position['modules_show']) {
					if ($gridHelper[$screen]['nl'] || $offsetCounter[$screen]>0) {
						$offsetCounter[$screen]+=$position['grid'][$screen]>0?$position['grid'][$screen]:0;
						//echo "$position_id $screen : ".$offsetCounter[$screen]."\n";
					} else {
						$row['positions'][$last_position]['grid-helper'][$screen]['width']+=$position['grid'][$screen]>0?$position['grid'][$screen]:0;
						$row['positions'][$last_position]['grid-changed']=true;
					}
				}
			}
			$position['grid-helper']=$gridHelper;
		}


		if ($row_id=='mainbox') {			
			$positions=&$row['positions'];

			reset($positions);
			while (key($positions) !== 'componentbox') next($positions);

			$active_line=array('md'=>true, 'sm'=>true, 'xs'=>true);
			$line_columns=array('md'=>0, 'sm'=>0, 'xs'=>0);
			while ($position_id=key($positions)) {
				foreach ($columnCounter as $screen=>$p) {
					if (!$active_line[$screen]) continue;
					$positions[$position_id]['grid-helper'][$screen]['width']=$positions[$position_id]['grid'][$screen]>0?$positions[$position_id]['grid'][$screen]:0;
					if (!$positions[$position_id]['modules_show'])
						$line_columns[$screen]+=$positions[$position_id]['grid'][$screen]>0?$positions[$position_id]['grid'][$screen]:0;
					if ($positions[$position_id]['grid-helper'][$screen]['nl']) $active_line[$screen]=false;
				}
				prev($positions);
			}

			reset($positions);
			while (key($positions) !== 'componentbox') next($positions);
			$active_line=array('md'=>true, 'sm'=>true, 'xs'=>true);
			while (next($positions)) {
				$position_id=key($positions);
				foreach ($columnCounter as $screen=>$p) {
					if ($positions[$position_id]['grid-helper'][$screen]['nl']) $active_line[$screen]=false;
					if (!$active_line[$screen]) continue;
					$positions[$position_id]['grid-helper'][$screen]['width']=$positions[$position_id]['grid'][$screen]>0?$positions[$position_id]['grid'][$screen]:0;
					if (!$positions[$position_id]['modules_show'])
						$line_columns[$screen]+=$positions[$position_id]['grid'][$screen]>0?$positions[$position_id]['grid'][$screen]:0;
				}
			}

			foreach ($columnCounter as $screen=>$p) {
				$positions['componentbox']['grid-helper'][$screen]['width']=($positions['componentbox']['grid'][$screen])+$line_columns[$screen];
				$positions['componentbox']['grid-changed']=true;
			}
		}
	}
	public static function writeClearfix(&$columnCounter, $grid_options, $firstPosition, $enabled=true) {	
		$clearfix=false;
		$clearfix_classes=array('hidden-md'=>true, 'hidden-lg'=>true, 'hidden-sm'=>true, 'hidden-xs'=>true);
		//echo "$position_id ";
		foreach ($columnCounter as $screen=>$val) {
			if ($enabled) $columnCounter[$screen]+=$grid_options[$screen]>0?$grid_options[$screen]:0;
			if ($columnCounter[$screen]>12) {
				$columnCounter[$screen]=$grid_options[$screen]>0?$grid_options[$screen]:0;
				$clearfix=true;
				switch ($screen) {
					case 'md':
						unset($clearfix_classes['hidden-lg']);
						// break is missing - it's ok
					default:
						unset($clearfix_classes['hidden-'.$screen]);
						break;
				}
			}
		}
		if ($clearfix && !$firstPosition) {
			echo '<div class="clearfix '.implode(' ', array_keys($clearfix_classes)).'"></div>';
		}
	}

	public static function getPositionClasses($position_id, &$position) {
		$grid=array();
		$grid['xs']=$position['grid-helper']['xs']['width']>0?'col-xs-'.$position['grid-helper']['xs']['width']:'hidden-xs';
		$grid['sm']=$position['grid-helper']['sm']['width']>0?'col-sm-'.$position['grid-helper']['sm']['width']:'hidden-sm';
		$grid['md']=$position['grid-helper']['md']['width']>0?'col-md-'.$position['grid-helper']['md']['width']:'hidden-md hidden-lg';
		return implode(' ', $grid);
	}
	public static function showMainbox(&$bootstrap_grid, $check_rows) {
		$showMainbox=false;
		foreach ($check_rows as $row_id) $showMainbox|=$bootstrap_grid[$row_id]['show_modules'];
		return $showMainbox;
	}
	public static function bootstrapStaticPosition(&$bootstrap_grid, $group, $tagname, $position_id, $firstPosition, &$columnCounter) {
		if (isset($bootstrap_grid[$group]['positions'][$position_id])) {
			$position=$bootstrap_grid[$group]['positions'][$position_id];
		}
		if ((int)$position['enabled']==0) return;
		LinelaboxHelper::writeClearfix($columnCounter, $position['grid'], $firstPosition, $position['enabled']);
		if (!$position['modules_show']) return;
		$position_classes=LinelaboxHelper::getPositionClasses($position_id, $position);		
		?>
		<<?php echo $tagname;?> id="<?php echo $position_id;?>" class="labox <?php echo $position_classes;?>">
			<jdoc:include type="modules" name="<?php echo $position_id;?>" style="style"/>
		</<?php echo $tagname;?>>
	<?php 
	}
}

