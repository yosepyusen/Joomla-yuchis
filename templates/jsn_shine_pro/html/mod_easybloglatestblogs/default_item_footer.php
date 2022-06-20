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
<?php if ($modules->config->get('main_ratings') && $params->get('showratings', false) && $post->showRating) { ?>
	<div class="eb-rating">
		<?php echo EB::ratings()->html($post, 'eblatestblogs-' . $post->id . '-ratings', JText::_('MOD_LATESTBLOGS_RATEBLOG'), !$enableRatings); ?>
	</div>
<?php } ?>

<?php if ($params->get('showhits' , false) || $params->get('showcommentcount', false) || $params->get('showreadmore', true)) { ?>
<div class="eb-mod-foot mod-muted mod-small lastest">
	<?php if ($params->get('showhits' , true)) { ?>
		<div class="mod-cell pr-10">
			<?php echo $post->hits;?> <?php echo JText::_('MOD_LATESTBLOGS_HITS');?>
		</div>
	<?php } ?>

	<?php if ($params->get('showcommentcount', false)) { ?>
		<div class="mod-cell pr-10">
			<a href="<?php echo $post->getPermalink();?>">
				<?php echo EB::string()->getNoun('MOD_LATESTBLOGS_COMMENT_COUNT', EB::comment()->getCommentCount($post), true);?>
			</a>
		</div>
	<?php } ?>

	<?php if ($params->get('showreadmore', true)) { ?>
		<div class="mod-cell">
			<a href="<?php echo $post->getPermalink(); ?>"><?php echo JText::_('MOD_LATESTBLOGS_READMORE'); ?></a>
		</div>
	<?php } ?>
</div>
<?php } ?>