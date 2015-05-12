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
global $post;

$attachments = get_posts( array(
    	'post_type' 	=> 'attachment',
    	'numberposts' 	=> -1,
    	'post_status' 	=> null,
    	'post_parent' 	=> get_the_ID(),
    	'post_mime_type'=> 'image',
    	'orderby'		=> 'menu_order',
    	'order'			=> 'ASC'
    )
);

$image_type = 'blog_' . $blog_type;

$image_size = YIT_Registry::get_instance()->image->get_size( $image_type );

if ( !empty( $attachments ) ):
?>

<div style="visibility: hidden;" class="thumbnail masterslider ms-skin-default" data-view="flow" data-width="<?php echo esc_attr( $image_size['width'] ) ?>" data-height="<?php echo esc_attr( $image_size['height'] ) ?>" data-postid="<?php the_ID() ?>" id="galleryslider-<?php the_ID() ?>">
   <?php foreach ( $attachments as $key => $attachment ) : ?>
        <div class="ms-slide">
            <?php yit_image( "id=$attachment->ID&size=$image_type&class=img-responsive" ); ?>
        </div>
    <?php endforeach; ?>
</div>
<?php endif ?>
