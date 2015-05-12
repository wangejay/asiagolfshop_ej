<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$opt_show_search = apply_filters('yit_header_show_search', yit_get_option( 'header-show-search' ));
$search_trigger = ( $opt_show_search == 'small' ) ? '<li class="search-trigger"><a href="#"></a></li>' : '';
?>

<!-- START NAVIGATION -->
<nav id="nav" role="navigation" class="nav header-nav">

    <?php
    include_once( YIT_THEME_ASSETS_PATH . '/lib/Walker_Nav_Menu_Div.php' );
    $nav_args = array(
        'theme_location' => 'nav',
        'container' => 'div',
        'container_class' => 'level-1 clearfix',
        'depth' => apply_filters( 'yit_main_nav_depth', 3 ),
        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s' . $search_trigger . '</ul>',
    );

    if ( has_nav_menu( 'nav' ) )
        $nav_args['walker'] = new YIT_Walker_Nav_Menu_Div();

    wp_nav_menu( $nav_args );

    ?>

</nav>
<!-- END NAVIGATION -->

<?php if ( has_nav_menu( 'mobile-nav' ) ) : ?>
	<!-- MOBILE MENU -->
	<div class="mobile-nav hidden">

		<?php
		$nav_args = array(
			'theme_location' => 'mobile-nav',
			'container' => 'none',
			'menu_class' => 'level-1 clearfix',
			'depth' => apply_filters( 'yit_main_nav_depth', 3 ),
		);

		wp_nav_menu( $nav_args );
		?>

	</div>
	<!-- END MOBILE MENU -->
<?php endif; ?>


<?php if ( is_active_sidebar( 'Mobile Sidebar' ) ) : ?>
    <!-- MOBILE SIDEBAR -->
    <div class="mobile-sidebar hidden">

        <?php
        // the sidebar for mobile
        dynamic_sidebar( 'Mobile Sidebar' );
        ?>

    </div>
    <!-- END MOBILE SIDEBAR -->
<?php endif; ?>
