<?php
/**
* @package     Joomla.Site
* @subpackage  Templates.Linelabox
* @copyright   Copyright (C) 2018 Linelab.org. All rights reserved.
* @license     GNU General Public License version 2.
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

function modChrome_style($module, &$params, &$attribs) {
	$moduleTag      = $params->get('module_tag', 'div');
	$headerTag     = htmlspecialchars($params->get('header_tag', 'h3'), ENT_COMPAT, 'UTF-8');
	$headerClass    = htmlspecialchars($params->get('header_class'), ENT_COMPAT, 'UTF-8');
	$moduleTagAttribs=empty($attribs['attribs'])?'':$attribs['attribs'];
	if (!empty ($module->content)) : ?>
	<<?php echo $moduleTag; ?> class="module-style style_box <?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>" <?php echo $moduleTagAttribs;?>>	
		<?php if ($module->showtitle != 0) : ?>
		<<?php echo $headerTag; ?> class="style_header <?php echo $headerClass; ?> showtitle header_tag"><span><?php echo $module->title; ?></span></<?php echo $headerTag; ?>>
		<?php endif; ?>
		<div class="style_content module <?php echo $module->module; ?>">
		   <?php echo $module->content; ?>
		</div>
		</<?php echo $moduleTag; ?>>
	<?php endif;    
} 
