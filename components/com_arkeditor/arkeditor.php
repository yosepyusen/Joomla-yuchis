<?php
/*------------------------------------------------------------------------
# Copyright (C) 2005-2012 WebxSolution Ltd. All Rights Reserved.
# @license - GPLv2.0
# Author: WebxSolution Ltd
# Websites:  http://webx.solutions
# Terms of Use: An extension that is derived from the ARK Editor will only be allowed under the following conditions: http://arkextensions.com/terms-of-use
# ------------------------------------------------------------------------*/ 

defined( '_JEXEC' ) or die();

// Require specific controller
// Controller


if (!JFactory::getUser()->authorise('core.manage', 'com_menus'))
{
	throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}


$app = JFactory::getApplication();


$lang = JFactory::getLanguage();
$lang->load('joomla', JPATH_ADMINISTRATOR);
$lang->load('com_banners', JPATH_ADMINISTRATOR, null, false, true)
||	$lang->load('com_banners', JPATH_SITE, null, false, true);
$lang->load('com_categories', JPATH_ADMINISTRATOR, null, false, true)
||	$lang->load('com_categories', JPATH_SITE, null, false, true);


JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_banners/tables');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_categories/tables');

require_once JPATH_ADMINISTRATOR . '/includes/toolbar.php';

// Execute the task.
$controller = JControllerLegacy::getInstance('Arkeditor');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
  