<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $wp_query;

do_action( 'yit_page_meta' );

$is_blog = ( ( is_page_template( 'blog.php' ) || is_home() || is_archive() || is_search() || is_category() || is_tag() ) && get_post_type( get_the_ID() ) != 'forum') ? true : false;
$blog_type = yit_get_option( 'blog-type' );

$blog_type_options = array(
    'blog_type'     => $blog_type,
    'date_style'    => ( 'style-1' == yit_get_option( 'blog-date-style' ) ? 'normal border-2' : 'alternative' )
);


if ( is_page_template( 'blog.php' ) ) {
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    query_posts( 'cat=' . yit_get_excluded_categories() . '&posts_per_page=' . yit_get_option( 'posts_per_page' ) . '&paged=' . $paged );
}

if ( have_posts() ) :

    while ( have_posts() ) : the_post();

        if ( $is_blog ) {
            yit_get_template( 'primary/loop/blog.php', $blog_type_options );
        }
        elseif ( is_singular( 'post' ) ) {
            yit_get_template( 'primary/loop/single.php', $blog_type_options );
        }
        elseif ( is_page() ) {
            the_content();
        }
        else {

            ob_start();
            do_action( 'yit_loop' );
            $yit_loop = ob_get_clean();

            if ( ! empty( $yit_loop ) ) {
                echo $yit_loop;
            }
            else {
                the_content();
            }
        }

        wp_link_pages();

    endwhile;
else:
    ?>
    <div id="post-0" class="<?php if ( $is_blog && $blog_type != "big" ) echo 'col-sm-12'; ?> post error404 not-found group">
        <h1 class="entry-title"><?php _e( 'Not Found', 'yit' ); ?></h1>

        <div class="entry-content">
            <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'yit' ); ?></p>
            <?php get_search_form(); ?>
        </div>
    </div>
<?php
endif;

$has_pagination = ( $wp_query->max_num_pages > 1 ) ? true : false;

if ( function_exists( 'yit_pagination' ) && $has_pagination ) {
    yit_pagination();
}

comments_template();

wp_reset_query();