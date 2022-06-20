<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="eb-blog-grids">
	<div class="eb-blog-grid">
		<?php if ($showcasePost) { 
		?>
		<div class="test eb-blog-grid--12">
			<div id="eb-blog-grid-showcases" class="eb-blog-grid-showcases carousel slide mootools-noconflict" data-showcasepost-posts data-interval="false">
				<div class="carousel-inner">
					<?php $i = 0; ?>
					<?php foreach ($showcasePost as $post) { ?>
					<?php ++$i;?>
					<div class="item<?php echo $i == 1 ? ' active' : '';?>">
						<div class="eb-blog-grid-showcase">
							<?php if ($this->params->get('photo_show', true)) { ?>
								<?php if ($post->hasImage()) { ?>
								<div class="eb-blog-grid-showcase-cover">
									<a class="eb-blog-grid-showcase-cover__img" href="<?php echo $post->getPermalink(); ?>" style="background-image: url('<?php echo $post->getImage('large');?>');"></a>
								</div>
								<?php } ?>
							<?php } ?>
							<div class="eb-blog-grid-showcase-content<?php echo $this->params->get('photo_show', true) ? '' : ' no-cover'; ?>">
								<?php if ($this->params->get('authoravatar', true)) { ?>
								<a href="<?php echo $post->getAuthor()->getProfileLink(); ?>" class="showcase-avatar">
									<img class="showcase-avatar--rounded" src="<?php echo $post->getAuthor()->getAvatar(); ?>" alt="<?php echo $post->getAuthor()->getName(); ?>">
								</a>
								<?php } ?>
								<a href="<?php echo $post->getPermalink(); ?>">
									<h2 class="eb-blog-grid-showcase-content__title"><?php echo $post->title;?></h2>
								</a>
								<div class="eb-blog-grid-showcase-content__article">
									<span>
										<?php echo $post->getIntro(true, $showcaseTruncation, 'intro', null, array('forceTruncateByChars' => true, 'forceCharsLimit' => $this->params->get('showcase_content_limit', 350))); ?>
									</span>
								</div>
								<div class="eb-blog-grid-showcase-content__meta eb-blog-grid-showcase-content__meta--text">
									<?php if ($this->params->get('contentauthor', true)) { ?>
									<div class="eb-blog-grid-showcase-author">
										<span>
											<?php echo ucfirst(JText::_('COM_EASYBLOG_GRID_SHOWCASE_BY')); ?><a href="<?php echo $post->getAuthor()->getProfileLink(); ?>"><?php echo $post->getAuthor()->getName(); ?></a>
										</span>
									</div>
									<?php } ?>
									<div class="eb-blog-grid-showcase-category">
										<span>
											<?php echo JText::_('COM_EASYBLOG_GRID_SHOWCASE_POSTED_IN'); ?><a href="<?php echo $post->getPrimaryCategory()->getPermalink();?>"><?php echo $post->getPrimaryCategory()->title;?></a>
											<?php if ($this->params->get('contentdate' , true)) { ?>
												<?php echo JText::_('COM_EASYBLOG_GRID_SHOWCASE_ON'); ?>
												<?php echo $post->getDisplayDate()->format(JText::_('DATE_FORMAT_LC1')); ?>
											<?php } ?>
										</span>
									</div>
								</div>
								<div class="eb-blog-grid-showcase-content__more">
								<?php if ($this->params->get('showreadmore', true)) { ?>
									<a class="showcase-btn showcase-btn-more" href="<?php echo $post->getPermalink();?>"><?php echo JText::_('COM_EASYBLOG_GRID_SHOWCASE_READ_MORE');?></a>
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				<?php if (count($showcasePost) > 1) { ?>
				<div class="eb-blog-grid-showcase-control btn-group">
					<a class="btn btn-default btn-xs" href="#eb-blog-grid-showcases" role="button" data-bp-slide="prev">
						<span class="fa fa-angle-left"></span>
					</a>
					<a class="btn btn-default btn-xs" href="#eb-blog-grid-showcases" role="button" data-bp-slide="next">
						<span class="fa fa-angle-right"></span>
					</a>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<?php if ($posts) { ?>
			<?php foreach ($posts as $post) { 
			?>
			<div class="eb-blog-grid--<?php echo $gridLayout; ?>">
				<?php if ($this->params->get('grid_show_cover', true)) { ?>
				<div class="eb-blog-grid__thumb">
					<a class="eb-blog-grid-image" href="<?php echo $post->getPermalink(); ?>" style="background-image: url('<?php echo $post->getImage('medium');?>');">
						<!-- Featured label -->
						<?php if ($post->isFeatured()) { ?>
						<span class="eb-blog-grid-label">
							<i class="fa fa-bookmark"></i>
						</span>
						<?php } ?>
					</a>
				</div>
				<?php } ?>
				<div class="eb-blog-grid__title">
					<a href="<?php echo $post->getPermalink(); ?>"><?php echo $post->title; ?></a>
				</div>

				<!-- Grid meta -->
				<div class="eb-blog-grid__meta eb-blog-grid__meta--text">
					<?php if ($this->params->get('grid_show_author', true)) { ?>
					<div class="eb-blog-grid-author">
						<a href="<?php echo $post->getAuthor()->getProfileLink(); ?>"><?php echo $post->getAuthor()->getName(); ?></a>
					</div>
					<?php } ?>
					<div class="eb-blog-grid-date">
						<time class="eb-blog-grid-meta-date">
							<?php echo $post->getDisplayDate()->format(JText::_('DATE_FORMAT_LC1')); ?>
						</time>
					</div>
					<?php if ($this->params->get('grid_show_category', true)) { ?>
					<div class="eb-blog-grid-category">
						<a href="<?php echo $post->getPrimaryCategory()->getPermalink();?>"><?php echo $post->getPrimaryCategory()->title;?></a>
					</div>
					<?php } ?>
				</div>
				<div class="eb-blog-grid__body">
					<?php echo $post->getIntro(true, $gridTruncation, 'intro', null, array('forceTruncateByChars' => true, 'forceCharsLimit' => $this->params->get('grid_content_limit', 350))); ?>
				</div>
				<div class="eb-blog-grid__foot">
					
				</div>
			</div>
			<?php } ?>
		<?php } ?>
	</div>
	<?php if ($pagination) { ?>
		<?php echo $pagination;?>
	<?php } ?>
</div>
