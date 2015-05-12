<?php
/*
* This file belongs to the YIT Framework.
*
* This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://www.gnu.org/licenses/gpl-3.0.txt
*/

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

// check if is mobile
$isMobile = YIT_Mobile()->isMobile();
$isPhone = $isMobile && ! YIT_Mobile()->isTablet();
$isTablet = YIT_Mobile()->isTablet();
$is_iPad = wp_is_mobile() && preg_match( '/iPad/', $_SERVER['HTTP_USER_AGENT'] );
$isLumia = preg_match( '/IEMobile/', $_SERVER['HTTP_USER_AGENT'] ) ? true : false;

if (apply_filters('yit-enable-topbar', yit_get_option('header-enable-topbar')) != 'yes' || yit_get_option('header-skin') == 'transparent') {
    return;
}
?>
<!-- START TOPBAR -->
<div id="topbar"
     class="<?php echo class_exists('YIT_Style_Picker') ? apply_filters('yit-stylepicker-topbar-class', '') : ''; ?>">
    <div class="container">
        <div class="clearfix header-wrapper">

            <?php if ( null !== ( yit_get_option('header-enable-topbar-left-mobile')) && yit_get_option('header-enable-topbar-left-mobile') == 'yes' && $isMobile ): ?>
                <div id="topbar-left">
                    <?php yit_get_template('/header/sidebar-topbar-left.php'); ?>
                </div>
            <?php elseif( !$isMobile ): ?>
                <div id="topbar-left">
                    <?php yit_get_template('/header/sidebar-topbar-left.php'); ?>
                </div>
            <?php endif ?>

            <?php if ( null !== ( yit_get_option('header-enable-topbar-right-mobile')) && yit_get_option('header-enable-topbar-right-mobile') == 'yes' && $isMobile ): ?>
                <div id="topbar-right">
                    <?php yit_get_template('/header/sidebar-topbar-right.php') ?>
                    <?php if (defined('ICL_SITEPRESS_VERSION')): ?>
                        <?php yit_get_template('/header/wpml.php'); ?>
                    <?php endif ?>
                </div>
            <?php elseif( !$isMobile ): ?>
                <div id="topbar-right">
                    <?php yit_get_template('/header/sidebar-topbar-right.php') ?>
                    <?php if (defined('ICL_SITEPRESS_VERSION')): ?>
                        <?php yit_get_template('/header/wpml.php'); ?>
                    <?php endif ?>
                </div>
            <?php endif ?>


        </div>
    </div>
</div>
<!-- END TOPBAR -->