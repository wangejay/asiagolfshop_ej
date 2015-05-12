<?php
/**
 * Login Form
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       2.2.6
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

    <div class="col2-set clearfix" id="customer_login">

    <div class="row">

    <div class="col-1 col-sm-6">

<?php endif; ?>

    <form method="post" class="login border">

        <?php if ( shortcode_exists( 'box_title' ) ) {
            echo do_shortcode( "[box_title class='form-login-title' font_size='18' border_color='#f2f2f2' font_alignment='center' border='none']" . __( 'I already have an account here', 'yit' ) . "[/box_title]" );
        }
        else {
            echo "<h2>" . __( 'I already have an account here', 'yit' ) . "</h2>";
        }?>

        <?php do_action( 'woocommerce_login_form_start' ); ?>

        <p class="form-row form-row-wide">
            <label for="username"><?php _e( 'Username or email address', 'yit' ); ?>
                <span class="required">*</span></label>
            <input type="text" class="input-text" name="username" id="username" value="<?php if ( !empty( $_POST['username'] ) ) {
                echo esc_attr( $_POST['username'] );
            } ?>" />
        </p>

        <p class="form-row form-row-wide">
            <label for="password"><?php _e( 'Password', 'yit' ); ?> <span class="required">*</span></label>
            <input class="input-text" type="password" name="password" id="password" />
        </p>

        <?php do_action( 'woocommerce_login_form' ); ?>

        <p class="form-row">
            <?php wp_nonce_field( 'woocommerce-login' ); ?>
            <input type="submit" class="button btn btn-flat-orange" name="login" value="<?php _e( 'Login', 'yit' ); ?>" />
            <label for="rememberme" class="inline">
                <input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'yit' ); ?>
            </label>
        </p>

        <p class="lost_password">
            <a href="<?php echo esc_url( wc_lostpassword_url() ); ?>" class="a-style-2"><?php _e( 'Lost your password?', 'yit' ); ?></a>
        </p>



        <?php do_action( 'woocommerce_login_form_end' ); ?>

    </form>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

    </div>

    <div class="col-2 col-sm-6">

        <form method="post" class="register">

            <?php if ( shortcode_exists( 'box_title' ) ) {
                echo do_shortcode( "[box_title class='form-register-title' font_size='18' border_color='#f2f2f2' font_alignment='center' border='middle']" . __( 'I\'m new here!', 'yit' ) . "[/box_title]" );
            }
            else {
                echo "<h2>" . __( 'I\'m new here!', 'yit' ) . "</h2>";
            }?>

            <?php do_action( 'woocommerce_register_form_start' ); ?>

            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                <p class="form-row form-row-wide">
                    <label for="reg_username"><?php _e( 'Username', 'yit' ); ?> <span class="required">*</span></label>
                    <input type="text" class="input-text" name="username" id="reg_username" value="<?php if ( !empty( $_POST['username'] ) ) {
                        echo esc_attr( $_POST['username'] );
                    } ?>" />
                </p>

            <?php endif; ?>

            <p class="form-row form-row-wide">
                <label for="reg_email"><?php _e( 'Email address', 'yit' ); ?> <span class="required">*</span></label>
                <input type="email" class="input-text" name="email" id="reg_email" value="<?php if ( !empty( $_POST['email'] ) ) {
                    echo esc_attr( $_POST['email'] );
                } ?>" />
            </p>

            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                <p class="form-row form-row-wide">
                    <label for="reg_password"><?php _e( 'Password', 'yit' ); ?> <span class="required">*</span></label>
                    <input type="password" class="input-text" name="password" id="reg_password" />
                </p>

            <?php endif; ?>

            <!-- Spam Trap -->
            <div style="<?php echo( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;">
                <label for="trap"><?php _e( 'Anti-spam', 'yit' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" />
            </div>

            <?php do_action( 'woocommerce_register_form' ); ?>

            <p class="form-row">
                <?php wp_nonce_field( 'woocommerce-register' ); ?>
                <input type="submit" class="button btn btn-flat-orange" name="register" value="<?php _e( 'Register', 'yit' ); ?>" />
            </p>

            <?php do_action( 'woocommerce_register_form_end' ); ?>

        </form>

    </div>

    </div>

    </div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>