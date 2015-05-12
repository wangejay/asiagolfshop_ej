<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Template file for print share buttons
 *
 * @package Yithemes
 * @author Francesco Licandro <francesco.licandro@yithemes.com> ,Andrea Frascaspata <andrea.frascaspata@yithemes.com>, Emanuela Castorina <emanuela.castorina@yithemes.com>
 * @since 1.0.0
 */

/* Social list */


$socials_accepted = array( 'facebook', 'twitter', 'google', 'pinterest');

if( is_serialized( $socials ) ){
    $socials = unserialize( $socials );
    $socials = empty( $socials ) ? $socials_accepted : $socials;
}else{
    $socials = empty( $socials ) ? $socials_accepted : array_map( 'trim', explode( ',', $socials ) );
}

$class_container ="";


        if( !empty( $class ) ) {
            $class = ' ' . $class;
        }

        echo "<div class='share-container'>";



                if ( ! empty( $title ) ) echo '<span class="share-text">' . $title . '</span>';   // title

                echo "<div class='socials-container ". esc_attr( $class_container ) ."'>";

                echo '<div class="socials' . esc_attr( $class ) . '">';   // begin list of socials


                foreach ( $socials as $i => $social ) {
                    if ( in_array( $social, $socials_accepted ) ) {

                        $url = $script = $attrs = '';

                        global $post;

                        $title = urlencode( get_the_title() );
                        $permalink = urlencode( get_permalink() );
                        $excerpt = urlencode( get_the_excerpt() );

                        if ( $social == 'facebook' ) {
                            $url = apply_filters( 'yiw_share_facebook', 'https://www.facebook.com/sharer.php?u=' . $permalink . '&t=' . $title . '' );

                        } else if ( $social == 'twitter' ) {
                            $url = apply_filters( 'yiw_share_twitter', 'https://twitter.com/share?url=' . $permalink . '&text=' . $title . '' );

                        } else if ( $social == 'google' ) {
                            $social="google-plus";//fix after social.php use awesome icons
                            $url = apply_filters( 'yiw_share_google', 'https://plus.google.com/share?url=' . $permalink . '&title=' . $title . '' );
                            $attrs = " onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;\"";

                        } else if ( $social == 'pinterest' ) {
                            $src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                            $url = apply_filters( 'yiw_share_pinterest', 'http://pinterest.com/pin/create/button/?url=' . $permalink . '&media=' . $src[0] . '&description=' . $excerpt );
                            $attrs = ' onclick="window.open(this.href); return false;';

                        }

                        if ($size=="small"){
                            $icon_size="18";
                            $circle_size="35";
                        }
                        else {
                            $icon_size="34";
                            $circle_size="55";
                        }

                        echo do_shortcode( '[social icon_type="'.$icon_type.'"  icon_social="' . $social . '" href="' . $url . '"' . $attrs . ' target="_blank" size="' . $size . '" icon_size="'.$icon_size.'" circle="yes" circle_size="'.$circle_size.'" circle_border_size="1" title="'.$social.'"]' );
                        echo $script;
                    }
                }

                echo '</div>';   // end list of socials

                echo '</div>';

        echo '</div>';
