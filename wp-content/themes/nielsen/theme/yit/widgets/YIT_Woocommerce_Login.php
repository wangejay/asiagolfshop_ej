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

if ( ! class_exists( 'YIT_Woocommerce_Login' ) && function_exists( 'WC' ) ) :

    /**
     * Yit Woocommerce Login Class
     *
     * @since 2.0.0
     */
    class YIT_Woocommerce_Login extends WP_Widget {

        function __construct() {
            $widget_ops  = array(
                'classname'   => 'yit-woocommerce-login',
                'description' => __( 'Display Login Menu in the header of the page. Note: the widget can be used only in the header.', 'yit' )
            );
            $control_ops = array( 'id_base' => 'yit-woocommerce-login' );
            $this->WP_Widget( 'yit-woocommerce-login', __( 'YIT Wooocommerce Login', 'yit' ), $widget_ops, $control_ops );
        }

        function widget( $args, $instance ) {

            $show_logged_out    = isset( $instance['show_logged_out'] ) ? $instance['show_logged_out'] : 'yes';
	        $show_logged_in     = isset( $instance['show_logged_in'] )  ? $instance['show_logged_in']  : 'yes';
            $title_logged_out   = isset( $instance['title_logged_out'] ) ? $instance['title_logged_out'] : '';
            $nav_menu           = isset( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : '';
            $nav_show_wishlist  = ( isset( $instance['nav_show_wishlist'] ) && $instance['nav_show_wishlist']=='yes' && defined( 'YITH_WCWL' ) ) ? true : false;
            $nav_show_wpml_menu = ( isset( $instance['nav_show_wpml_menu'] ) && $instance['nav_show_wpml_menu']=='yes' && defined( 'ICL_SITEPRESS_VERSION' ) ) ? true : false;

           // var_dump($instance);
            $logged_in = false;
            if ( is_user_logged_in() ) {
                $logged_in = true;

                global $current_user;
                get_currentuserinfo();

                $user_name = $current_user->display_name;
                if( empty( $user_name ) ) {
                    $user_name = __('user', 'yit');
                }


                ?>
                <!-- START LOGGED IN NAVIGATION -->
                <div id="welcome-menu" class="nav dropdown">
                    <ul>
                        <li class="menu-item<?php if ( $show_logged_in == 'yes' ) echo ' dropdown'; ?>">
	                        <a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ) ?>">
		                        <span class="welcome_username"><?php echo apply_filters( 'yit_welcome_login_label', __( 'Hi', 'yit' ) ) ?> <?php  echo apply_filters( 'yit_welcome_username', $user_name ) ?></span>
	                        </a>
	                        <?php

	                        if ( $show_logged_in == 'yes' ) {
		                        include_once( YIT_THEME_ASSETS_PATH . '/lib/Walker_Nav_Menu_Div.php' );
		                        $nav_args = array(
			                        'menu'            => $nav_menu,
			                        'container'       => 'div',
			                        'container_class' => 'submenu',
			                        'menu_class'      => 'sub-menu clearfix',
			                        'depth'           => 1,
			                        'walker'          => new YIT_Walker_Nav_Menu_Div()
		                        );

		                        wp_nav_menu( $nav_args );
	                        }
	                        ?>
                        </li>

                    </ul>

                </div>
                <!-- END LOGGED IN  NAVIGATION -->

            <?php
            }
            else {
                $enabled_registration = get_option( 'woocommerce_enable_myaccount_registration' );
                $enabled_registration_class = ( $enabled_registration === 'yes' ) ? 'with_registration' : '';

                $profile_link = is_shop_installed() ? get_permalink( wc_get_page_id( 'myaccount' ) ) : wp_login_url();
                ?>
                <div id="welcome-menu-login" class="nav">
                    <ul id="menu-welcome-login">
                        <li class="menu-item login-menu<?php if ( $show_logged_out == 'yes' ) echo ' dropdown'; ?>">
                            <a href="<?php echo esc_url( $profile_link ) ?>"><?php echo $title_logged_out ?></a>

	                        <?php if ( $show_logged_out == 'yes' ) { ?>
                            <div class="submenu clearfix">
                                <div class="clearfix login-box <?php echo esc_attr( $enabled_registration_class ) ?>">
                                    <div id="customer_login">
	                                    <div class="customer-login-box customer-login-box1">

                                            <form method="post" class="login">

                                                <?php do_action( 'woocommerce_login_form_start' ); ?>

                                                <div class="form-group">
                                                    <label for="username"><?php _e( 'Username or email address', 'yit' ); ?> <span class="required">*</span></label>
                                                    <input type="text" class="form-control" name="username" id="username" />
                                                </div>

                                                <div class="form-group">
                                                    <label for="password"><?php _e( 'Password', 'yit' ); ?> <span class="required">*</span></label>
                                                    <input class="form-control" type="password" name="password" id="password" />
                                                </div>

                                                <?php do_action( 'woocommerce_login_form' ); ?>

                                                <div class="form-group login-submit">
                                                    <?php wp_nonce_field( 'woocommerce-login' ); ?>
                                                    <input type="submit" class="button btn btn-flat-red button-login" name="login" value="<?php _e( 'Login', 'yit' ); ?>" />
                                                    <p class="lost_password">
	                                                    <?php if( function_exists('wc_lostpassword_url') ): ?>
		                                                    <a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e( 'Lost password?', 'yit' ); ?></a><br />
	                                                    <?php endif ?>
	                                                    <?php if( $enabled_registration === 'yes' ): ?>
		                                                    <?php _e( 'New Customer ?', 'yit' ) ?> <a class="signup" href="<?php echo esc_url( is_shop_installed() ? get_permalink( wc_get_page_id( 'myaccount' ) ) : wp_registration_url() ); ?>"><?php _e( 'Sign up', 'yit' ); ?></a>
	                                                    <?php endif ?>
                                                    </p>
                                                   <!-- <label for="rememberme" class="inline">
                                                        <input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'yit' ); ?>
                                                    </label> -->
                                                </div>

                                                <?php do_action( 'woocommerce_login_form_end' ); ?>

                                            </form>
	                                    </div>
                                    </div>
                                </div>

                            </div>
		                    <?php } ?>

                        </li>
                    </ul>
                </div>
            <?php
            }
            if( $nav_show_wishlist ): ?>
                <div class="nav whislist_nav">
                    <ul>
                        <li>
                            <a href="<?php echo wc_get_endpoint_url('myaccount-wishlist', '',  get_permalink( wc_get_page_id( 'myaccount' ) ) ) ?>"><?php  _e('My Wishlist', 'yit') ?></a>
                        </li>
                    </ul>
                </div>
            <?php endif;
        }

        function form( $instance ) {

            $defaults = array(
                'show_logged_out'    => 'yes',
                'title_logged_out'   => __( 'Login/Register', 'yit' ),
                'show_logged_in'     => 'yes',
                'nav_menu'           => '',
                'nav_show_wishlist'  => 'yes'
            );

            $instance = wp_parse_args( (array) $instance, $defaults );
            $menus    = wp_get_nav_menus( array( 'orderby' => 'name' ) );

            ?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_logged_out' ) ); ?>"><?php _e( 'Show Logged Out Menu', 'yit' ) ?>:
                    <select id="<?php echo esc_attr( $this->get_field_id( 'show_logged_out' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_logged_out' ) ); ?>">
                        <option value="yes" <?php selected( $instance['show_logged_out'], 'yes' ) ?>><?php _e( 'Yes', 'yit' ) ?></option>
                        <option value="no" <?php selected( $instance['show_logged_out'], 'no' ) ?>><?php _e( 'No', 'yit' ) ?></option>
                    </select>
                </label>
            </p>


            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title_logged_out' ) ); ?>"><?php _e( 'Title Logged Out', 'yit' ) ?>:
                    <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title_logged_out' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_logged_out' ) ); ?>" value="<?php echo esc_attr( $instance['title_logged_out'] ); ?>" class="widefat" />
                </label>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_logged_in' ) ); ?>"><?php _e( 'Show Logged In Menu', 'yit' ) ?>:
                    <select id="<?php echo esc_attr( $this->get_field_id( 'show_logged_in' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_logged_in' ) ); ?>">
                        <option value="yes" <?php selected( $instance['show_logged_in'], 'yes' ) ?>><?php _e( 'Yes', 'yit' ) ?></option>
                        <option value="no" <?php selected( $instance['show_logged_in'], 'no' ) ?>><?php _e( 'No', 'yit' ) ?></option>
                    </select>
                </label>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>"><?php _e( 'Select Logged In Menu:', 'yit' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'nav_menu' ) ); ?>">
                    <?php
                    foreach ( $menus as $menu ) : ?>
                        <option value="<?php echo esc_attr( $menu->term_id ) ?>" <?php selected( $instance['nav_menu'], $menu->term_id ); ?>><?php echo $menu->name ?></option>
                    <?php endforeach; ?>
                </select>
            </p>


            <?php  if (  defined( 'YITH_WCWL' ) ) : ?>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'nav_show_wishlist' ) ); ?>"><?php _e( 'Show Wishlist Link:', 'yit' ); ?></label>
                    <select id="<?php echo esc_attr( $this->get_field_id( 'nav_show_wishlist' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'nav_show_wishlist' ) ); ?>">
                        <option value="yes" <?php selected( $instance['nav_show_wishlist'], 'yes' ) ?>><?php _e( 'Yes', 'yit' ) ?></option>
                        <option value="no" <?php selected( $instance['nav_show_wishlist'], 'no' ) ?>><?php _e( 'No', 'yit' ) ?></option>
                    </select>
                </p>
            <?php endif ?>




        <?php

        }

        function update( $new_instance, $old_instance ) {
            $instance                        = $old_instance;
            $instance['title_logged_out']    = strip_tags( $new_instance['title_logged_out'] );
            $instance['show_logged_out']     = $new_instance['show_logged_out'];
            $instance['show_logged_in']      = $new_instance['show_logged_in'];
            $instance['nav_menu']            = $new_instance['nav_menu'];
            $instance['nav_show_wishlist']   = $new_instance['nav_show_wishlist'];
            $instance['nav_show_wpml_menu']  = $new_instance['nav_show_wpml_menu'];

            return $instance;
        }


    }

endif;
