<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Pre-Launch page - Regency Skin
 *
 * @author Your Inspiration Themes
 * @package YITH Pre-Launch
 * @version 1.0.0
 */

    $countdown_message      = get_option( 'yith_prelaunch_countdown_message' );
    $wrapper_background     = get_option( 'yith_prelaunch_wrapper_background_wrapper_color' );
    $wrapper_opacity        = ( get_option( 'yith_prelaunch_wrapper_background_wrapper_opacity' ) / 100 );
    $border['style-1']      = yit_get_option( 'color-website-border-style-1' );
    $border['style-2']      = yit_get_option( 'color-website-border-style-2' );
    $tooltip['box']         = get_option( 'yith_prelaunch_socials_tooltip_box_color' );
    $tooltip['text']        = get_option( 'yith_prelaunch_socials_tooltip_text_color' );
    $tooltip['prefix']      = apply_filters( 'yit_prelaunch_tooltip_prefix' ,__( "I'm on", 'yit' ) );
    $tooltip['prefix_mail'] = apply_filters( 'yit_prelaunch_tooltip_prefix_mail' ,__( "me", 'yit' ) );

    if( 'transparent' != $wrapper_background ){
        $rgba = YIT_Registry::get_instance()->colors->hex2rgb( $wrapper_background );
        $wrapper_background = "rgba( {$rgba[0]}, {$rgba[1]}, {$rgba[2]}, {$wrapper_opacity} );";
    }

    $background_role = array();
    if ( ! empty( $background['color'] ) )      $background_role[] = "background-color: {$background['color']};";
    if ( ! empty( $background['image'] ) )      $background_role[] = "background-image: url('{$background['image']}');";
    if ( ! empty( $background['repeat'] ) )     $background_role[] = "background-repeat: {$background['repeat']};";
    if ( ! empty( $background['position'] ) )   $background_role[] = "background-position: {$background['position']};";
    if ( ! empty( $background['attachment'] ) ) $background_role[] = "background-attachment: {$background['attachment']};";

    $social_to_awesome = array(
        'facebook'  => 'fa-facebook',
        'twitter'   => 'fa-twitter',
        'gplus'     => 'fa-google-plus',
        'youtube'   => 'fa-youtube',
        'rss'       => 'fa-rss',
        'behance'   => 'fa-behance',
        'dribble'   => 'fa-dribbble',
        'email'     => 'fa-envelope-o',
        'flickr'    => 'fa-flickr',
        'instagram' => 'fa-instagram',
        'linkedin'  => 'fa-linkedin',
        'pinterest' => 'fa-pinterest',
        'skype'     => 'fa-skype',
        'tumblr'    => 'fa-tumblr'
    );

?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 9]>
<html id="ie9" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if gt IE 9]>
<html class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if !IE]>
<html <?php language_attributes() ?>>
<![endif]-->

<!-- START HEAD -->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="Shortcut Icon" type="image/x-icon" href="<?php echo home_url(); ?>/favicon.ico" />

    <link rel="stylesheet" href="<?php echo YIT_THEME_ASSETS_URL . '/bootstrap/css/bootstrap.min.css' ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo YIT_CORE_ASSETS_URL . '/css/font-awesome.min.css' ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo yith_google_fonts_url() ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo YIT_THEME_ASSETS_URL ?>/css/prelaunch/prelaunch.css" type="text/css" />

    <style type="text/css">
        .border, #prelaunch .countdown .num:after, #prelaunch form.newsletter input.text-field {
            border-color: <?php echo $border['style-1']['color'] ?>
        }

        .border-2 {
            border-color: <?php echo $border['style-2']['color'] ?>
        }

        body.prelaunch {
            <?php echo implode( "\n", $background_role ) ?>
        }

        .prelaunch .fixed-background {
            background-color: <?php echo $wrapper_background ?>;
        }

        #prelaunch .logo .tagline {
            <?php echo $logo['tagline_font']; ?>
        }

        #prelaunch h1, h2, h3, h4, h5, h6, h1.title{
            <?php echo $title_font; ?>
        }

        #prelaunch p, li {
            <?php echo $p_font; ?>
        }

        #prelaunch .top-bg {
            background: <?php echo $color['border_top'] ?>;
        }

        #prelaunch form.newsletter input.text-field {
            <?php echo $newsletter['email_font']; ?>
        }

        #prelaunch form.newsletter input.submit-field {
            background: <?php echo $newsletter['submit']['color'] ?>;
            <?php echo $newsletter['submit']['font']; ?>
            border-color: <?php echo get_option( 'yith_prelaunch_newsletter_submit_border' )?>;
        }
        #prelaunch form.newsletter input.submit-field:hover {
            color: <?php echo get_option( ' yith_prelaunch_newsletter_submit_font_color_hover ' ) ?>;
            border-color: <?php echo get_option( 'yith_prelaunch_newsletter_submit_border_hover' ) ?>;
        }

        #prelaunch form.newsletter .submit:hover input.submit-field {
            background: <?php echo $newsletter['submit']['hover'] ?>;
        }

        #prelaunch form.newsletter .submit:after {
            border-right-color: <?php echo $newsletter['submit']['color'] ?>;
         }

        #prelaunch form.newsletter .submit:hover:after {
            border-right-color: <?php echo $newsletter['submit']['hover'] ?>;
        }

        #prelaunch .countdown .num {
            <?php echo $countdown['num_font'] ?>
        }

        #prelaunch .countdown .label {
            <?php echo $countdown['label_font'] ?>
        }

        #prelaunch .countdown_message{
            <?php echo yit_typo_option_to_css( get_option('yith_prelaunch_countdown_message_font') );?>
        }

        #prelaunch .socials .social{
            border-color: <?php echo get_option( 'yith_prelaunch_socials_icon_color' );?>
        }

        #prelaunch .socials .social:hover{
            border-color: <?php echo get_option( 'yith_prelaunch_socials_icon_color_hover' );?>
        }

        #prelaunch .socials .social .fa{
            color: <?php echo get_option( 'yith_prelaunch_socials_icon_color' );?>
        }

        #prelaunch .socials .social .fa:hover,
        #prelaunch .socials .social:hover .fa{
            color: <?php echo get_option( 'yith_prelaunch_socials_icon_color_hover' );?>
        }

        div.tooltip.bottom div.tooltip-arrow {
            border-bottom-color: <?php echo $tooltip['box'] ?>;
        }

        div.tooltip.top div.tooltip-arrow {
            border-top-color: <?php echo $tooltip['box'] ?>;
        }

        div.tooltip-inner {
            background-color: <?php echo $tooltip['box'] ?>;
        }

        div.tooltip-inner {
            color: <?php echo $tooltip['text'] ?>;
        }

    	<?php echo $custom ?>
    </style>
</head>
<!-- END HEAD -->
<!-- START BODY -->
<body <?php body_class( 'prelaunch' ) ?>>
    <div class="fixed-background container border"></div>
    <div class="fixed-bottom container border"></div>
    <div id="prelaunch" class="container">

        <a class="logo" href="<?php echo site_url() ?>">
            <img src="<?php echo esc_url( $logo['image'] ) ?>" class="img-responsive" alt="Logo" />
            <?php if ( ! empty( $logo['tagline'] ) ) : ?><p class="tagline"><?php echo $logo['tagline'] ?></p><?php endif; ?>
        </a>

        <div class="yit-box">
            <div class="top-bg"></div>

            <div class="message">
                <?php echo $message; ?>
            </div>

            <?php if ( $countdown['enabled'] ) : ?>
                <?php if( ! empty( $countdown_message ) ) : ?>
                    <div class="countdown_message">
                        <?php echo $countdown_message ?>
                    </div>
                <?php endif;?>

                <div class="countdown row">

                    <div class="col-xs-3 days">
                        <div class="information-container border">
                            <span class="num"><?php echo $countdown['days'] ?></span>
                            <span class="label"><?php _e( 'Days', 'yit' ) ?></span>
                        </div>
                    </div>

                    <div class="col-xs-3 hours">
                        <div class="information-container border">
                            <span class="num"><?php echo $countdown['hours'] ?></span>
                            <span class="label"><?php _e( 'Hours', 'yit' ) ?></span>
                        </div>
                    </div>

                    <div class="col-xs-3 minutes">
                        <div class="information-container border">
                            <span class="num"><?php echo $countdown['minutes'] ?></span>
                            <span class="label"><?php _e( 'Minutes', 'yit' ) ?></span>
                        </div>
                    </div>

                    <div class="col-xs-3 seconds">
                        <div class="information-container border">
                            <span class="num"><?php echo $countdown['seconds'] ?></span>
                            <span class="label"><?php _e( 'Seconds', 'yit' ) ?></span>
                        </div>
                    </div>

                    <div class="cleafix"></div>

                </div>
            <?php endif; ?>

            <?php if ( $newsletter['enabled'] ) : ?>

                <?php if ( $title ) : ?>
                    <h1 class="title"><?php echo $title ?></h1>
                <?php endif ?>

                <form method="<?php echo $newsletter['form_method'] ?>" action="<?php echo $newsletter['form_action'] ?>" class="newsletter">
                    <fieldset>
                        <input type="text" name="<?php echo $newsletter['email_name'] ?>" id="<?php echo $newsletter['email_name'] ?>" class="email-field text-field" placeholder="<?php echo $newsletter['email_label'] ?>" />
                        <div class="submit"><input type="submit" value="<?php echo $newsletter['submit']['label'] ?>" class="submit-field" /></div>
                        <?php foreach( $newsletter['hidden_fields'] as $field_name => $field_value ) : ?>
                            <input type="hidden" id="<?php echo $field_name ?>" name="<?php echo $field_name ?>" value="<?php echo $field_value ?>" />
                        <?php endforeach; ?>
                    </fieldset>
                </form>
            <?php endif; ?>

        </div>

        <div class="socials">
            <?php foreach( $socials as $social => $url ) :
                if ( empty( $url ) ) continue;

                if ( $social == 'email' ) $url = 'mailto:' . $url;
                if ( $social == 'skype' ) $url = 'http://myskype.info/' . str_replace( '@', '', $url );

                $social_name    = 'gplus' == $social ? 'google+' : $social;

                if( 'email' == $social ) {
                    $social_prefix  = $social;
                    $social_name    = $tooltip['prefix_mail'];
                }else {
                    $social_prefix = $tooltip['prefix'];
                }

            ?>
            <a class="social with-tooltip <?php echo $social ?>" href="<?php echo esc_url( $url ) ?>" target="_blank" title="<?php echo $social_prefix . ' ' .$social_name ?>">
                <i class="fa <?php echo $social_to_awesome[ $social ] ?>"></i>
            </a>
            <?php endforeach; ?>
        </div>

    </div>

	<?php wp_footer() ?>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            var countdown_html = $('.countdown').clone();
            $('.days .num', countdown_html).text('{dn}');
            $('.hours .num', countdown_html).text('{hnn}');
            $('.minutes .num', countdown_html).text('{mnn}');
            $('.seconds .num', countdown_html).text('{snn}');

            $('.countdown').countdown({until: <?php echo $countdown['to'] ?>, layout: countdown_html.html() });

            $( 'a.with-tooltip' ).each( function() {
                var $placement = ( $(this).data('placement') ) ? $(this).data('placement') : 'bottom';

                $(this).tooltip({
                    placement: $placement
                });
            });
        });
    </script>
</body>
</html>