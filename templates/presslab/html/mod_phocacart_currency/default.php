<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;

echo '<div class="ph-currency-select-box ph-currency-select-box'.$moduleclass_sfx .'">';
echo '<form action="'.$linkCheckout.'" method="post">';
echo $selectBox;
echo '<input type="hidden" name="task" value="checkout.currency">';
echo '<input type="hidden" name="tmpl" value="component" />';
echo '<input type="hidden" name="option" value="com_phocacart" />';
echo '<input type="hidden" name="return" value="'.$actionBase64.'" />';
echo '<div class="ph-input-select-currencies-button">';
echo '<button class="btn btn-primary btn-sm ph-btn"><span class="glyphicon glyphicon-refresh"></span> '.JText::_('COM_PHOCACART_CHANGE_CURRENCY').'</button>';
echo '</div><div class="ph-cb"></div>';
echo JHtml::_('form.token');
echo '</form>';
echo '</div>';
echo '<div class="ph-cb"></div>';

?>


