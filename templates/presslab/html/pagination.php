<?php
/**
* @package     Joomla.Site
* @subpackage  Templates.Linelabox
* @copyright   Copyright (C) 2018 Linelab.org. All rights reserved.
* @license     GNU General Public License version 2.
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

function pagination_list_footer($list)
{
	$html = "<div class=\"paginationlab\">\n";
	$html .= $list['pageslinks'];
	$html .= "\n<input type=\"hidden\" name=\"" . $list['prefix'] . "limitstart\" value=\"" . $list['limitstart'] . "\" />";
	$html .= "\n</div>";
	return $html;
}
function pagination_list_render($list)
{
	$currentPage = 1;
	$range = 1;
	$step = 5;
	foreach ($list['pages'] as $k => $page)
	{
		if (!$page['active'])
		{
			$currentPage = $k;
		}
	}
	if ($currentPage >= $step)
	{
		if ($currentPage % $step == 0)
		{
			$range = ceil($currentPage / $step) + 1;
		}
		else
		{
			$range = ceil($currentPage / $step);
		}
	}
	$html = '<ul class="pagination">';
	$html .= $list['start']['data'];
	$html .= $list['previous']['data'];
	foreach ($list['pages'] as $k => $page)
	{
		if (in_array($k, range($range * $step - ($step + 1), $range * $step)))
		{
			if (($k % $step == 0 || $k == $range * $step - ($step + 1)) && $k != $currentPage && $k != $range * $step - $step)
			{
				$page['data'] = preg_replace('#(<a.*?>).*?(</a>)#', '$1...$2', $page['data']);
			}
		}
		$html .= $page['data'];
	}
	$html .= $list['next']['data'];
	$html .= $list['end']['data'];
	$html .= '</ul>';
	return $html;
}
function pagination_item_active(&$item)
{
	$class = '';
	// Check for "Start" item
	if ($item->text == JText::_('JLIB_HTML_START'))
	{
		$display = '<i class="fa fa-angle-double-left"></i>';
	}
	// Check for "Prev" item
	if ($item->text == JText::_('JPREV'))
	{
		$display = '<i class="fa fa-angle-left"></i>';
	}
	// Check for "Next" item
	if ($item->text == JText::_('JNEXT'))
	{
		$display = '<i class="fa fa-angle-right"></i>';
	}
	// Check for "End" item
	if ($item->text == JText::_('JLIB_HTML_END'))
	{
		$display = '<i class="fa fa-angle-double-right"></i>';
	}
	// If the display object isn't set already, just render the item with its text
	if (!isset($display))
	{
		$display = $item->text;
		$class   = ' class="hidden-phone"';
	}
	return '<li' . $class . '><a title="' . $item->text . '" href="' . $item->link . '" class="pagenav">' . $display . '</a></li>';
}
/**
 * Renders an inactive item in the pagination block
 *
 * @param   JPaginationObject  $item  The current pagination object
 *
 * @return  string  HTML markup for inactive item
 *
 * @since   3.0
 */
function pagination_item_inactive(&$item)
{
	// Check for "Start" item
	if ($item->text == JText::_('JLIB_HTML_START'))
	{
		return '<li class="disabled"><a><i class="fa fa-angle-double-left"></i></a></li>';
	}
	// Check for "Prev" item
	if ($item->text == JText::_('JPREV'))
	{
		return '<li class="disabled"><a><i class="fa fa-angle-left"></i></a></li>';
	}
	// Check for "Next" item
	if ($item->text == JText::_('JNEXT'))
	{
		return '<li class="disabled"><a><i class="fa fa-angle-right"></i></a></li>';
	}
	// Check for "End" item
	if ($item->text == JText::_('JLIB_HTML_END'))
	{
		return '<li class="disabled"><a><i class="fa fa-angle-double-right"></i></a></li>';
	}
	// Check if the item is the active page
	if (isset($item->active) && ($item->active))
	{
		return '<li class="active hidden-phone"><a>' . $item->text . '</a></li>';
	}
	// Doesn't match any other condition, render a normal item
	return '<li class="disabled hidden-phone"><a>' . $item->text . '</a></li>';
}