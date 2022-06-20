<?php
/**
* @package     Joomla.Site
* @subpackage  Templates.Linelabox
* @copyright   Copyright (C) 2018 Linelab.org. All rights reserved.
* @license     GNU General Public License version 2.
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$app = JFactory::getApplication();
$doc             = JFactory::getDocument();
$lang            = JFactory::getLanguage();
$db            = JFactory::getDBO();
$menu = $app->getMenu();

$this->language  = $doc->language;
$this->direction = $doc->direction;
JHtml::_('bootstrap.framework');

include(__DIR__.'/helper.php');
$bootstrap_grid=json_decode('{"mainbox":{"options":{"container":"boxed","enabled":"1","frontpage-component-enabled":"0"},"positions":{"componentbox":{"grid":{"xs":"12","sm":"12","md":9},"enabled":1},"rightbox2":{"grid":{"xs":12,"sm":"12","md":"3"},"enabled":1}}},"abox":{"options":{"container":"boxed","enabled":1},"positions":{"abox1":{"grid":{"xs":"12","sm":-6,"md":"6"},"style":"style"},"abox2":{"grid":{"md":"6","sm":12,"xs":12},"options":{"enabled":1}}}},"cbox":{"options":{"container":"boxed","no-gutters":"true","enabled":1},"positions":{"cbox1":{"grid":{"xs":"12","sm":"12","md":"2"}},"cbox2":{"grid":{"md":"7","sm":12,"xs":12},"enabled":1},"cbox3":{"grid":{"md":"3","sm":12,"xs":12},"enabled":1}}},"dbox":{"options":{"container":"fluid","enabled":1,"no-gutters":1},"positions":{"dbox1":{"grid":{"xs":"12","sm":12,"md":"12"}}}},"ebox":{"options":{"container":"boxed","enabled":1},"positions":{"ebox1":{"grid":{"xs":"12","sm":12,"md":12}}}},"fbox":{"options":{"container":"boxed","enabled":1},"positions":{"fbox1":{"grid":{"xs":"12","sm":"12","md":12}}}},"gbox":{"options":{"container":"boxed","enabled":1},"positions":{"gbox1":{"grid":{"xs":"12","sm":12,"md":12}}}},"kbox":{"options":{"container":"fluid","enabled":1},"positions":{"kbox1":{"grid":{"xs":"12","sm":"12","md":"12"}}}},"lbox":{"options":{"container":"boxed","enabled":1},"positions":{"lbox1":{"grid":{"xs":"12","sm":12,"md":12}}}},"pbox":{"options":{"container":"boxed","enabled":1},"positions":{"pbox1":{"style":"style","grid":{"xs":12,"sm":12,"md":12}}}},"qbox":{"options":{"container":"boxed","enabled":1},"positions":{"qbox1":{"style":"style","grid":{"xs":12,"sm":12,"md":12}}}},"rbox":{"options":{"container":"fluid","enabled":1,"no-gutters":1},"positions":{"rbox1":{"style":"style","grid":{"xs":"12","sm":"12","md":6}},"rbox2":{"grid":{"md":6,"sm":12,"xs":12},"enabled":1}}},"sbox":{"options":{"container":"boxed","enabled":1,"no-gutters":0},"positions":{"sbox1":{"style":"style","grid":{"xs":"12","sm":12,"md":5}},"sbox2":{"style":"style","grid":{"xs":"12","sm":"6","md":4}},"sbox3":{"grid":{"md":"3","sm":"6","xs":12},"enabled":1}}},"tbox":{"options":{"container":"boxed","enabled":1},"positions":{"tbox1":{"style":"style","grid":{"xs":12,"sm":12,"md":12}}}}}', true);
LinelaboxHelper::init($this, $menu, $lang, $bootstrap_grid);
LinelaboxHelper::countModules($this, $menu, $lang, $bootstrap_grid);


// Getting params from template
// Detecting Active Variables
$component_identificator=array();
if ($menu->getActive() === $menu->getDefault($lang->getTag())) {
	if (!empty($menu->getActive()->query['option'])) $component_identificator[]=$menu->getActive()->query['option'];
	if (!empty($menu->getActive()->query['view'])) $component_identificator[]=$menu->getActive()->query['view'];
	$component_identificator=implode('_', $component_identificator);	
	if (!empty($menu->getActive()->query['layout'])) { 
		$component_identificator="$component_identificator $component_identificator".'_'.$menu->getActive()->query['layout'];
	}
} else {
	if ($option   = $app->input->getCmd('option', '')) $component_identificator[]=$option;
	if ($view   = $app->input->getCmd('view', ''))  $component_identificator[]=$view;
	$component_identificator=implode('_', $component_identificator);	
	if ($layout   = $app->input->getCmd('layout', '')) {
		$component_identificator="$component_identificator $component_identificator".'_'.$layout;
	}
}
$this->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/bootstrap.min.css');
$this->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/animate.min.css');
JHtml::_('bootstrap.loadCss', false, $this->direction);
$this->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/static.css');
$this->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/template.css');
$this->addStyleSheetVersion('https://fonts.googleapis.com/css?family=Roboto:regular,italic,700,700italic|Poppins:regular,italic,700,700italic');
$this->addScriptVersion($this->baseurl . '/templates/' . $this->template . '/js/tools.js');
$userCss = JPATH_SITE . '/templates/' . $this->template . '/css/custom.css';
if (file_exists($userCss) && filesize($userCss) > 0) {
	$this->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/custom.css');
}

?><!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<jdoc:include type="head" />
<!--[if lt IE 9]>
<script src="<?php echo JURI::root(true); ?>/media/jui/js/html5.js"></script>
<script src="<?php echo JURI::root(true); ?>/templates/<?php echo $this->template; ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<body class="linelabox <?php echo ($this->direction == 'rtl' ? ' rtl' : '');?>">
<!-- Wrap -->
<div class="wrapper clearfix panelbox">
<header class="header" id="headerlab">
<?php LinelaboxHelper::renderRow($this, 'abox',$bootstrap_grid);?>
<nav id="mainnav<?php echo $this->params->get('disable_sticky_navigation')==1?'-nosticky':'';?>">
<?php LinelaboxHelper::renderRow($this, 'cbox',$bootstrap_grid);?>
</nav>
</header>

<?php LinelaboxHelper::renderRow($this, 'dbox',$bootstrap_grid);?>
<?php LinelaboxHelper::renderRow($this, 'ebox',$bootstrap_grid);?>
<div class="labcontent">
<?php LinelaboxHelper::renderRow($this, 'fbox',$bootstrap_grid);?>

<?php
$section_mainbox_extra_class='';
$section_mainbox_extra_attributes='';
if (false) {
	$section_mainbox_extra_class='parallax-window';
	list($width, $height) = getimagesize(JPATH_ROOT.'/{mainbox_parallax_image}');
	if ($width>0 && $height>0) {
		$section_mainbox_extra_attributes.='data-image-src="{mainbox_parallax_image}" data-image-width="'.$width.'" data-image-height="'.$height.'"';
	}
}
$mainbox_extra_class='container';
$mainbox_extra_attributes='';
$show_mainbox=LinelaboxHelper::showMainbox($bootstrap_grid, array("mainbox","gbox"));
$row_extra_class='';
if ($show_mainbox):
?>
<section id="section-mainbox" class="mainbox <?php echo $section_mainbox_extra_class;?>" <?php echo $section_mainbox_extra_attributes;?>>
<div id="mainbox" class="<?php echo $mainbox_extra_class; ?>" <?php echo $mainbox_extra_attributes; ?>>
<div class="row <?php echo $row_extra_class;?>">
<?php
LinelaboxHelper::expandPositions($bootstrap_grid, 'mainbox');

$columnCounter=array('xs'=>0,'sm'=>0,'md'=>0);
?>

<?php
LinelaboxHelper::writeClearfix($columnCounter, $bootstrap_grid['mainbox']['positions']['componentbox']['grid'], false, true);
$position_classes=LinelaboxHelper::getPositionClasses('componentbox', $bootstrap_grid['mainbox']['positions']['componentbox']);
$componentbox_extra_attributes='';
$showcomponent=LinelaboxHelper::showComponent($menu, $lang, $bootstrap_grid);
$component_extra_class='';

?>

<div id="componentbox" class="labox labcontent <?php echo $position_classes;?>" <?php echo $componentbox_extra_attributes;?>>
<main>
<!-- Message container -->
<jdoc:include type="message" />
<?php LinelaboxHelper::renderRow($this, 'gbox',$bootstrap_grid);?>
<?php
?>
<?php if ($showcomponent):?>
<div id="component" class="mainlinelab content <?php echo $component_extra_class;?> <?php echo $component_identificator;?>">
<jdoc:include type="component" />
</div>
<?php endif;?>
</main>
</div>
<?php LinelaboxHelper::bootstrapStaticPosition($bootstrap_grid, 'mainbox', 'aside', 'rightbox2', true, $columnCounter); ?>
</div>
</div>
</section>
<?php 
endif; // show_mainbox
?>
<?php LinelaboxHelper::renderRow($this, 'kbox',$bootstrap_grid);?>
<?php LinelaboxHelper::renderRow($this, 'lbox',$bootstrap_grid);?>
<?php LinelaboxHelper::renderRow($this, 'pbox',$bootstrap_grid,'templates/presslab/images/keppel-cbd-keppelport-morning-cargo-buil-2039122.jpg');?>
<?php LinelaboxHelper::renderRow($this, 'qbox',$bootstrap_grid);?>
</div>
<!-- Footer -->
<footer id="footer" class="footer">
<?php LinelaboxHelper::renderRow($this, 'rbox',$bootstrap_grid,'templates/presslab/images/sailing-boat-ocean-open-water-sea-569336.jpg');?>
<?php LinelaboxHelper::renderRow($this, 'sbox',$bootstrap_grid);?>
<?php LinelaboxHelper::renderRow($this, 'tbox',$bootstrap_grid);?>
</footer>
<jdoc:include type="modules" name="debug" style="none"/>
<!--Please do not remove Copyright link in footer of Free Joomla templates, it helps promote Linelabox.--> 
<div class="copylab">Creado por<a href="https://www.linelab.org" title="Joomla! Template Builder">  LINELABOX  </a></div>
</div>
</body>
</html>