<?php 
/**
 * @package modules
 * @since       February 2017
 * @author      Linelab http://www.linelabox.com
 * @copyright   Copyright (C) 2017 Linelab. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * Bootstrap, jQuery, TouchSwipe, Animate.css, FontAwesome
 */
 
defined('_JEXEC') or die;
?>
        <div id="bootstrap-touch-slider" class="bootstrap-touch-slider carousel bs-slider <?php echo $params->get('slidetype'); ?> control-round indicators-line <?php echo $moduleclass_sfx; ?>" data-ride="carousel" data-pause="hover" data-interval="10000" >

            <!-- Indicators -->
			<?php if ($params->get('indicators')) : ?>
            <ol class="carousel-indicators hidden-xs">
                <?php for ($i=0; $i<$params->get('numberofslides'); $i++) :?>
                <?php $ii=$i+1; ?>
                <?php if (empty($params->get($ii.'headingtext')) && empty($params->get($ii.'showimages'))) continue; ?>
                <li data-target="#bootstrap-touch-slider" data-slide-to="<?php echo $i;?>" class="<?php echo $i==0?'active':'';?>"></li>
                <?php endfor; ?>
            </ol>
	<?php endif; ?>
            <!-- Wrapper For Slides -->
            <div class="carousel-inner" role="listbox">
                <?php for ($i=0; $i<$params->get('numberofslides'); $i++) :?>
                <?php $ii=$i+1; ?>
                <?php if (empty($params->get($ii.'headingtext')) && empty($params->get($ii.'showimages'))) continue; ?>
                <div class="item <?php echo $i==0?'active':'';?>">
                    <!-- Slide Background -->
				<?php if ($params->get('showimage')) : ?>
                    <?php
                    if (preg_match('/^vimeo:(.*)/',$params->get($ii.'showimages'), $matches)) {
                        ?>
                    <div class="video-background">
                        <iframe src="//player.vimeo.com/video/<?php echo $matches[1];?>?title=0&portrait=0&byline=0&autoplay=1&background=1&loop=1" allowfullscreen></iframe>
                    </div>
                        <?php

                    } else {
                        ?><img src="<?php echo $params->get($ii.'showimages'); ?>" alt=""  class="slide-image"/><?php
                    }                    
                    ?>
					<?php else : ?>
					  <div class="bs-slider-overlay"></div>
<?php endif; ?>
                    <div class="container">
                        <div class="row">
                            <!-- Slide Text Layer -->
                            <div class="slide-text <?php echo $params->get($ii.'slidestyle'); ?>">
                                <<?php echo $params->get('headtype'); ?> data-animation="animated <?php echo $params->get($ii.'datanimation1'); ?>"><?php echo $params->get($ii.'headingtext'); ?></<?php echo $params->get('headtype'); ?>>
                                <p data-animation="animated <?php echo $params->get($ii.'datanimation2'); ?>"><?php echo $params->get($ii.'slidetext'); ?></p>                            
<?php if ($params->get($ii.'showbt1')) : ?>	<a href="<?php echo $params->get($ii.'urlbt1'); ?>" class="btn btn-default" data-animation="animated <?php echo $params->get($ii.'datanimation3'); ?>"><?php echo $params->get($ii.'button1text'); ?></a><?php endif; ?>
<?php if ($params->get($ii.'showbt2')) : ?> <a href="<?php echo $params->get($ii.'urlbt2'); ?>" class="btn btn-primary second" data-animation="animated <?php echo $params->get($ii.'datanimation4'); ?>"><?php echo $params->get($ii.'button2text'); ?></a> <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
				
            </div><!-- End of Wrapper For Slides -->

				<?php if ($params->get('controls')) : ?>
            <!-- Left Control -->
            <a class="left carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="prev">
                <span class="fa fa-angle-left" aria-hidden="true"></span>
                <span class="sr-only"><?php echo JText::_('MOD_LABSLIDESHOW_PREVIOUS'); ?></span>
            </a>

            <!-- Right Control -->
            <a class="right carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="next">
                <span class="fa fa-angle-right" aria-hidden="true"></span>
                <span class="sr-only"><?php echo JText::_('MOD_LABSLIDESHOW_NEXT'); ?></span>
            </a>
	<?php endif; ?>
        </div> <!-- End Slider -->  
