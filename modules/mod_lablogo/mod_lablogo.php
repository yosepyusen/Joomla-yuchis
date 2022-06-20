<?php
/**
 * @package modules
 * @since       February 2017
 * @author      Linelab http://www.linelabox.com
 * @copyright   Copyright (C) 2017 Linelab. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
defined('_JEXEC') or die;
$layout = $params->get('layout', 'default');
$app = JFactory::getApplication();

$document = JFactory::getDocument();

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
require JModuleHelper::getLayoutPath('mod_lablogo', $params->get('layout', 'default'));
