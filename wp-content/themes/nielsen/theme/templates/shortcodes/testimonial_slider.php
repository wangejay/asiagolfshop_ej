<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

wp_reset_query();

$args = array(
    'post_type' => 'testimonial'
);

$text_type = YIT_Testimonial()->get_option( 'text-type-testimonials' );

$args['posts_per_page'] = ( ! is_null( $items ) ) ? $items : - 1;

if ( isset( $cat ) && ! empty( $cat ) ) {
    $cat               = array_map( 'trim', explode( ',', $cat ) );
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'category-testimonial',
            'field'    => 'id',
            'terms'    => $cat
        )
    );
}

$tests = new WP_Query( $args );
$count_posts = wp_count_posts( 'testimonial' );
$text_type = YIT_Testimonial()->get_option( 'text-type-testimonials' );
$thumbnail = ( YIT_Testimonial()->get_option( 'thumbnail-testimonials' ) == '' ) ? 'yes' : YIT_Testimonial()->get_option( 'thumbnail-testimonials' );

if ( $count_posts->publish == 1 ) {
    $is_slider = false;
}
else {
    $is_slider = true;
}

$html = '';
if ( ! $tests->have_posts() ) {
    return $html;
}

$pagination             = ( $pagination == 'yes') ? 'true' : 'false';
$navigation             = ( $navigation == 'yes') ? 'true' : 'false';
$autoplay               = ( $autoplay == 'yes') ? 'true' : 'false';
$general_border_color   = yit_get_option( 'color-website-border-style-2' );
$title_sc_args          = array(
    'font_size'             => apply_filters( 'yit_testimonial_slider_title_font', 18 ),
    'font_alignment'        => apply_filters( 'yit_testimonial_slider_title_font_alignment', 'center' ),
    'border'                => apply_filters( 'yit_testimonial_slider_title_border', 'bottom-little-line' ),
    'border_color'          => apply_filters( 'yit_testimonial_slider_title_border_color', $general_border_color['color'] )
);
$title_shortcode       = '[box_title font_size="' . $title_sc_args['font_size'] . '" font_alignment="' . $title_sc_args['font_alignment'] . '" border="' . $title_sc_args['border'] . '" border_color="' . $title_sc_args['border_color'] . '"]%content%[/box_title]';

?>

<div class="testimonials-slider">
    <?php if( isset( $title_text ) && ! empty( $title_text ) ) echo do_shortcode( str_replace( '%content%', $title_text, $title_shortcode ) ); ?>
    <?php if ( $is_slider ): ?>
        <ul class="testimonial-content owl-slider hide-elem" data-slidespeed = "<?php echo esc_attr( $speed ) ?>" data-pagination = "<?php echo $pagination ?>" data-paginationspeed = "<?php echo esc_attr( $paginationspeed ) ?>" data-navigation = "<?php echo $navigation ?>" data-autoplay = "<?php echo $autoplay ?>" data-singleitem = "true" >
    <?php else: ?>
        <ul class="testimonial-content">
    <?php endif ?>
        <?php
        //loop
        $c = 0;
        while ( $tests->have_posts() ) : $tests->the_post();

            $length = create_function( '', "return $excerpt;" );
            add_filter( 'excerpt_length', $length );

            $title  = the_title( '<span class="name main-text-color">', '</span>', false );
            $text   = (strcmp($text_type, 'content') == 0) ? get_the_content() : get_the_excerpt();
            remove_filter( 'excerpt_length', $length );

            $label          = yit_get_post_meta( get_the_ID(), '_yit_testimonial_social' );
            $role           = yit_get_post_meta( get_the_ID(), '_yit_testimonial_role' );
            $small_quote    = yit_get_post_meta( get_the_ID(), '_yit_testimonial_small_quote' );
            ?>

            <li class='item'>
                 <?php if ( $small_quote != '' ): ?>
                    <h4 class="testimonial-smallquote"><?php echo $small_quote ?></h4>
                <?php endif ?>
                <p><?php echo $text ?></p>

                <p class="meta">
                    <?php echo $title; ?>
                    <span class="role main-text-color">
                        <?php echo $role; ?>
                    </span>
                </p>
            </li>

            <?php $c ++; endwhile; ?>

    </ul>

</div>
<?php echo $html; ?>
