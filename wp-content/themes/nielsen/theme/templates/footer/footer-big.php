<?php 
/**
 * Your Inspiration Themes
 * 
 * In this files there is a collection of a functions useful for the core
 * of the framework.   
 * 
 * @package WordPress
 * @subpackage Your Inspiration Themes
 * @author Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
$footer_rows    = yit_get_option( 'footer-rows' );
$footer_columns = yit_get_option( 'footer-columns' );
$f_extra_a = yit_get_option('footer-add-extra-area');

if ( $f_extra_a == 'yes' ) {
    $f_extra_width        = yit_get_option( 'footer-extra-area-width' );
    $f_extra_width_al     = ( 12 - $f_extra_width ) ? (12 - $f_extra_width) : 12;
    $f_extra_position     = yit_get_option( 'footer-extra-area-position' );
    $f_extra_class        = ( $f_extra_position == 'right' ) ? 'col-sm-' . $f_extra_width . ' col-md-push-' . $f_extra_width_al : 'col-sm-' . $f_extra_width ;
    $f_main_sidebar_class = ( $f_extra_position == 'right' ) ? 'col-sm-' . $f_extra_width_al . ' col-md-pull-' . $f_extra_width : 'col-sm-' . $f_extra_width_al ;
}

?>
<!-- START FOOTER -->
<div class="clear"></div>
<div id="footer">
    <div class="container">
        <div class="border">
            <div class="row">
                <?php if ( $f_extra_a == 'yes' ) : ?>
                    <div class="<?php echo esc_attr( $f_extra_class ) ?>"><div class="row"><?php dynamic_sidebar( 'Footer Extra Area' ) ?></div></div>

                    <div class="<?php echo esc_attr( $f_main_sidebar_class ) ?>">
                        <div class="row">
                <?php endif; ?>

                <?php for( $i = 1; $i <= $footer_rows; $i++ ) : ?>
                    <?php do_action( 'yit_before_footer_row_' . $i ) ?>
                    <div class="footer-row-<?php echo $i ?> footer-columns-<?php echo esc_attr( $footer_columns ) ?>">
                        <?php dynamic_sidebar( 'Footer Row ' . $i ) ?>
                    </div>
                    <div class="clear"></div>
                    <?php do_action( 'yit_after_footer_row_' . $i ) ?>
                <?php endfor ?>

                <?php if ( $f_extra_a == 'yes' ) : ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- END FOOTER -->