<?php 
/**
 * @package modules
 * @since       January 2018
 * @author      Linelab http://www.linelabox.com
 * @copyright   Copyright (C) 2018 Linelab. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
defined('_JEXEC') or die;
?>
<div class="logo_custom">
<?php if ($params->get('logoimage')) : ?>
<a href="<?php echo JURI::root();?>" class="logo"><img src="<?php echo $params->get('logoimage');?>" alt="Logo"></a>
<?php endif; ?>
<?php if ($params->get('logoslogan')) : ?>
<p><?php echo $params->get('logoslogan');?></p>
<?php endif; ?>
</div>