<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! function_exists( 'yit_get_meta_tags' ) ) {
    /**
     * Retrieve current page keywords and description and return them.
     *
     * @return string
     * @since 1.0.0
     */
    function yit_get_meta_tags() {
        global $post;

        ob_start() ?>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <?php if ( yit_get_option( 'responsive-enabled' ) ) : ?>
            <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php endif ?>


        <?php return ob_get_clean();
    }
}



if( !function_exists( 'yit_get_favicon' ) ) {
    /**
     * Retrieve the URL of the favicon.
     *
     * @return string
     * @since 1.0.0
     */
    function yit_get_favicon() {
        $url = yit_get_option( 'general-favicon' );

        if( empty( $url ) )
        { $url = get_template_directory_uri() . '/favicon.ico'; }

        if( is_ssl() )
        { $url = str_replace( 'http://', 'https://', $url ); }

        return $url;
    }
}