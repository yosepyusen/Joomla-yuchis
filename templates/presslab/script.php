<?php
/**
* @package     Joomla.Site
* @subpackage  Templates.Linelabox
* @copyright   Copyright (C) 2018 Linelab.org. All rights reserved.
* @license     GNU General Public License version 2.
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
class presslabInstallerScript
{
	var $template_custom_data_old;
	var $template_custom_data_new;
	var $allInstalledModules;
	var $installedModules;

	function preflight( $type, $parent ) {		
		jimport( 'joomla.filesystem.file' );
		jimport( 'joomla.filesystem.folder' );
		$this->template_custom_data_old=array();
		if ($currentExtensionId=$parent->get('currentExtensionId')) {
			$db = JFactory::getDBO();
			$db->setQuery("SELECT custom_data FROM #__extensions WHERE extension_id=".$db->Quote($currentExtensionId));
			if ($custom_data=$db->loadResult()) {
				$this->template_custom_data_old=json_decode($custom_data, true);
			}
		}
		if ($type == 'update') {
			if (JFile::exists(JPATH_ROOT.'/templates/'.$parent->get('element').'/css/custom.css')) {
				JFile::copy(JPATH_ROOT.'/templates/'.$parent->get('element').'/css/custom.css',
					JPATH_ROOT.'/templates/'.$parent->get('element').'/custom.css');
			}
			foreach (JFolder::folders(JPATH_ROOT.'/templates/'.$parent->get('element'),'.',false, true) as $folder) {
				JFolder::delete($folder);
			}
		}
	}

	function install( $parent ) {
		$this->installModules( $parent );
		$this->updateModules( $parent );
	}
	function update( $parent ) {


		$this->installModules( $parent );
		$this->updateModules( $parent );
	}
	#### NOT IMPLEMENTED IN JOOMLA! ... maybe in the future
	#function uninstall( $parent ) {
	#	$this->removeModules( $parent );
	#	$this->uninstallModules( $parent );
	#}
	#function removelModules() {
	#}
	#function uninstallModules() {
	#}

	function installModules() {
		jimport( 'joomla.filesystem.file' );
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.path' );
		include_once(JPATH_ADMINISTRATOR.'/components/com_modules/models/module.php');


		$db = JFactory::getDBO();

		$db->setQuery("SELECT `element` FROM `#__extensions` WHERE `type` = 'module'");
		$this->allInstalledModules=$db->loadColumn();

		$src = dirname(__FILE__);
		$this->installedModules=array();
		if(JFolder::exists($src.'/modules')) {
			$folders=JFolder::folders($src.'/modules', '.', false, true);
			foreach ($folders as $folder) {
				$installer = new JInstaller;
				$result = @$installer->install($folder);
				$module_name=pathinfo($folder, PATHINFO_BASENAME);
				if (!in_array($module_name, $this->allInstalledModules)) {
					$this->installedModules[]=$module_name;

					# automaticaly remove the first instance of module
					$db->setQuery("SELECT id FROM #__modules WHERE module=".$db->Quote($module_name));
					if ($module_id=$db->loadResult()) {
						$db->setQuery("UPDATE #__modules SET published=-2 WHERE module=".$db->Quote($module_name));
						$db->query();
						$module_instance=new ModulesModelModule;
						$pks=array($module_id);
						$module_instance->delete($pks);
					}
				}
			}
		}
	}

	function updateModules( $parent ) {
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.path' );

		$db = JFactory::getDBO();
		$src = dirname(__FILE__);

		$db->setQuery("SELECT `element` FROM `#__extensions` WHERE `type` = 'module'");
		$installedModules=$db->loadColumn();

		$notInstalled=array();
		$newModules=array();
		$updatedModules=array();
		include_once(JPATH_ADMINISTRATOR.'/components/com_modules/models/module.php');

		$this->template_custom_data_new=array('modules'=>array());
		if (JFile::exists($src.'/modules/modules.json')) {
			$new_modules_string=JFile::read($src.'/modules/modules.json');
			$new_modules_string=str_replace('{templatename}', $parent->get('element'), $new_modules_string);
			$new_modules_data=json_decode($new_modules_string, true);

			foreach ($new_modules_data as $position_id=>&$position) {
				if (is_array($position['modules'])) {
					foreach ($position['modules'] as &$module) {
						if (empty($module['uniqueid'])) continue;
						$module_id=null;
						$module_id_old=null;
						$new_module=false;
						// try to find module over internal template info
						if (isset($this->template_custom_data_old['modules'][$module['uniqueid']])) {
							$db->setQuery("SELECT id FROM #__modules WHERE id=".$db->Quote($this->template_custom_data_old['modules'][$module['uniqueid']]['module.id']));
							if ($db->loadResult()) {
								$module_id=$this->template_custom_data_old['modules'][$module['uniqueid']]['module.id'];
								$module_id_old=$module_id;
							}
						}
						// try to find module over module note
						if ($module_id===null) {
							$db->setQuery("SELECT id FROM #__modules WHERE note=".$db->Quote('linelabox:'.$module['uniqueid']));
							if ($tmp_id=$db->loadResult()) {
								$module_id=$tmp_id;
								$module_id_old=$module_id;
								$new_module=true;
							}
						}
						if ($module_id===null) {
							if (in_array($module['module_name'], $installedModules)) {
								$db->setQuery("SELECT MAX(ordering) FROM `#__modules` WHERE `position` = ".$db->Quote($position_id));
								$ordering=(int)$db->loadResult();
								$module_instance=new ModulesModelModule;
								$data=array(
									'id' => '0',
									'title' => $module['module_title'],
									'note' => '',
									'module' => $module['module_name'],
									'showtitle' => @(int)$position['showtitle'],
									'published' => '1',
									'publish_up' => '',
									'publish_down' => '',
									'client_id' => '0',
									'position' => $position_id,
									'access' => '1',
									'ordering' => $ordering+1,
									'language' => '*',
									'assignment' => '0',
								);
								$module_instance->save($data);

								$module_id=$module_instance->getState('module.id');
								$new_module=true;
								$newModules[]=json_decode(json_encode($module), true);
							} else {
								$notInstalled[]=&$module;
							}
						}
						if ($module_id!=null) {
							$this->template_custom_data_new['modules'][$module['uniqueid']]=array(
								'module.id'=>$module_id,
								'module_title'=>@$module['module_title'],
								'module_name'=>@$module['module_name'],
								'showtitle'=>@(int)$position['showtitle'],
								'header_tag'=>@$position['header_tag'],
								'show_module_at'=>@(int)$module['show_module_at'],
								'position'=>@$position_id,
							);
							if (isset($module['params'])) {
								$this->template_custom_data_new['modules'][$module['uniqueid']]=array_merge($this->template_custom_data_new['modules'][$module['uniqueid']],
									$module['params']);
							}

							$db->setQuery("SELECT * FROM #__modules WHERE id=".$db->Quote($module_id));
							$current_module=$db->loadObject();
							if ($current_module->params=='') {
								$module_params=array(
									'module_tag'=>'div',
									'style'=>'0',
								);
								switch ($module['module_name']) {
									case 'mod_menu':
										$db->setQuery("SELECT `menutype` FROM `#__menu` WHERE `home` = '1'");
										$menutype=$db->loadResult();
										$module_params['menutype']=$menutype;
										break;
									case 'mod_labslideshow':
										$module_params['numberofslides']="2";
										$module_params['headtype']="h1";
									default:
										break;
								}
							} else {
								$module_params=json_decode($current_module->params, true);
							}
							switch ($module['module_name']) {
								case 'mod_custom':
									$this->template_custom_data_new['modules'][$module['uniqueid']]['content_hash']=md5($module['html']);
									break;
							}
							if (isset($this->template_custom_data_old['modules'][$module['uniqueid']]) && $new_module==false)
								$old_module_params=$this->template_custom_data_old['modules'][$module['uniqueid']];
							else 
								$old_module_params=array();

							$new_module_params=$this->template_custom_data_new['modules'][$module['uniqueid']];
							foreach ($new_module_params as $key=>$value) {
								switch ($key) {
									case 'module.id':
										continue 2;
									case 'showtitle':
										if (!isset($old_module_params[$key]) || $value!=$old_module_params[$key]) {
											$db->setQuery("UPDATE #__modules SET `showtitle`=".$db->Quote($value)." WHERE id=".$db->Quote($module_id));
											$db->query();
											if (!$new_module) $updatedModules[]=array(
												'position_id'=>$position_id,
												'module_name'=>$module['module_name'],
												'module_title'=>$module['module_title'],
												'desc_label'=>'Showtitle',
												'desc_value'=>$value==1?'Show':'Hide',
											);
										}
										continue 2;
									case 'content_hash':
										if (!isset($old_module_params[$key]) || $value!=$old_module_params[$key]) {
											$module['html']=str_replace('{uniq}', '_'.$module['uniqueid'], $module['html']);
											$db->setQuery("UPDATE #__modules SET `content`=".$db->Quote($module['html'])." WHERE id=".$db->Quote($module_id));
											$db->query();
											if (!$new_module) $updatedModules[]=array(
												'position_id'=>$position_id,
												'module_name'=>$module['module_name'],
												'module_title'=>$module['module_title'],
												'desc_label'=>'Module html content',
												'desc_value'=>'Changed',
											);
										}
										continue 2;
									case 'module_name':
										continue 2;
									case 'module_title':
										if (!isset($old_module_params[$key]) || $value!=$old_module_params[$key]) {
											$db->setQuery("UPDATE #__modules SET `title`=".$db->Quote($value)." WHERE id=".$db->Quote($module_id));
											$db->query();
											if (!$new_module) $updatedModules[]=array(
												'position_id'=>$position_id,
												'module_name'=>$module['module_name'],
												'module_title'=>$module['module_title'],
												'desc_label'=>'Module title',
												'desc_value'=>'Changed',
											);
										}
										continue 2;
									case 'position':
										if (!isset($old_module_params[$key]) || $value!=$old_module_params[$key]) {
											if (!$new_module) $updatedModules[]=array(
												'position_id'=>$position_id,
												'module_name'=>$module['module_name'],
												'module_title'=>$module['module_title'],
												'desc_label'=>'Position changed to',
												'desc_value'=>$position_id,
											);
										}
										continue 2;
									case 'show_module_at':
										if (!isset($old_module_params[$key]) || $value!=$old_module_params[$key]) {
											$db->setQuery("DELETE FROM #__modules_menu WHERE moduleid=".$db->Quote($module_id));
											$db->query();
											if ($value==0) {
												$db->setQuery("INSERT INTO #__modules_menu (`moduleid`, `menuid`) VALUES (".$db->Quote($module_id).", 0)");
												$db->query();
												$desc_value='All pages';
											} else {
												$db->setQuery("SELECT `id` FROM `#__menu` WHERE `home` = '1'");
												$home_ids=$db->loadColumn();
												foreach ($home_ids as $home_id) {
													if ($value==2) $home_id=-$home_id;
													$db->setQuery("INSERT INTO #__modules_menu (`moduleid`, `menuid`) VALUES (".$db->Quote($module_id).", ".$db->Quote($home_id).")");
													$db->query();
												}
												$desc_value=$value==1?'Homepage only':'All except homepage';
											}
											if (!$new_module) $updatedModules[]=array(
												'position_id'=>$position_id,
												'module_name'=>$module['module_name'],
												'module_title'=>$module['module_title'],
												'desc_label'=>'Module menu assignment',
												'desc_value'=>$desc_value,
											);
										}
										continue 2;
									default:
										$update=false;
										if (!isset($old_module_params[$key]) || $value!=$old_module_params[$key]) {
											$module_params[$key]=$value;
											if (!$new_module) $updatedModules[]=array(
												'position_id'=>$position_id,
												'module_name'=>$module['module_name'],
												'module_title'=>$module['module_title'],
												'desc_label'=>$key,
												'desc_value'=>$value,
											);
										}
										break;
								}
							}
							$db->setQuery("UPDATE #__modules SET `params`=".$db->Quote(json_encode($module_params)).", `position`=".$db->Quote($position_id).",`note`=".$db->Quote('linelabox:'.$module['uniqueid'])." WHERE id=".$db->Quote($module_id));
							$db->query();
						}
						if ($module_id_old) {
							unset($this->template_custom_data_old['modules'][$module['uniqueid']]);
						}
					}
				}
			}
		}
		$removedModules=array();
		if (!isset($this->template_custom_data_old['modules'])) {
			$this->template_custom_data_old['modules']=array();
		}
		if (count($this->template_custom_data_old['modules'])>0) {
			foreach ($this->template_custom_data_old['modules'] as $module) {
				$db->setQuery("SELECT id FROM #__modules WHERE id=".$db->Quote($module['module.id']));
				if ($db->loadResult()) {
					$db->setQuery("UPDATE #__modules SET published=-2 WHERE id=".$db->Quote($module['module.id']));
					$db->query();
					$module_instance=new ModulesModelModule;
					$pks=array($module['module.id']);
					$module_instance->delete($pks);
					$removedModules=json_decode(json_encode($module), true);
				}
			}
		}

		if (count($this->installedModules)>0 ||count($notInstalled)>0 || count($this->template_custom_data_old['modules'])>0 || count($updatedModules)>0 || count($newModules)>0) {
			echo '<ul class="installInfo level1">';
			if (count($this->installedModules)>0) {
				echo '<li class="installInfo level1">';
				echo '<div class="install_label">Following modules were newly installed</div><div class="install_warning">If you will uninstall the template, those modules will not be uninstalled automatically. You should remove them manually</div>';
				echo '<ul class="installInfo level2">';
				foreach ($this->installedModules as $item) {
					echo '<li class="installInfo level2">'.$item.'</li>';
				}
				echo '</ul>';
				echo '</li>';
			}
			if (count($notInstalled)>0) {
				//print_r($notInstalled);
				echo '<li class="installInfo level1">';
				echo '<div class="install_label">Following modules were not created, because they are not installed</div>';
				echo '<ul class="installInfo level2">';
				foreach ($notInstalled as $item) {
					echo '<li class="installInfo level2">'.$item['module_title'].' ('.$item['module_name'].')</li>';
				}
				echo '</ul>';
				echo '</li>';
			}
			if (count($removedModules)>0) {
				echo '<li class="installInfo level1">';
				echo '<div class="install_label">Following modules were removed from Joomla!</div>';
				echo '<ul class="installInfo level2">';
				foreach ($removedModules as $item) {
					echo '<li class="installInfo level2">'.$item['module_title'].' ('.$item['module_name'].')</li>';
				}
				echo '</ul>';
				echo '</li>';
			}
			if (count($updatedModules)>0) {
				echo '<li class="installInfo level1">';
				echo '<div class="install_label">Following modules were changed</div>';
				echo '<ul class="installInfo level2">';
				foreach ($updatedModules as $item) {
					echo '<li class="installInfo level2"><div class="line1">'.$item['module_title'].' ('.$item['module_name'].') at position '.$item['position_id'].'</div><div class="line2"><span class="desc_label">'.$item['desc_label'].'</span><span class="desc_value">'.$item['desc_value'].'</span></div></li>';
				}
				echo '</ul>';
				echo '</li>';
			}
			if (count($newModules)>0) {
				echo '<li class="installInfo level1">';
				echo '<div class="install_label">Following new modules were created</div><div class="install_warning">If you will uninstall the template, those modules will not be removed automatically. You should remove them manually</div>';
				echo '<ul class="installInfo level2">';
				foreach ($newModules as $item) {
					echo '<li class="installInfo level2">'.$item['module_title'].' ('.$item['module_name'].')</li>';
				}
				echo '</ul>';
				echo '</li>';
			}
			echo '<ul>';
		}
		$db->setQuery("UPDATE #__extensions SET `custom_data`=".$db->Quote(json_encode($this->template_custom_data_new))." WHERE extension_id=".$db->Quote($parent->get('extension')->get('extension_id')));
		$db->query();
	}

	function postflight( $type, $parent ) {		
		if (JFile::exists(JPATH_ROOT.'/templates/'.$parent->get('element').'/custom.css')) {
			JFile::move(JPATH_ROOT.'/templates/'.$parent->get('element').'/custom.css',
				JPATH_ROOT.'/templates/'.$parent->get('element').'/css/custom.css');
		} else {
			JFile::write(JPATH_ROOT.'/templates/'.$parent->get('element').'/css/custom.css', '');
		}
	}
}
