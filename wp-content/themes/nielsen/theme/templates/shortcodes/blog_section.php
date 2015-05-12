<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

global $post;

$enable_thumbnails = ($enable_thumbnails == 'yes') ? true : false;
$enable_title = ($enable_title == 'yes') ? true : false;
$enable_date = ($enable_date == 'yes') ? true : false;
$enable_author = ($enable_author == 'yes' && get_the_author() != false) ? true : false;
$enable_comments = ($enable_comments == 'yes') ? true : false;
$enable_slider = (isset($enable_slider)) ? $enable_slider : 'yes';
$date_style = ( isset($date_style) && $date_style == 'yes') ? 'alternative' : false;
$args = array(
    'post_type' => 'post',
    'posts_per_page' => $nitems
);

if ( isset( $cat_name ) && ! empty( $cat_name ) && $cat_name != 'null' && $cat_name != "a:0:{}" ) {
    $args['category_name'] = $cat_name;
}

$animate_data   = ( $animate != '' ) ? 'data-animate="' . $animate . '"' : '';
$animate_data  .= ( $animation_delay != '' ) ? ' data-delay="' . $animation_delay . '"' : '';
$animate        = ( $animate != '' ) ? ' yit_animate' : '';

$blog = new WP_Query( $args );

if ( $blog->have_posts() ) :

    wp_enqueue_script('owl-carousel');

    ?>
    <div class="blog-slider-outer clearfix  <?php echo esc_attr( $animate ) ?>" <?php echo $animate_data ?>>
        <div class="blog-slider">
            <div class="prev-blog">
                <div class="icon-square">
                    <i class="fa fa-angle-left"></i>
                </div>
            </div>
            <div class="next-blog">
                <div class="icon-square">
                    <i class="fa fa-angle-right"></i>
                </div>
            </div>
            <div class="row">
                <ul class="blogs_posts" data-slider="<?php echo esc_attr( $enable_slider ) ?>"
                    data-postid="<?php echo 'blog_section_' . mt_rand(); ?>">
                    <?php while ($blog->have_posts()) : $blog->the_post() ?>
                        <?php $thumbnails_class = has_post_thumbnail() ? 'thumbnails blog_section' : 'no-thumbnails blog_section'; ?>
                        <li class="blog_post col-xs-12">
                            <div class="<?php echo esc_attr( $thumbnails_class ) ?>">
                                <?php if ($enable_thumbnails) :
                                    if (has_post_thumbnail()) : ?>
                                        <a class="title_link" href="<?php the_permalink() ?>">
                                            <?php yit_image('size=blog_section&class=img-responsive'); ?>
                                        </a>
                                    <?php endif;
                                endif; ?>
                            </div>
                            <div class="blog_section post_meta">
                                <?php if ($enable_date) : ?>
                                    <div class="yit_post_meta_date <?php echo esc_attr( $date_style ) ?>">
                                        <span class="day">
                                            <?php echo get_the_date('d') ?>
                                        </span>
                                        <span class="month">
                                            <?php echo get_the_date('M') ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <div class="post_informations">
                                    <?php if ($enable_title) : ?>
                                        <h3 class="title">
                                            <a class="title_link" href="<?php the_permalink() ?>"><?php the_title() ?></a>
                                        </h3>
                                        <p class="excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>

                                    <?php endif; ?>
                                    <span class="info">
                                <?php if ($enable_author) : ?>
                                    <?php echo __('by', 'yit') . ' ';
                                    the_author_posts_link();
                                    echo ' // '; ?>
                                <?php endif; ?>
                                        <?php if ($enable_comments) : ?>
                                            <a href="<?php comments_link() ?>"><?php comments_number('0 ' . __('Comment', 'yit'), '1 ' . __('Comment', 'yit'), '% ' . __('Comments', 'yit')); ?></a>
                                        <?php endif; ?>
                            </span>
                                </div>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php wp_reset_query(); ?>