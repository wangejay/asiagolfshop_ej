<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $woocommerce, $wp;

$my_account_url = get_permalink( wc_get_page_id( 'myaccount' ) );

?>


<div class="user-profile border-2 clearfix">

    <div class="user-image">
        <a href="<?php echo $my_account_url ?>" >
            <?php
                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;
                echo get_avatar( $user_id, 50 );
            ?>
        </a>
    </div>
    <div class="user-logout">
        <span class="username"><?php echo $current_user->display_name?></span>
        <?php if( isset( $current_user ) && $current_user->ID != 0 ) : ?>
            <span class="logout"><a href="<?php echo wp_logout_url( $my_account_url ); ?>"><?php _e( 'logout', 'yit' ) ?></a></span>
        <?php endif; ?>
    </div>

</div>

<div class="clearfix"></div>
<ul class="border">
    <li>
        <a href="<?php echo $my_account_url ?>" title="<?php _e( 'Dashboard', 'yit' ); ?>" <?php echo ( isset( $wp->query_vars['view-order'] ) || isset( $wp->query_vars['recent-downloads'] ) || isset( $wp->query_vars['myaccount-wishlist'] ) || isset( $wp->query_vars['edit-address'] ) || isset( $wp->query_vars['edit-account'] ) )  ? '' : ' class="active"'; ?> >
            <span data-icon="&#xe3e6;" data-font="retinaicon-font"></span><?php _e( 'Dashboard', 'yit' ); ?>
        </a>
    </li>
    <li>
        <a href="<?php echo wc_get_endpoint_url( 'view-order', '', $my_account_url ) ?>" title="<?php _e( 'My Orders', 'yit' ); ?>" <?php echo isset( $wp->query_vars['view-order'] )  ? ' class="active"' : ''; ?> >
            <span data-icon="&#xe443;" data-font="retinaicon-font"></span><?php _e( 'My Orders', 'yit' ); ?>
        </a>
    </li>
    <?php if( yit_get_option( 'my-account-download' ) !== 'no' ) : ?>
    <li>
        <a href="<?php echo wc_get_endpoint_url('recent-downloads', '',  $my_account_url ) ?>" title="<?php _e( 'My Download', 'yit' ); ?>"<?php echo isset( $wp->query_vars['recent-downloads'] ) ? ' class="active"' : ''; ?>>
            <span data-icon="&#xe3c7;" data-font="retinaicon-font"></span><?php _e( 'My Downloads', 'yit' ) ?>
        </a>
    </li>
    <?php endif; ?>
    <?php if( defined( 'YITH_WCWL' ) ) : ?>
    <li>
        <a href="<?php echo wc_get_endpoint_url( 'myaccount-wishlist', '',  $my_account_url ) ?>" title="<?php _e( 'My Wishlist', 'yit' ); ?>"<?php echo isset( $wp->query_vars['myaccount-wishlist'] ) ? ' class="active"' : ''; ?>>
            <span data-icon="&#xe3e9;" data-font="retinaicon-font"></span><?php _e( 'My Wishlist', 'yit' ) ?>
        </a>
    </li>
    <?php endif; ?>
    <li>
        <a href="<?php echo wc_get_endpoint_url('edit-address', '',  $my_account_url ) ?>" title="<?php _e( 'Edit Address', 'yit' ); ?>"<?php echo isset( $wp->query_vars['edit-address'] ) ? ' class="active"' : ''; ?>>
            <span data-icon="&#xe3f2;" data-font="retinaicon-font"></span><?php _e( 'Edit Address', 'yit' ) ?>
        </a>
    </li>
    <li>
        <a href="<?php echo wc_get_endpoint_url('edit-account', '',  $my_account_url ) ?>" title="<?php _e( 'Edit Account', 'yit' ); ?>"<?php echo isset( $wp->query_vars['edit-account'] ) ? ' class="active"' : ''; ?>>
            <span data-icon="&#xe3f2;" data-font="retinaicon-font"></span><?php _e( 'Edit Account', 'yit' ) ?>
        </a>
    </li>
</ul>

