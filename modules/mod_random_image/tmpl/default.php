<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_random_image
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="random-image<?php echo $moduleclass_sfx; ?>">
<?php if ($link) : ?>
<!--a href="<>?php echo $link; ?>"-->
<a href="#">
<?php endif; ?>
	<?php echo JHtml::_('image', $image->folder . '/' . htmlspecialchars($image->name, ENT_COMPAT, 'UTF-8'), htmlspecialchars($image->name, ENT_COMPAT, 'UTF-8'), array('width' => $image->width, 'height' => $image->height)); ?>
<?php if ($link) : ?>
</a>
<?php endif; ?>
</div>
