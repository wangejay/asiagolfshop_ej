<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Markup of frontend
 *
 * @use $slider \YIT_Slider_Object The object of slider
 */

global $is_primary;
if( ! defined( 'YIT_SLIDER_USED' ) ){
    define( 'YIT_SLIDER_USED', true );
}

extract( array(
    'slider_id'  => 'slider-' . $this->index,
    'height'     => $slider->get('config-height'),
    'autoplay'   => $slider->get('config-autoplay'),
    'interval'   => $slider->get('config-interval') * 1000,
    'controlnav' => $slider->get('config-controlnav'),
) );

$height_inline = ( empty( $height ) ) ? '' : "height:{$height}px;";
$height_small = ( $height - 6 ) / 2;

$small = false;
$prev_size = '';
?>
    <!-- BEGIN FLEXSLIDER SLIDER -->
    <div id="<?php echo esc_attr( $slider_id ) ?>" <?php $slider->item_class('swiper_container masterslider ms-skin-default') ?> style="<?php echo esc_attr( $height_inline ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ) ?>" data-interval="<?php echo esc_attr( $interval ) ?>" data-height="<?php echo esc_attr( $height ) ?>" >

        <div class="swiper-wrapper">

			<?php while( $slider->have_posts() ) : $slider->the_post(); $size = '' == $slider->get('size') ? 'big' : $slider->get('size'); ?>

			<?php if ( $prev_size != 'small' || ( $prev_size == 'small' && $size == 'big' ) ) : ?>

				<?php if ( ! $slider->is_first() ) : ?>
					</div>
				</div>
				<!-- END SLIDE -->
				<?php endif; ?>

				<!-- START SLIDE -->
				<div class="swiper-slide swiper-slide-<?php echo esc_attr( $size ) ?>" style="<?php echo esc_attr( $height_inline ); ?>">
					<div class="slide-inner">

			<?php endif; ?>

						<div class="slide-wrapper">
							<?php $slider->the( 'featured-content', array(
								'container' => false
							)); ?>
							<div class="slide-text<?php echo 'yes' == $slider->get('border-text') ? ' border' : ''; ?> <?php $slider->the('position-text'); ?>">
								<h4><?php echo yit_decode_title( $slider->get('small-text') ); ?></h4>
								<h3><?php echo yit_decode_title( $slider->get('big-text') );   ?></h3>
							</div>
							<?php if ( '' != $slider->get('link') ) : ?><a href="<?php esc_url( $slider->the('link') ) ?>" class="slide-link"></a><?php endif; ?>
						</div>

				<?php $prev_size = $prev_size == 'small' ? '' : $size; ?>

			<?php endwhile; ?>

					</div>
				</div>
				<!-- END SLIDE -->

        </div>

        <?php if ( 'yes' == $controlnav ) : ?>
        <a href="#" class="prev"><?php _e( 'Previous', 'yit' ) ?></a>
        <a href="#" class="next"><?php _e( 'Next', 'yit' ) ?></a>
        <?php endif; ?>

    </div>

<?php $slider->reset_query() ?>