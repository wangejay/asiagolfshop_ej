<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
?>

<div class="searchform">
    <form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
        <div>
            <label class="screen-reader-text" for="s"><?php _e( 'Search for', 'yit' ) ?></label>
            <div class="search-wrapper"><input  type="text" value="" name="s" id="s" placeholder="<?php _e( 'Enter keywords here ...', 'yit' ) ?>"  /></div>
            <input  type="submit" class="button" id="searchsubmit" value="<?php _e( 'Search', 'yit' ) ?>" />
            <?php
            $post_types =  apply_filters( 'yit_searchform_post_types', array( 'post' ) );

            foreach( $post_types as $post_type ) : ?>
                <input type="hidden" name="post_type[]" value="<?php echo esc_attr( $post_type ) ?>" />
            <?php endforeach ?>
        </div>
    </form>
</div>