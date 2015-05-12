<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Template logo slider
 *
 * @package Yithemes
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_reset_query();

$args = array(
    'post_type' => 'logo'
);

$args['posts_per_page'] = (!is_null( $items )) ? $items : -1;

$tests = new WP_Query( $args );

$html = '';
if( !$tests->have_posts() ) return $html;

$is_slide = ( isset( $is_slide) && 'yes' == $is_slide ) ? true : false;
$logos_class = ( $is_slide ) ? 'logos-slides' : 'logo-list';
?>

<div class="margin-bottom">
    <div class="logos-slider wrapper">
        <h3><?php echo $title; ?></h3>
        <?php if( $is_slide ): ?>
            <div class="nav">
                <a class="prev border" href="#"><span class="main-text-color fa fa-angle-left"></span></a>
                <a class="next border" href="#"><span class="main-text-color fa fa-angle-right"></span></a>
            </div>
        <?php endif ?>
        <div class="list_carousel">

            <ul class="<?php echo esc_attr( $logos_class ) ?>" data-speed="<?php echo esc_attr( $speed )  ?>" data-items="<?php echo esc_attr( $nitems ) ?>">
            
                <?php
                    
                    while( $tests->have_posts() ) : $tests->the_post();
                        $logo_title = the_title( '<strong><a href="' . get_permalink() . '" class="name">', '</a></strong>', false );
                        $logo_link = yit_get_post_meta( get_the_ID(), '_logo_site' );
                ?>
                    <li style="height: <?php echo $height; ?>px;">
                        <?php
                            if ($logo_link != ''):
                                echo '<a href="' . esc_url($logo_link) . '" class="bwWrapper">';
                            else:
                                echo '<a href="#" class="bwWrapper" >';
                            endif;
                            
                            $image_id = get_post_thumbnail_id();  
                            $image_url = wp_get_attachment_image_src($image_id,'full');

                            echo '<img src="'. esc_url( $image_url[0] ) .'" style="max-height:'.$height.'px; width:auto" class="logo" width="'.$image_url[1].'" height="'.$image_url[2].'" alt="'.get_the_title().'" />';
							
                            echo '</a>';
                        ?>
                    </li>
                <?php endwhile; wp_reset_query(); ?>
            </ul>
        </div>
        <div class="clear"></div>

        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>

<?php
if ( $active_bw == 'yes' ) yit_enqueue_script( 'black-and-white', YIT_Logos()->plugin_assets_url . '/js/jQuery.BlackAndWhite.js', array( 'jquery' ), '', true );

echo $html;
