<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;

$layoutV	= new JLayoutFile('button_product_view', null, array('component' => 'com_phocacart'));
$layoutP	= new JLayoutFile('product_price', null, array('component' => 'com_phocacart'));

echo '<div class="ph-product-module-box ph-product-module-box'.$moduleclass_sfx .'">';

if ($p['module_description'] != '') {
	echo '<div class="ph-mod-desc">'.$p['module_description'].'</div>';
}
if (!empty($products)) {
	foreach ($products as $k => $v) {
		
		echo '<div class="ph-thumbsn"><div class="thumbnail ph-thumbnail">';

		$image = PhocacartImage::getThumbnailName($t['pathitem'], $v->image, 'medium');
		$link = JRoute::_(PhocacartRoute::getItemRoute($v->id, $v->catid, $v->alias, $v->catalias));
		echo '<a href="'.$link.'">';
		if (isset($image->rel) && $image->rel != '') {
			echo '<img src="'.JURI::base(true).'/'.$image->rel.'" alt="" class="img-responsive ph-image"';
			echo ' />';
		}
		echo '</a>';
		

		// CAPTION, DESCRIPTION
		echo '<div class="caption">';
		echo '<h3>'.$v->title.'</h3>';
		
		// Description box will be displayed even no description is set - to set height and have all columns same height
		echo '<div class="ph-item-desc">';
		if ($v->description != '' && (int)$p['display_product_description'] > 0) {
			echo $v->description;
		}
		echo '</div>';// end desc
		
		// :L: PRICE
		if ($p['hide_price'] != 1) {
			$price 				= new PhocacartPrice;
			$d					= array();
			$d['priceitems']	= $price->getPriceItems($v->price, $v->taxid, $v->taxrate, $v->taxcalculationtype, $v->taxtitle, $v->unit_amount, $v->unit_unit, 1);
			$d['priceitemsorig']= array();
			if ($v->price_original != '' && $v->price_original > 0) {
				$d['priceitemsorig'] = $price->getPriceItems($v->price_original, $v->taxid, $v->taxrate, $v->taxcalculationtype);
			}
			$d['class']			= 'ph-category-price-box';// we need the same class as category or items view
			$d['product_id']	= (int)$v->id;
			$d['typeview']		= 'Module';
			echo $layoutP->render($d);
		}
		
		// VIEW PRODUCT BUTTON
		echo '<div class="ph-category-add-to-cart-box">';
		
		// :L: LINK TO PRODUCT VIEW
		if ((int)$p['display_view_product_button'] > 0) {
			$d									= array();
			$d['link']							= $link;
			$d['display_view_product_button'] 	= $p['display_view_product_button'];
			echo $layoutV->render($d);
		}
	
		
		echo '</div>';// end add to cart box
		echo '<div class="clearfix"></div>';

		
		echo '</div>';// end caption
		
		echo '</div></div>';// end thumbnail
	}
}
echo '</div><div class="clearfix"></div>';
?>


