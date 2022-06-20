<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Create a shortcut for params.
$params = &$this->item->params;
$images = json_decode($this->item->images);
$canEdit = $this->item->params->get('access-edit');
$info    = $this->item->params->get('info_block_position', 0);


// Get content type.
if ( ( $attribs = json_decode($this->item->attribs) ) && ! empty($attribs->sunfw_article_type) )
{
	$type = $attribs->sunfw_article_type;
}

?>

<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
	<div class="system-unpublished">
<?php endif; ?>

<?php if ($params->get('show_title')) : ?>
	<h2 class="item-title" itemprop="name">
	<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>" itemprop="url">
			<?php echo $this->escape($this->item->title); ?>
		</a>
	<?php else : ?>
		<?php echo $this->escape($this->item->title); ?>
	<?php endif; ?>
	</h2>
<?php endif; ?>

<?php if ($this->item->state == 0) : ?>
	<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
<?php endif; ?>
<?php if (strtotime($this->item->publish_up) > strtotime(JFactory::getDate())) : ?>
	<span class="label label-warning"><?php echo JText::_('JNOTPUBLISHEDYET'); ?></span>
<?php endif; ?>
<?php if ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00') : ?>
	<span class="label label-warning"><?php echo JText::_('JEXPIRED'); ?></span>
<?php endif; ?>

<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
	<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
<?php endif; ?>

<?php // Todo Not that elegant would be nice to group the params ?>
<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') ); ?>

<?php if ($useDefList && ($info == 0 ||  $info == 2)) : ?>
	<dl class="article-info  text-muted">
		<dt class="article-info-term hide">
		<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
		</dt>

		<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
			<dd class="createdby" itemprop="author" itemscope itemtype="http://schema.org/Person">
				<i class="fa fa-user"></i> 
				<?php $author = ($this->item->created_by_alias) ? $this->item->created_by_alias : $this->item->author; ?>
				<?php $author = '<span itemprop="name">' . $author . '</span>'; ?>
				<?php if (!empty($this->item->contact_link) && $params->get('link_author') == true) : ?>
					<?php echo JHtml::_('link', $this->item->contact_link, $author, array('itemprop' => 'url')); ?>
				<?php else: ?>
					<?php echo $author; ?>
				<?php endif; ?>
			</dd>
		<?php endif; ?>

		<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
			<dd class="parent-category-name">
				<i class="fa fa-folder-open"></i> 
				<?php $title = $this->escape($this->item->parent_title); ?>
				<?php if ($params->get('link_parent_category') && !empty($this->item->parent_slug)) : ?>
					<?php $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '" itemprop="genre">' . $title . '</a>'; ?>
					<?php echo $url; ?>
				<?php else : ?>
					<?php echo '<span itemprop="genre">' . $title . '</span>'; ?>
				<?php endif; ?>
			</dd>
		<?php endif; ?>

		<?php if ($params->get('show_category')) : ?>
			<dd class="category-name">
				<i class="fa fa-folder-open"></i> 
				<?php $title = $this->escape($this->item->category_title); ?>
				<?php if ($params->get('link_category') && $this->item->catslug) : ?>
					<?php $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '" itemprop="genre">' . $title . '</a>'; ?>
					<?php echo $url; ?>
				<?php else : ?>
					<?php echo '<span itemprop="genre">' . $title . '</span>'; ?>
				<?php endif; ?>
			</dd>
		<?php endif; ?>

		<?php if ($params->get('show_publish_date')) : ?>
			<dd class="published">
				<i class="fa fa-calendar"></i>
				<time datetime="<?php echo JHtml::_('date', $this->item->publish_up, 'c'); ?>" itemprop="datePublished">
					<?php echo JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3')); ?>
				</time>
			</dd>
		<?php endif; ?>

		<?php if ($info == 0) : ?>
			<?php if ($params->get('show_modify_date')) : ?>
				<dd class="modified">
					<i class="fa fa-calendar"></i>
					<time datetime="<?php echo JHtml::_('date', $this->item->modified, 'c'); ?>" itemprop="dateModified">
						<?php echo JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3')); ?>
					</time>
				</dd>
			<?php endif; ?>

			<?php if ($params->get('show_create_date')) : ?>
				<dd class="create">
					<i class="fa fa-calendar"></i>
					<time datetime="<?php echo JHtml::_('date', $this->item->created, 'c'); ?>" itemprop="dateCreated">
						<?php echo JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3')); ?>
					</time>
				</dd>
			<?php endif; ?>

			<?php if ($params->get('show_hits')) : ?>
				<dd class="hits">
					<i class="fa fa-eye"></i> 
					<meta itemprop="interactionCount" content="UserPageVisits:<?php echo $this->item->hits; ?>" />
					<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
				</dd>
			<?php endif; ?>

		<?php endif; ?>
	</dl>
<?php endif; ?>

<?php
if ( ! empty($type) )
{
	echo $this->loadTemplate("type_{$type}");
}else {
	if (isset($images->image_intro) && !empty($images->image_intro)) : ?>
		<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
		<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image img-responsive"> <img
				<?php if ($images->image_intro_caption):
					$captionClass = "";
					echo $captionClass="caption".' title="' .htmlspecialchars($images->image_intro_caption) .'"';
				endif; ?>
				src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" class="img-responsive <?php echo $captionClass;?>"/> </div>
	<?php endif;
}
?>

<?php if (!$params->get('show_intro')) : ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>
<?php endif; ?>
<?php echo $this->item->event->beforeDisplayContent; ?>
<div class="intro-text">
	<?php echo $this->item->introtext; ?>
</div>

<?php if ($useDefList && ($info == 1 ||  $info == 2)) : ?>
	<dl class="article-info text-muted">
		<dt class="article-info-term hide">
			<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
		</dt>
		<?php if ($info == 1) : ?>
			<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
				<dd class="createdby" itemprop="author" itemscope itemtype="http://schema.org/Person">
					<i class="fa fa-user"></i>
					<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
					<?php $author = '<span itemprop="name">' . $author . '</span>'; ?>
					<?php if (!empty($this->item->contact_link) && $params->get('link_author') == true) : ?>
						<?php echo JHtml::_('link', $this->item->contact_link, $author, array('itemprop' => 'url')); ?>
					<?php else : ?>
						<?php echo $author; ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>

			<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
				<dd class="parent-category-name">
					<i class="fa fa-folder-open"></i> 
					<?php $title = $this->escape($this->item->parent_title); ?>
					<?php if ($params->get('link_parent_category') && $this->item->parent_slug) : ?>
						<?php $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '" itemprop="genre">' . $title . '</a>'; ?>
						<?php echo $url; ?>
					<?php else : ?>
						<?php echo '<span itemprop="genre">' . $title . '</span>'; ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>

			<?php if ($params->get('show_category')) : ?>
				<dd class="category-name">
					<i class="fa fa-folder-open"></i>
					<?php $title = $this->escape($this->item->category_title); ?>
					<?php if ($params->get('link_category') && $this->item->catslug) : ?>
						<?php $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '" itemprop="genre">' . $title . '</a>'; ?>
						<?php echo $url; ?>
					<?php else : ?>
						<?php echo '<span itemprop="genre">' . $title . '</span>'; ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>

			<?php if ($params->get('show_publish_date')) : ?>
				<dd class="published">
					<i class="fa fa-calendar"></i>
					<time datetime="<?php echo JHtml::_('date', $this->item->publish_up, 'c'); ?>" itemprop="datePublished">
						<?php echo JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3')); ?>
					</time>
				</dd>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ($params->get('show_create_date')) : ?>
			<dd class="create">
				<i class="fa fa-calendar"></i>
				<time datetime="<?php echo JHtml::_('date', $this->item->created, 'c'); ?>" itemprop="dateCreated">
					<?php echo JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3')); ?>
				</time>
			</dd>
		<?php endif; ?>

		<?php if ($params->get('show_modify_date')) : ?>
			<dd class="modified">
				<i class="fa fa-calendar"></i>
				<time datetime="<?php echo JHtml::_('date', $this->item->modified, 'c'); ?>" itemprop="dateModified">
					<?php echo JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3')); ?>
				</time>
			</dd>
		<?php endif; ?>

		<?php if ($params->get('show_hits')) : ?>
			<dd class="hits">
				<i class="fa fa-eye"></i> 
				<meta itemprop="interactionCount" content="UserPageVisits:<?php echo $this->item->hits; ?>" />
				<?php echo $this->item->hits; ?>
			</dd>
		<?php endif; ?>

		<?php if ($this->params->get('show_tags', 1)) : ?>
			<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
			<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
		<?php endif; ?>
	</dl>
<?php endif; ?>

<?php if ($params->get('show_readmore') && $this->item->readmore) :
	if ($params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		$link = new JUri($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif; ?>

	<?php echo JLayoutHelper::render('joomla.content.readmore', array('item' => $this->item, 'params' => $params, 'link' => $link)); ?>

<?php endif; ?>

<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
</div>
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?>