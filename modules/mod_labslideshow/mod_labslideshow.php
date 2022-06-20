<?php
/**
 * @package modules
 * @since       February 2017
 * @author      Linelab http://www.linelabox.com
 * @copyright   Copyright (C) 2017 Linelab. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * Bootstrap, jQuery, TouchSwipe, Animate.css, FontAwesome
 */
 
defined('_JEXEC') or die;
$layout = $params->get('layout', 'default');
$app = JFactory::getApplication();

JHTML::stylesheet('modules/mod_labslideshow/css/slider.css' );
JHtml::script('modules/mod_labslideshow/js/slider.min.js');   
JHtml::script('modules/mod_labslideshow/js/slider.init.js');   
$document = JFactory::getDocument();
//$document->addScriptDeclaration('jQuery(function($){$(".bootstrap-touch-slider").bsTouchSlider();});');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
require JModuleHelper::getLayoutPath('mod_labslideshow', $params->get('layout', 'default'));
