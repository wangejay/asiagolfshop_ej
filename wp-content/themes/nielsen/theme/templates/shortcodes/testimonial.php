<?php

/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

//yit_enqueue_script( 'yit-testimonial', YIT_Testimonial()->plugin_assets_url . '/js/yit-testimonial-frontend.min.js', array( 'jquery' ), null, true );
//yit_enqueue_script( 'owl-carousel', YIT_Testimonial()->plugin_assets_url . '/js/owl.carousel.min.js', array( 'jquery' ) );


wp_reset_query();

$args = array(
    'post_type' => 'testimonial'
);

$args['posts_per_page'] = ( isset( $items ) && $items != '' ) ? $items : - 1;

if ( isset( $cat ) && !empty( $cat ) ) {
    $cat               = array_map( 'trim', explode( ',', $cat ) );
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'category-testimonial',
            'field'    => 'id',
            'terms'    => $cat
        )
    );
}

$options     = get_option( 'yit_testimonial_options' );
$text_type   = YIT_Testimonial()->get_option( 'text-type-testimonials' );
$limit_words = YIT_Testimonial()->get_option( 'limit-words-testimonials' );
$limit       = ( $limit_words != '' ) ? $limit_words : 50;
$thumbnail   = ( YIT_Testimonial()->get_option( 'thumbnail-testimonials' ) == '' ) ? 'yes' : YIT_Testimonial()->get_option( 'thumbnail-testimonials' );

$sidebars            = YIT_Layout()->sidebars;
$bootstrap_col_class = '';

if ( $sidebars['layout'] == 'sidebar-no' ) {
    $bootstrap_col_class = 'col-sm-4 col-xs-6';
}
elseif ( $sidebars['layout'] == 'sidebar-double' ) {
    $bootstrap_col_class = 'col-sm-6 col-xs-12';
}
else {
    $bootstrap_col_class = 'col-sm-4 col-xs-6';
}

$tests = new WP_Query( $args );

if ( !$tests->have_posts() ) {
    return false;
}


?>
    <div class="testimonials row
">
        <?php
        $i   = 0;
        $row = 2;
        while ( $tests->have_posts() ) : $tests->the_post();

            if ( $text_type == 'excerpt' ) {
                $length = create_function( '', "return $limit;" );
                add_filter( 'excerpt_length', $length );
                $text = get_the_excerpt();
                remove_filter( 'excerpt_length', $length );
            }
            else {
                $text = get_the_content();
            }

            $title              = the_title( '<p class="name">', '</p>', false );
            $label              = yit_get_post_meta( get_the_ID(), '_yit_testimonial_social' );
            $siteurl            = yit_get_post_meta( get_the_ID(), '_yit_testimonial_website' );
            $small_quote        = yit_get_post_meta( get_the_ID(), '_yit_testimonial_small_quote' );
            $rating             = yit_get_post_meta( get_the_ID(), '_yit_testimonial_rating' );
            $role               = yit_get_post_meta( get_the_ID(), '_yit_testimonial_role' );
            $website            = '';
            $has_post_thumbnail = has_post_thumbnail( get_the_ID() );

            if ( $siteurl != '' ) :
                if ( $label != '' ) :
                    $website = '<span class="testimonialwebsite"> <a  href="' . esc_url( $siteurl ) . '">' . $label . '</a> </span>';
                else:
                    $website = '<span class="testimonialwebsite"> <a  href="' . esc_url( $siteurl ) . '">' . $siteurl . '</a> </span>';
                endif;
            else :
                $website = '';
            endif;?>

            <div class="testimonial-col <?php echo $bootstrap_col_class ?>">
                <div class="testimonial-wrapper">

                    <div class="testimonial-content">
                        <?php if ( $small_quote != '' ): ?>
                            <h4 class="testimonial-smallquote"><?php echo $small_quote ?></h4>
                        <?php endif ?>
                        <div class="testimonial-cit">
                            <div class="testimonial-text"><?php echo wpautop( $text ); ?></div>
                        </div>
                    </div>

                    <div class="row  <?php if ( $thumbnail == 'no' || !$has_post_thumbnail ) : ?>nothumb<?php endif ?>">
                        <?php  // Control if the thumbnail exists
                        if ( $thumbnail == 'yes' && $has_post_thumbnail ) :  ?>
                            <!-- We have the thumbnail in the span2 -->
                            <div class="thumb col-sm-6">
                                <div class="thumbnail">
                                    <?php echo get_the_post_thumbnail( null, 'testimonial-thumbnail' ); ?>
                                </div>
                            </div>
                        <?php endif ?>


                        <div class="testimonial-meta <?php echo (  $thumbnail == 'no' || !$has_post_thumbnail ) ? 'col-sm-12' : 'col-sm-6' ?>">
                            <?php if ( $rating != 0 ): ?>
                                <div class="testimonial-rating">
                                    <div class="star-rating"><span style="width:<?php echo ( ( (int)$rating / 5 ) * 100 ) ?>%"></span></div>
                                </div>
                                <div class="clearfix"></div>
                            <?php endif; ?>

                            <!-- testimonial's name -->
                            <div class="testimonial-name">
                                <?php echo $title ?>
                            </div>

                            <?php if ( $role != ''): ?>
                                <!-- testimonial's role -->
                                <div class="testimonial-role">
                                    <?php echo $role ?>
                                </div>
                            <?php endif ?>

                            <?php echo "<div class='text'>" . $website . "</div>"; ?>

                        </div>
                    </div>

                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php wp_reset_query() ?>