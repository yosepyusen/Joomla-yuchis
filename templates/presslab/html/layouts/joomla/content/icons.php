<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

//JHtml::_('bootstrap.framework');

$canEdit = $displayData['params']->get('access-edit');
$articleId = $displayData['item']->id;

?>


	<?php if (empty($displayData['print'])) : ?>

		<?php if ($canEdit || $displayData['params']->get('show_print_icon') || $displayData['params']->get('show_email_icon')) : ?>
				<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
				<div class="pull-right">
					<?php if ($displayData['params']->get('show_print_icon')) : ?>
						<span class="print-icon"><i class="fa fa-print" aria-hidden="true"></i> <?php echo JHtml::_('icon.print_popup', $displayData['item'], $displayData['params']); ?> </span>
					<?php endif; ?>
					<?php if ($displayData['params']->get('show_email_icon')) : ?>
						<span class="email-icon"><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo JHtml::_('icon.email', $displayData['item'], $displayData['params']); ?> </span>
					<?php endif; ?>
					<?php if ($canEdit) : ?>
						<span class="edit-icon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?php echo JHtml::_('icon.edit', $displayData['item'], $displayData['params']); ?> </span>
					<?php endif; ?>
				</div>
		<?php endif; ?>

	<?php else : ?>
	<div class="pull-right">
			<?php echo JHtml::_('icon.print_screen', $displayData['item'], $displayData['params']); ?>
</div>
	<?php endif; ?>

