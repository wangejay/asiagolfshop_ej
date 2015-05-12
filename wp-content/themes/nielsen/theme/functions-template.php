<?php
/**
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

require_once YIT_THEME_PATH . '/functions/string_manipulation.php';
require_once YIT_THEME_PATH . '/functions/head.php';

/*
 * =HOOKED FUNCTIONS=
 *
 * All the following functions are hooked via add_action or add_filter
 */
if ( ! function_exists( 'yit_change_container_width' ) ) {
    function yit_change_container_width() {
        $width = apply_filters( 'yit-container-width-std', 1170 );

        if ( 1170 == $width ) {
            return;
        }
        ?>
        <style type="text/css">
                /*
                 * Override Bootstrap's default container.
                 */

            @media (min-width: 1200px) {
                .container {
                    width: <?php echo $width ?>px;
                }
            }

            <?php if ( $width < 992 ) : ?>
            @media (min-width: 992px) {
                .container {
                    width: <?php echo $width ?>px;
                }
            }

            <?php endif; ?>
        </style>
    <?php
    }
}

if( ! function_exists( 'yit_slider_layout_values' ) ){
    /**
     * Unset unused slider layout
     *
     * @param $layouts
     *
     * @return string[]
     * @since  2.0.0
     * @author Antonio La Rocca <antonio.larocca@yithems.com>
     */
    function yit_slider_layout_values( $layouts ){
        unset( $layouts['default'] );

        return $layouts;
    }
}

if( ! function_exists( 'init_slider_single_layouts' ) ){
    /**
     * Add slider single layout setup on after setup theme
     *
     * @since  2.0.0
     * @author Antonio La Rocca <antonio.larocca@yithems.com>
     */
    function init_slider_layouts(){
        if ( function_exists( 'YIT_Slider' ) ) {
            add_filter( 'yit_cptu_' . YIT_Slider()->sliders_post_type . '_layout_values', 'yit_slider_layout_values' );
        }
    }
}

/**
 * HEAD
 */
if ( ! function_exists( 'yit_favicon' ) ) {
    function yit_favicon() {
        yit_get_template( '/head/favicon.php' );
    }
}

if ( ! function_exists( 'yit_body_background' ) ) {
    /**
     * Define the body background for the page.
     *
     * First get the setting for the current page. If a setting is not defined
     * in the current page, will be get the setting from the theme options.
     * All css will be shown in head tag, by the action 'wp_head'
     *
     * @since 1.0.0
     */
    function yit_body_background() {
        $color            = YIT_Layout()->body_bg_color;
        $image            = YIT_Layout()->body_bg_image;
        $image_repeat     = YIT_Layout()->body_bg_repeat;
        $image_position   = YIT_Layout()->body_bg_position;
        $image_attachment = YIT_Layout()->body_bg_attachment;
        $wrapper_color    = YIT_Layout()->wrapper_bg_color;

        if ( 'default' == $image_repeat ) {
            $image_repeat = '';
        }
        if ( 'default' == $image_position ) {
            $image_position = '';
        }
        if ( 'default' == $image_attachment ) {
            $image_attachment = '';
        }

        // get from theme options
        $background = yit_get_option( 'background-style' );

        if ( empty( $image ) && empty( $color ) ) {
            $image = $background['image'];
            if ( $image == 'custom' ) {
                $image = yit_get_option( 'background-custom-image' );
            }

        }

        $wrapper = yit_get_option( 'container-background-color' );

        if ( empty( $color ) ) {
            $color = $background['color'];
        }

        if ( empty( $image_repeat ) ) {
            $image_repeat = yit_get_option( 'background-repeat' );
        }
        if ( empty( $image_position ) ) {
            $image_position = yit_get_option( 'background-position' );
        }
        if ( empty( $image_attachment ) ) {
            $image_attachment = yit_get_option( 'background-attachment' );
        }

        if ( empty( $wrapper_color ) ) {
            $wrapper_color = $wrapper['color'];
        }

        $css = array();
        $css_wrapper = array();

        if ( ! empty( $color ) ) {
            $css[] = "background-color: $color;";
        }
        if ( ! empty( $image ) && ( $image != 'none' || $image !='' ) ) {
            $css[] = "background-image: url('$image');";
        }

        if ( ! empty( $image ) && ! empty( $image_repeat ) ) {
            $css[] = "background-repeat: $image_repeat;";
        }
        if ( ! empty( $image ) && ! empty( $image_position ) ) {
            $css[] = "background-position: $image_position;";
        }
        if ( ! empty( $image ) && ! empty( $image_attachment ) ) {
            $css[] = "background-attachment: $image_attachment;";
        }
        if ( ! empty( $wrapper_color ) && yit_get_option( 'general-layout-type' ) == 'boxed' ){
            $css_wrapper[] = "background-color: $wrapper_color;";
        }

        if ( empty( $css ) && empty( $css_wrapper ) ) {
            return;
        }

        $css = apply_filters( 'yit_layout_body_background', $css );
        ?>
        <style type="text/css">
            <?php if( ! empty( $css ) ): ?>
            body, .st-content, .st-content-inner {
            <?php echo implode( ' ', $css ) ?>
            }
            <?php endif;?>
            <?php if( ! empty( $css_wrapper ) ): ?>

            .boxed-layout #wrapper{
            <?php echo implode( ' ', $css_wrapper )?>
            }
            <?php endif;?>
        </style>
    <?php
    }
}

/**
 * HEADER
 */
if ( ! function_exists( 'yit_start_wrapper' ) ) {
    function yit_start_wrapper() {
        yit_get_template( '/header/start-wrapper.php' );
        global $is_primary;
        $is_primary = false;
    }
}

if ( ! function_exists( 'yit_end_wrapper' ) ) {
    function yit_end_wrapper() {
        yit_get_template( '/footer/end-wrapper.php' );
    }
}

if ( ! function_exists( 'yit_start_header' ) ) {
    function yit_start_header() {
        yit_get_template( '/header/start-header.php' );
    }
}

if ( ! function_exists( 'yit_end_header' ) ) {
    function yit_end_header() {
        yit_get_template( '/header/end-header.php' );
    }
}

if( ! function_exists( 'yit_map' ) ) {
    function yit_map() {
        yit_get_template( '/header/map.php' );
    }
}

if ( ! function_exists( 'yit_topbar' ) ) {
    function yit_topbar() {
        yit_get_template( '/header/topbar.php' );
    }
}

if ( ! function_exists( 'yit_header' ) ) {
    function yit_header() {
        yit_get_template( '/header/header.php' );
    }
}

if ( ! function_exists( 'yit_logo' ) ) {
    function yit_logo() {
        yit_get_template( '/header/logo.php' );
    }
}

if ( ! function_exists( 'yit_header_sidebar' ) ) {
	function yit_header_sidebar() {
		yit_get_template( '/header/sidebar-header.php' );
	}
}

if ( ! function_exists( 'yit_header_search' ) ) {
	function yit_header_search() {
		yit_get_template( '/header/header-search.php' );
	}
}

if ( ! function_exists( 'yit_nav' ) ) {
    function yit_nav() {
        yit_get_template( '/header/navigation.php' );
    }
}

if ( ! function_exists( 'yit_shop_by_category' ) ) {
    function yit_shop_by_category() {
        yit_get_template( '/header/shop-by-category.php' );
    }
}

if ( ! function_exists( 'yit_shop_by_category_nav' ) ) {
    function yit_shop_by_category_nav() {
        yit_get_template( '/header/shop-by-category-nav.php' );
    }
}

if ( ! function_exists( 'yit_shop_by_category_nav_wrapper_start' ) ) {
    function yit_shop_by_category_nav_wrapper_start() {
        ?><div class="shop-by-category container nav vertical"><?php
    }
}

if ( ! function_exists( 'yit_shop_by_category_nav_wrapper_end' ) ) {
    function yit_shop_by_category_nav_wrapper_end() {
        ?></div><?php
    }
}

if ( ! function_exists( 'yit_slider_header' ) ) {
    function yit_slider_header() {
        yit_get_template( '/header/slider.php' );
    }
}


if ( ! function_exists( 'yit_slogan' ) ) {
    function yit_slogan() {
        yit_get_template( '/header/slogan.php' );
    }
}

/**
 * PRIMARY
 */
if ( ! function_exists( 'yit_start_primary' ) ) {
    function yit_start_primary() {
        yit_get_template( '/primary/start-primary.php' );
        global $is_primary;
        $is_primary = true;
    }
}

if ( ! function_exists( 'yit_end_primary' ) ) {
    function yit_end_primary() {
        yit_get_template( '/primary/end-primary.php' );
        global $is_primary;
        $is_primary = false;
    }
}

if ( ! function_exists( 'yit_primary_content' ) ) {
    function yit_primary_content() {
        yit_get_template( '/primary/content.php' );
    }
}

if ( ! function_exists( 'yit_primary_sidebar' ) ) {
    function yit_primary_sidebar() {
        yit_get_template( '/primary/sidebar.php' );
    }
}

if ( ! function_exists( 'yit_primary_sidebar_two' ) ) {
    function yit_primary_sidebar_two() {
        yit_get_template( '/primary/sidebar-two.php' );
    }
}

if ( ! function_exists( 'yit_content_loop' ) ) {
    function yit_content_loop() {
        yit_get_template( '/primary/loop/loop.php' );
    }
}


/**
 * FOOTER
 */

if ( ! function_exists( 'yit_footer' ) ) {
    function yit_footer() {
        yit_get_template( '/footer/footer.php' );
    }
}

if ( ! function_exists( 'yit_footer_big' ) ) {
    function yit_footer_big() {
        yit_get_template( '/footer/footer-big.php' );
    }
}

if ( ! function_exists( 'yit_copyright' ) ) {
    function yit_copyright() {
        yit_get_template( '/footer/copyright.php' );
    }
}


/*
 * =NON HOOKED FUNCTIONS=
 */

if ( ! function_exists( 'yit_sidebar_args' ) ) {
    /**
     * Create the standard set of arguments for creating new sidebar
     *
     * @param string $name         The main name of sidebar
     * @param string $description  (optional) Description of sidebar
     * @param string $widget_class (optional) The widget class
     * @param string $title        (optional) The tag to use for the titles
     *
     * @return array The set of arguments for creating the sidebar
     *
     * @since 1.0.0
     */
    function yit_sidebar_args( $name, $description = '', $widget_class = 'widget', $title = 'h3' ) {
        $id = strtolower( str_replace( ' ', '-', $name ) );

        return array(
            'name'          => $name,
            'id'            => $id,
            'description'   => $description,
            'before_widget' => '<div id="%1$s" class="' . $widget_class . ' %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<' . $title . '>',
            'after_title'   => '</' . $title . '>',
        );
    }
}


if ( ! function_exists( 'is_posts_page' ) ) {
    /**
     * Check if the user is in the page setted in Settings -> Reading as "Blog page"
     *
     * @return bool
     * @since 1.0.0
     */
    function is_posts_page() {
        global $wp_query;
        return $wp_query->is_posts_page;
    }
}

if ( ! function_exists( 'yit_get_post_meta' ) ) {
    /**
     * Retrieve the value of a metabox.
     *
     * This function retrieve the value of a metabox attached to a post. It return either a single value or an array.
     *
     * @param int    $id      Post ID.
     * @param string $meta    The meta key to retrieve.
     *
     * @return mixed Single value or array
     * @since    1.0.0
     */
    function yit_get_post_meta( $id, $meta ) {
        if ( ! strpos( $meta, '[' ) ) {
            return get_post_meta( $id, $meta, true );
        }

        $sub_meta = explode( '[', $meta );

        $meta = get_post_meta( $id, $meta, true );
        for ( $i = 0; $i < count( $sub_meta ); $i ++ ) {
            $meta = $meta[rtrim( $sub_meta[$i], ']' )];
        }

        return $meta;
    }
}

if ( ! function_exists( 'yit_ssl_url' ) ) {
    /**
     * Force the URL to https://, if we are in SSL
     *
     * @since 1.0.0
     */
    function yit_ssl_url( $url ) {
        if ( is_ssl() ) {
            $url = str_replace( 'http://', 'https://', $url );
        }

        return $url;
    }
}

if ( ! function_exists( 'yit_button_style' ) ) {
    /**
     * Add button style to shortcode button.
     *
     * @param $button
     *
     * @return array
     *
     * @since 1.0.0
     */
    function yit_button_style( $button = array() ) {

        $button['ghost']       = __( 'Ghost', 'yit' );
        $button['flat-red']    = __( 'Flat Red', 'yit' );
        $button['flat-orange'] = __( 'Flat Orange', 'yit' );

        return $button;
    }
}

if ( ! function_exists( 'yit_edit_post_link' ) ) {
    /**
     * Add the edit post link
     *
     * @return void
     *
     * @since 1.1.0
     */
    function yit_edit_post_link( $link = '', $before = '', $after = '', $id = '' ) {

        $link   = empty( $link ) ? __( 'Edit', 'yit' ) : $link;
        $before = empty( $before ) ? '<span class="yit-edit-post">' : $before;
        $after  = empty( $after ) ? '</span>' : $after;
        $id     = empty( $id ) ? 0 : $link;

        edit_post_link( $link, $before, $after, $id );
    }
}

if ( ! function_exists( 'yit_get_testimonial_categories' ) ) {
    /**
     * Return an array with all testimonial categories
     *
     *
     * @return array
     * @since  1.0
     *
     */

    function yit_get_testimonial_categories() {

        global $wpdb;

        $terms           = $wpdb->get_results( 'SELECT name, ' . $wpdb->prefix . 'terms.term_id FROM ' . $wpdb->prefix . 'terms, ' . $wpdb->prefix . 'term_taxonomy WHERE ' . $wpdb->prefix . 'terms.term_id = ' . $wpdb->prefix . 'term_taxonomy.term_id AND taxonomy = "category-testimonial" ORDER BY name ASC;' );
        $categories      = array();
        $categories['0'] = __( 'All categories', 'yit' );
        if ( $terms ) :
            foreach ( $terms as $cat ) :
                $categories[$cat->term_id] = ( $cat->name ) ? $cat->name : 'ID: ' . $cat->term_id;
            endforeach;
        endif;

        return $categories;
    }
}

if( ! function_exists( 'yit_get_portfolios' ) ) {
    /**
     * Return an array of portfolios
     *
     * @param array $array
     *
     * @return array
     * @since  1.0
     * @author Antonio La Rocca <antonio.larocca@yithemes.com>
     */

    function yit_get_portfolios( $array = array() ) {
        $posts = get_posts( array(
            'post_type' => YIT_Portfolio()->portfolios_post_type
        ) );

        foreach( $posts as $post ){
            $array[ $post->post_name ] = $post->post_title;
        }
        return $array;
    }
}

if( ! function_exists( 'yit_get_contact_forms' ) ) {
    /**
     * Return an array of contact forms
     *
     * @param array $array
     *
     * @return array
     * @since  1.0
     * @author Francesco Licandro <francesco.licandro@yithemes.com>
     */

    function yit_get_contact_forms( $array = array() ) {
        if( ! function_exists( 'YIT_Contact_Form' ) ){
           return array();
        }

        $posts = get_posts( array(
            'post_type' => YIT_Contact_Form()->contact_form_post_type
        ) );

        foreach( $posts as $post ){
            $array[ $post->post_name ] = $post->post_title;
        }
        return $array;
    }
}

if ( ! function_exists( 'yit_add_field_to_testimonial_meta' ) ) {
    /**
     * Add field to metabox testimonial
     *
     */
    function yit_add_field_to_testimonial_meta() {

        $args = array(
            'yit_testimonial_rating' => array(
                'label' => __( 'Rating Star', 'yit' ),
                'desc'  => __( 'Insert the rating', 'yit' ),
                'type'  => 'number',
                'min'   => '1',
                'max'   => '5',
                'std'   => '0'
            )
        );

        YIT_Metabox( 'yit-testimonial-info' )->add_field( 'settings', $args, 'last' );
    }
}

if ( ! function_exists( 'yit_add_field_to_layout' ) ) {
    /**
     * Add field to layout panel
     *
     */
    function yit_add_field_to_layout() {


        $args = array(
	        'slogan_text_color'       => array(
		        'label' => __( 'Slogan color', 'yit' ),
		        'desc'  => __( 'Select a color for the slogan', 'yit' ),
		        'type'  => 'colorpicker',
		        'std'   => '#ffffff',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        ),
	        'slogan_text_bg_color'       => array(
		        'label' => __( 'Slogan background color', 'yit' ),
		        'desc'  => __( 'Select a background color for the text of slogan', 'yit' ),
		        'type'  => 'colorpicker',
		        'std'   => '#040404',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        ),
	        'slogan_highlight_color'       => array(
		        'label' => __( 'Slogan highlight text color', 'yit' ),
		        'desc'  => __( 'Select the color of the text for the hightlight of the slogan that you can get writing [text]', 'yit' ),
		        'type'  => 'colorpicker',
		        'std'   => '#ffffff',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        ),
	        'slogan_highlight_bg_color'       => array(
		        'label' => __( 'Slogan highlight text color', 'yit' ),
		        'desc'  => __( 'Select the color of the background for the hightlight of the slogan that you can get writing [text]', 'yit' ),
		        'type'  => 'colorpicker',
		        'std'   => '#c11200',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        ),

	        'subslogan_color'       => array(
		        'label' => __( 'Subslogan color', 'yit' ),
		        'desc'  => __( 'Select a color for the sublogan', 'yit' ),
		        'type'  => 'colorpicker',
		        'std'   => '#ffffff',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        ),
	        'slogan_image_background'      => array(
		        'label' => __( 'Enable slogan background', 'yit' ),
		        'desc'  => __( 'Set YES if you want to customize the background of slogan', 'yit' ),
		        'type'  => 'onoff',
		        'std'   => 'no',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        ),
	        'slogan_image_height'         => array(
		        'label' => __( 'Slogan height', 'yit' ),
		        'desc'  =>__( 'Set 0 for auto height', 'yit' ),
		        'type'  => 'number',
		        'std'   => '0',
		        'min'   => '0',
		        'max'   => '1000',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        ),
	        'slogan_bg_color'       => array(
		        'label' => __( 'Slogan background color', 'yit' ),
		        'desc'  => __( 'Select a background color for the slogan', 'yit' ),
		        'type'  => 'colorpicker',
		        'std'   => '#ffffff',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        ),
	        'slogan_bg_image'       => array(
		        'label' => __( 'Slogan background image', 'yit' ),
		        'desc'  => __( 'Select a background image for the slogan.', 'yit' ),
		        'type'  => 'upload',
		        'std'   => '',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        ),
	        'slogan_bg_repeat'                 => array(
		        'label'   => __( 'Background repeat', 'yit' ),
		        'desc'    => __( 'Select the repeat mode for the background image.', 'yit' ),
		        'type'    => 'select',
		        'options' => array(
			        'default'   => __( 'Default', 'yit' ),
			        'repeat'    => __( 'Repeat', 'yit' ),
			        'repeat-x'  => __( 'Repeat Horizontally', 'yit' ),
			        'repeat-y'  => __( 'Repeat Vertically', 'yit' ),
			        'no-repeat' => __( 'No Repeat', 'yit' ),
		        ),
		        'std'     => 'default',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        ),
	        'slogan_bg_position'               => array(
		        'label'   => __( 'Background position', 'yit' ),
		        'desc'    => __( 'Select the position for the background image.', 'yit' ),
		        'type'    => 'select',
		        'options' => array(
			        'default'       => __( 'Default', 'yit' ),
			        'center'        => __( 'Center', 'yit' ),
			        'top left'      => __( 'Top left', 'yit' ),
			        'top center'    => __( 'Top center', 'yit' ),
			        'top right'     => __( 'Top right', 'yit' ),
			        'bottom left'   => __( 'Bottom left', 'yit' ),
			        'bottom center' => __( 'Bottom center', 'yit' ),
			        'bottom right'  => __( 'Bottom right', 'yit' ),
		        ),
		        'std'     => 'default',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        ),
	        'slogan_bg_attachment' => array(
		        'label'   => __( 'Background attachment', 'yit' ),
		        'desc'    => __( 'Select the attachment for the background image.', 'yit' ),
		        'type'    => 'select',
		        'options' => array(
			        'default' => __( 'Default', 'yit' ),
			        'scroll'  => __( 'Scroll', 'yit' ),
			        'fixed'   => __( 'Fixed', 'yit' ),
		        ),
		        'std'     => 'default',
		        'deps'  => array(
			        'ids'    => '_show_slogan',
			        'values' => 'yes'
		        )
	        )
        );

        YIT_Layout_Panel() -> add_option( 'setting', $args, $where = 'after');
        YIT_Layout_Panel() -> remove_panel( 'google_map' );

    }
}


if ( ! function_exists( 'yit_header_background' ) ) {
    /**
     * Define the body background for the page.
     *
     * First get the setting for the current page. If a setting is not defined
     * in the current page, will be get the setting from the theme options.
     * All css will be shown in head tag, by the action 'wp_head'
     *
     * @since 1.0.0
     */
    function yit_header_background() {

        $custom_header = YIT_Layout()->custom_background;

        if ( $custom_header == 'no' ) {
            return;
        }

        $css        = array();
        $color      = YIT_Layout()->header_bg_color;
        $image      = YIT_Layout()->header_bg_image;
        $repeat     = YIT_Layout()->header_bg_repeat;
        $position   = YIT_Layout()->header_bg_position;
        $attachment = YIT_Layout()->header_bg_attachament;

        if ( ! empty( $color ) ) {
            $css[] = 'background-color: ' . $color . ';';
        }

        if ( ! empty( $image ) ) {
            $css[] = "background-image: url('$image');";

            if ( ! empty( $repeat ) ) {
                $css[] = "background-repeat: $repeat;";
            }

            if ( ! empty( $position ) ) {
                $css[] = "background-position: $position;";
            }

            if ( ! empty( $attachment ) ) {
                $css[] = "background-attachment: $attachment;";
            }
        }

        if ( empty( $css ) ) {
            return;
        }


        $custom_css = '#header{' . implode( ' ', $css ) . '}'; ?>

        <style type="text/css">
            <?php echo $custom_css ?>
        </style>
    <?php
    }
}

if ( ! function_exists( 'yit_slogan_background' ) ) {
    /**
     * Define the slogan background for the page.
     *
     * First get the setting for the current page. If a setting is not defined
     * in the current page, will be get the setting from the theme options.
     * All css will be shown in head tag, by the action 'wp_head'
     *
     * @since 1.0.0
     */
    function yit_slogan_background() {
	    $show_slogan = YIT_Layout()->show_slogan;

        $slogan_custom_background     = YIT_Layout()->slogan_image_background;

        if ( $slogan_custom_background == false ) {
            return;
        }

        $css        = array();

	    // background
        $height     = YIT_Layout()->slogan_image_height;
        $color      = YIT_Layout()->slogan_bg_color;
        $image      = YIT_Layout()->slogan_bg_image;
        $repeat     = YIT_Layout()->slogan_bg_repeat;
        $position   = YIT_Layout()->slogan_bg_position;
        $attachment = YIT_Layout()->slogan_bg_attachment;

	    // text colors
	    $slogan_color               = YIT_Layout()->slogan_text_color;
	    $slogan_bg_color            = YIT_Layout()->slogan_text_bg_color;
	    $slogan_highlight_color     = YIT_Layout()->slogan_highlight_color;
	    $slogan_highlight_bg_color  = YIT_Layout()->slogan_highlight_bg_color;

	    // text colors subslogan
	    $subslogan_color            = YIT_Layout()->subslogan_color;

	    if ( ( empty( $show_slogan ) || $show_slogan == 'no' ) && function_exists( 'WC' ) ) {

		    if ( is_cart() || is_checkout() || is_order_received_page() ) {
			    $show_slogan         = YIT_Layout()->checkout_show_slogan;
			    $slogan_color        = YIT_Layout()->checkout_text_color;
                $slogan_bg_color     = ''; //remove main background
                $slogan_background_color = YIT_Layout()->checkout_text_background_color;
			    $slogan_active_color = YIT_Layout()->checkout_active_text_color;
                $slogan_active_background_color = YIT_Layout()->checkout_active_text_background_color;
			    $image               = YIT_Layout()->checkout_background_image;
			    $slogan_text_style   = array();

			    $slogan_text_style['cart']           = is_cart() ? "color: {$slogan_active_color}; background-color: {$slogan_active_background_color};" : "color: {$slogan_color}; background-color: {$slogan_background_color};";
			    $slogan_text_style['checkout']       = ( is_checkout() && ! is_order_received_page() ) ? "color: {$slogan_active_color}; background-color: {$slogan_active_background_color};" : "color: {$slogan_color}; background-color: {$slogan_background_color};";
			    $slogan_text_style['order_complete'] = is_order_received_page() ? "color: {$slogan_active_color}; background-color: {$slogan_active_background_color};" : "color: {$slogan_color}; background-color: {$slogan_background_color};";

			    $css['#slogan.yit-cart-checkout-slogan .slogan-checkout:before'][] = 'color: ' . $slogan_color . ';';
			    $css['#slogan.yit-cart-checkout-slogan .slogan-complete:before'][] = 'color: ' . $slogan_color . ';';
			    $css['#slogan.yit-cart-checkout-slogan .slogan-cart'][] = $slogan_text_style['cart'];
			    $css['#slogan.yit-cart-checkout-slogan .slogan-checkout'][] = $slogan_text_style['checkout'];
			    $css['#slogan.yit-cart-checkout-slogan .slogan-complete'][] = $slogan_text_style['order_complete'];

		    }
		    elseif( is_account_page() ) {
			    $show_slogan                = YIT_Layout()->myaccount_show_slogan;
			    $slogan_color               = YIT_Layout()->myaccount_text_color;
                $slogan_bg_color            = YIT_Layout()->myaccount_text_background;
                $slogan_highlight_bg_color  = YIT_Layout()->myaccount_highlight_background;
			    $image                      = YIT_Layout()->myaccount_background_image;

		    }
	    }

	    if ( empty( $show_slogan ) || $show_slogan == 'no' ) {
		    return;
	    }

	    // ***** CSS *****

        if ( ! empty( $height ) ) {
            $css['#slogan .slogan-wrapper'][] = 'height: ' . $height . 'px;';
        }

        if ( ! empty( $color ) ) {
            $css['#slogan .slogan-wrapper'][] = 'background-color: ' . $color . ';';
        }

        if ( ! empty( $image ) ) {

            $css['#slogan .slogan-wrapper'][] = "background-image: url('$image');";

            if ( ! empty( $repeat ) ) {
                $css['#slogan .slogan-wrapper'][] = "background-repeat: $repeat;";
            }

            if ( ! empty( $position ) ) {
                $css['#slogan .slogan-wrapper'][] = "background-position: $position;";
            }

            if ( ! empty( $attachment ) ) {
                $css['#slogan .slogan-wrapper'][] = "background-attachment: $attachment;";
            }
        }


	    /* slogan text */
	    if ( ! empty( $slogan_color ) ) {
		    $css['#slogan h1 span, #slogan h2 span'][] = 'color:' . $slogan_color . ';';
	    }

	    if ( ! empty( $slogan_bg_color ) ) {
		    $css['#slogan h1 span, #slogan h2 span'][] = 'background-color:' . $slogan_bg_color . ';';
	    }

	    if ( ! empty( $slogan_highlight_color ) ) {
		    $css['#slogan h1 span.title-highlight, #slogan h2 span.title-highlight'][] = 'color:' . $slogan_highlight_color . ';';
	    }

	    if ( ! empty( $slogan_highlight_bg_color ) ) {
		    $css['#slogan h1 span.title-highlight, #slogan h2 span.title-highlight'][] = 'background-color:' . $slogan_highlight_bg_color . ';';
	    }

	    if ( ! empty( $subslogan_color ) ) {
		    $css['#slogan p'][] = 'color:' . $subslogan_color . ';';
	    }

        if ( empty( $css ) ) {
            return;
        }
		?>

        <style type="text/css">
            <?php foreach ( $css as $rule => $atts ) printf( '%s{%s}', $rule, implode( '', $atts ) ); ?>
        </style>
    <?php
    }
}

if( ! function_exists( 'yit_list_comments' ) ){
    /*
     * Comments Template Callback
     *
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     * @param
     * @since 2.0.0
     */

    function yit_list_comments( $comment, $args, $depth ) {

        global $post;

        $comments_list_template = 'comments/list.php';

        $args = array(
            'reply_text' => __( 'Reply', 'yit' ),
            'depth'       => 1,
            'max_depth'  => get_option( 'thread_comments_depth' )
        );

        $is_author = $comment->user_id == $post->post_author ? true : false;

        $user = get_userdata( $comment->user_id );
        $comment_author_gravatar_mail = ( is_object( $user ) ) ? $user->data->user_email : $comment->comment_author_email;

        $avatar_class = $is_author ? 'avatar is_author' : 'avatar';

        $param = array(
            'is_author'                     =>  $is_author,
            'user'                          =>  $user,
            'comment_author_gravatar_mail'  =>  $comment_author_gravatar_mail,
            'avatar_class'                  =>  $avatar_class,
            'comment'                       =>  $comment,
            'args'                          =>  $args
        );

        yit_get_template( $comments_list_template, $param );
    }
}

if ( !function_exists( 'yit_back_to_top' ) ) {
    /**
     * Add a back to top button
     *
     * @since 1.0.0
     */

    function yit_back_to_top() {
        if ( yit_get_option('general-show-back-to-top') == 'yes' ) {
            echo '<div id="back-top"><a href="#top"><i class="fa fa-chevron-up"></i>' . __('Back to top', 'yit') . '</a></div>';
        }
    }
}

if( !function_exists( 'yit_404' ) ) {

    /**
     * Get 404 template
     *
     * @since 2.0.0
     */


    function yit_404() {
        yit_get_template( '404/404.php' );
    }
}

/* === MISC */
if( !function_exists( 'yit_searchform' ) ) {

    /**
     * Get SearchForm template
     *
     * @since 2.0.0
     */
    function yit_searchform( $post_type ) {
        yit_get_template( '/searchform/' . $post_type . '.php' );
    }
}

if ( ! function_exists( 'yit_get_social_share' ) ) {

    /**
     * Get social share
     *
     * @param \Select|string $type Select the type of share to show
     *
     * @param string         $class
     *
     * @param string         $socials
     * @param bool           $show_text
     * @param bool           $show_icon
     *
     * @return mixed String!Array
     * @since  2.0.0
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     */
    function yit_get_social_share( $type = 'text', $class='', $socials = '', $show_text = false, $show_icon = true ) {

        global $post;

        $share_on = __( 'Share on', 'yit' );
        $share_by = __( 'Share by', 'yit' );

        echo '<ul class="socials ' . $type . ' ' . $class . '">';

         $default_socials = array(
            'facebook' 		    => $share_on . ' '. 'facebook',
            'twitter'		    => $share_on . ' '. 'twitter',
            'google-plus'	    => $share_on . ' '. 'google+',
            'pinterest'		    => $share_on . ' '. 'pinterest',
            'envelope-o'		=> $share_by . ' '. 'mail',
        );

        if( empty( $socials ) ) {
            $socials = $default_socials;
        } else {
            $new_socials_array = array();
            foreach( $socials as $k => $v ){
                is_numeric( $k ) ? $new_socials_array[ $v ] = $default_socials[ $v ] : $new_socials_array[ $k ] = $socials[ $k ] ;
            }

            $socials = $new_socials_array;
        }

        foreach ( $socials as $social_icon => $social_name ) {

            $title      = urlencode( get_the_title() );
            $permalink  = urlencode( get_permalink() );
            $excerpt    = urlencode( get_the_excerpt() );
            $attrs      = '';

            switch( $social_icon ){
                case 'facebook':
                    $url    = apply_filters( 'yiw_share_facebook', 'https://www.facebook.com/sharer.php?u=' . $permalink . '&t=' . $title . '' );
                    $attrs  = " onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;\"";
                    break;

                case 'twitter':
                    $url    = apply_filters( 'yiw_share_twitter', 'https://twitter.com/share?url=' . $permalink . '&text=' . $title . '' );
                    $attrs  = " onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=417,width=600');return false;\"";
                    break;

                case 'google-plus':
                    $url   = apply_filters( 'yiw_share_google', 'https://plus.google.com/share?url=' . $permalink . '&title=' . $title . '' );
                    $attrs = " onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;\"";
                    break;

                case 'pinterest':
                    $src   = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
                    $url   = apply_filters( 'yiw_share_pinterest', 'http://pinterest.com/pin/create/button/?url=' . $permalink . '&media=' . $src[0] . '&description=' . $excerpt );
                    $attrs = " onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;\"";
                    break;

                case 'envelope-o':
                    $subject = urlencode( apply_filters( 'yit_share_mail_subject', __('I wanted you to see this site', 'yit') ));
                    $url = apply_filters( 'yit_share_mail', 'mailto:?subject='.$subject.'&amp;body= ' . $permalink . '&amp;title=' . $title . '' );
                    break;
            } ?>

            <li>
                <a href='<?php echo esc_url( $url ); ?>' title="<?php echo $social_name ?>" class="color-theme-share social-<?php echo $type ?> with-tooltip <?php echo $social_icon ?>" target="_blank" <?php echo $attrs ?>>
                    <?php if( $show_text ) : ?>
                        <?php _e( 'Share on ', 'yit' ); ?>
                        <?php echo $social_name; ?>
                    <?php endif; ?>
                    <?php if( $show_icon ) : ?>
                        <i class="fa fa-<?php echo $social_icon ?>"></i>
                    <?php endif; ?>
                </a>
            </li>

            <?php
        }
        echo '</ul>';
    }
}

if( ! function_exists( 'my_comments_open' ) ) {

    /**
     * Remove Comments, Pingaback and Trackback template on Pages
     *
     * @param string $open the pingback, trackback or comments status
     *
     * @param string $post_id the post ID
     *
     * @return bool
     * @since  2.0.0
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     */
    function my_comments_open( $open, $post_id ) {
        $remove_options = yit_get_option('general-disable-comment-on-pages-admin') == 'yes' ? true : false;
        $post = get_post( $post_id );

        return ( $post->post_type != 'post' && ( $remove_options || $post->post_type == 'forum' || $post->post_type == 'topic' ) ) ? false : $open;
    }
}


if( ! function_exists('yit_page_meta') ){
    function yit_page_meta(){

	    if ( function_exists('YIT_Layout') && YIT_Layout()->show_title == '1' ){
            echo '<h1>'. apply_filters( 'yit_page_title', yit_page_title() ) .'</h1>';
        }

        if ( function_exists('YIT_Layout') && ( YIT_Layout()->show_breadcrumb == '1' ) ) : ?>
            <div class="breadcrumbs">
                <?php yit_breadcrumb( apply_filters( 'yit_breadcrumb_delimiter', ' / '  ) ); ?>
            </div>
        <?php endif;
    }
}
add_action('yit_page_meta','yit_page_meta');


if( ! function_exists('yit_page_title') ){

    /**
     * Return the title of the page
     *
     * @return bool
     * @since  2.0.0
     * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
     */

    function yit_page_title(){

        // Homepage and Single Page
        if ( is_home() || is_single() || is_404() ) {
            return '';
        }

        // Search Page
        if ( is_search() ) {
            return sprintf( __( 'Search Results for: %s', 'yit' ), get_search_query() );
        }

        // Archive Pages
        if ( is_archive() ) {
            if ( is_author() ) {
                return sprintf( __( 'All posts by %s', 'yit' ), get_the_author() );
            }
            elseif ( is_day() ) {
                return sprintf( __( 'Daily Archives: %s', 'yit' ), get_the_date() );
            }
            elseif ( is_month() ) {
                return sprintf( __( 'Monthly Archives: %s', 'yit' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'yit' ) ) );
            }
            elseif ( is_year() ) {
                return sprintf( __( 'Yearly Archives: %s', 'yit' ), get_the_date( _x( 'Y', 'yearly archives date format', 'yit' ) ) );
            }
            elseif ( is_tag() ) {
                return sprintf( __( 'Tag Archives: %s', 'yit' ), single_tag_title( '', false ) );
            }
            elseif ( is_category() ) {
                return sprintf( __( 'Category Archives: %s', 'yit' ), single_cat_title( '', false ) );
            }
            elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
                return __( 'Asides', 'yit' );
            }
            elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
                return __( 'Videos', 'yit' );
            }
            elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
                return __( 'Audio', 'yit' );
            }
            elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
                return __( 'Quotes', 'yit' );
            }
            elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
                return __( 'Galleries', 'yit' );
            }
            else {
                return __( 'Archives', 'yit' );
            }
        }

        return get_the_title();
    }
}


if( !function_exists( 'yit_breadcrumb' ) ) {
    /**
     * Print the breadcrumb.
     *
     * @param string $sep
     * @return string
     * @since 1.0.0
     */
    function yit_breadcrumb( $delimiter = '&raquo;' ) {
        global $wp_query;
        $post = $wp_query->get_queried_object();

        $home = apply_filters( 'yit_homepage_breadcrumb_text', __( 'Home Page', 'yit' ) ); // text for the 'Home' link
        $before = '<a class="no-link current" href="#">'; // tag before the current crumb
        $after = '</a>'; // tag after the current crumb

        echo '<p id="yit-breadcrumb" itemprop="breadcrumb">';

        $homeLink = apply_filters('yit_breadcrumb_homelink', YIT_SITE_URL);

        if( !is_home() && !is_front_page() )
            echo '<a class="home" href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

        if ( is_category() ) {
            $cat_obj = $post;
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ( $thisCat->parent != 0 )
                echo get_category_parents( $parentCat, true, ' ' . $delimiter . ' ' );

            echo $before . sprintf( __( 'Archive by category "%s"', 'yit' ), single_cat_title( '', false ) ) . $after;
        } elseif ( is_day() ) {
            echo '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time( 'd' ) . $after;
        } elseif ( is_month() ) {
            echo '<a href="' . get_year_link( get_the_time( 'Y'  )) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time( 'F' ) . $after;
        } elseif ( is_year() ) {
            echo $before . get_the_time( 'Y' ) . $after;
        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object( get_post_type() );
                $slug = $post_type->rewrite;

                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
                echo $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];

                if( !empty( $cat ) ) {
                    echo get_category_parents( $cat, true, ' ' . $delimiter . ' ' );
                }

                echo $before . get_the_title() . $after;
            }
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() & !is_home()  ) {

            $post_type = get_post_type_object( get_post_type() );

            echo $before . $post_type->labels->singular_name . $after;
        } elseif ( is_attachment() ) {
            $parent = get_post( $post->post_parent );

            if( $parent->post_type == 'page' || $parent->post_type == 'post' ) {
                $cat = get_the_category( $parent->ID ); $cat = $cat[0];
                echo get_category_parents( $cat, true, ' ' . $delimiter . ' ' );
            }

            echo '<a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif ( is_page() && !$post->post_parent ) {
            echo $before . ucfirst( strtolower( get_the_title() ) ) . $after;
        } elseif ( is_page() && $post->post_parent ) {

            $parent_id  = $post->post_parent;
            $breadcrumbs = array();

            while ( $parent_id ) {
                $page = get_page( $parent_id );
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title( $page->ID ) . '</a>';
                $parent_id  = $page->post_parent;
            }

            $breadcrumbs = array_reverse( $breadcrumbs );
            foreach ( $breadcrumbs as $crumb )
            { echo $crumb . ' ' . $delimiter . ' '; }

            echo $before . yit_remove_chars_title(get_the_title()) . $after;
        } elseif ( is_search() ) {
            echo $before . sprintf( __( 'Search results for "%s"', 'yit' ), get_search_query() ) . $after;
        } elseif ( is_tag() ) {
            echo $before . sprintf( __( 'Posts tagged "%s"', 'yit' ), single_tag_title( '', false ) ) . $after;
        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);

            echo $before . sprintf( __( 'Articles posted by %s', 'yit' ), $userdata->display_name ) . $after;
        } elseif ( is_404() ) {
            echo $before . __( 'Error 404', 'yit' ) . $after;
        } elseif( is_home() ) {

            echo $before . apply_filters( 'yit_posts_page_breadcrumb', __( 'Home', 'yit' ) ) . $after;
        }

        if ( get_query_var('paged') ) {


            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
            { echo ' ('; }

            echo ' - '.$before . __( 'Page', 'yit' ) . ' ' . get_query_var( 'paged' ) . $after;

            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
            { echo ')'; }
        }

        echo '</p>';
    }
}

if (! function_exists("yit_hex2rgb")){
    /*
    * print single item of contact info
    *
    * @return void
    * @since 2.0
    * @author Andrea Frascaspata <andrea.frascaspata@yithemes.com>
    */
    function yit_hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);

        return implode(",", $rgb); // returns the rgb values separated by commas

    }
}
/*
 * print single item of contact info
 *
 * @return void
 * @since 1.0
 * @author Andrea Frascaspata <andrea.frascaspata@yithemes.com>
 */
if ( ! function_exists( "yit_get_contact_info_item_custom" ) ){


    function yit_get_contact_info_item_custom( $name_icon, $text, $value, $email_link = '' ){
        if ( isset( $value ) && $value != '' ){
            $has_email_link = ! empty( $email_link ) ? true : false;
            $container_class = $has_email_link ? 'icon-container background-image email' : 'icon-container background-image';
            ?>
            <li>
                <?php if ( isset( $name_icon ) && ( $name_icon != '' && $name_icon != 'None' ) ) :  ?>
                    <div class="<?php echo esc_attr( $container_class ) ?>" style="background-image:url(<?php echo $name_icon ?>)"></div>
                <?php endif; ?>
                <div class="info-container">
                    <?php if( $has_email_link ) : ?>
                        <a href="<?php echo esc_url( 'mailto:' . $email_link ) ?>" class="contact_info_email">
                    <?php endif; ?>
                        <h4><?php echo $text ?> </h4>
                        <p><?php echo $value ?></p>
                    <?php if( $has_email_link ) : ?>
                        </a>
                    <?php endif; ?>
                    </a>
                </div>
            </li>
        <?php
        }
    }
}

if( ! function_exists('yit_testimonial_section_shortcode') ){

    /*
    * override testimonials shortcode options
    *
    * @return void
    * @since 2.0
    * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
    */

    function yit_testimonial_section_shortcode(){
        return array(
            'testimonial'        => array(
                'title'       => __( 'Testimonials', 'yit' ),
                'description' => __( 'Show all post on testimonials post types', 'yit' ),
                'tab'         => 'cpt',
                'has_content' => false,
                'in_visual_composer' => true,
                'create'      => false,
                'attributes'  => array(
                    'items' => array(
                        'title'       => __( 'N. of items', 'yit' ),
                        'description' => __( 'Show all with -1', 'yit' ),
                        'type'        => 'number',
                        'std'         => '-1'
                    ),
                    'cat'   => array(
                        'title'       => __( 'Categories', 'yit' ),
                        'description' => __( 'Select the categories of posts to show', 'yit' ),
                        'type'        => 'select',
                        'options'     => yit_get_testimonial_categories(),
                        'std'         => ''
                    )
                )
            ),
            'testimonial_slider' => array(
                'title'       => __( 'Testimonials slider', 'yit' ),
                'description' => __( 'Show a slider with testimonials', 'yit' ),
                'tab'         => 'cpt',
                'has_content' => false,
                'in_visual_composer' => true,
                'create'      => false,
                'attributes'  => array(
                    'items'           => array(
                        'title'       => __( 'N. of items', 'yit' ),
                        'description' => __( 'Show all with -1', 'yit' ),
                        'type'        => 'number',
                        'std'         => '-1'
                    ),
                    'excerpt'         => array(
                        'title' => __( 'Limit words', 'yit' ),
                        'type'  => 'number',
                        'std'   => '32'
                    ),
                    'speed'           => array(
                        'title' => __( 'Speed (ms)', 'yit' ),
                        'type'  => 'number',
                        'std'   => '300'
                    ),
                    'paginationspeed' => array(
                        'title' => __( 'Pagination Speed (ms)', 'yit' ),
                        'type'  => 'number',
                        'std'   => '400'
                    ),
                    'navigation'      => array(
                        'title' => __( 'Navigation', 'yit' ),
                        'type'  => 'checkbox',
                        'std'   => 'yes'
                    ),
                    'pagination'      => array(
                        'title' => __( 'Pagination', 'yit' ),
                        'type'  => 'checkbox',
                        'std'   => 'no'
                    ),
                    'show_border'        => array(
                        'title' => __( 'Show Border', 'yit' ),
                        'type'  => 'checkbox',
                        'std'   => 'yes'
                    ),
                    'autoplay'        => array(
                        'title' => __( 'Autoplay', 'yit' ),
                        'type'  => 'checkbox',
                        'std'   => 'yes'
                    ),
                    'cat'             => array(
                        'title'       => __( 'Categories', 'yit' ),
                        'description' => __( 'Select the categories of posts to show', 'yit' ),
                        'type'        => 'select',
                        'options'     => yit_get_testimonial_categories(),
                        'std'         => ''
                    ),
                    'title_text'      => array(
                        'title'       => __( 'Title', 'yit' ),
                        'description' => __( 'Select the shortcodes title', 'yit' ),
                        'type'        => 'text',
                        'std'         => ''
                    ),
                )
            ),
        );
    }
}

if ( !function_exists( 'yit_testimonial_add_fields' ) ) {
    function yit_testimonial_add_fields() {
        return array(
            'yit_testimonial_role'       => array(
                'label' => __( 'Role', 'yit' ),
                'type'  => 'text',
                'desc'  => __( 'Insert role of testimonial (leave empty to not use it)', 'yit' ),
                'std'   => ''
            ),
            'yit_testimonial_social'      => array(
                'label' => __( 'Label', 'yit' ),
                'desc'  => __( 'Insert the label used for testimonial if Website Url is set.', 'yit' ),
                'type'  => 'text',
                'std'   => '' ),

            'yit_testimonial_website'     => array(
                'label' => __( 'Web Site Url', 'yit' ),
                'desc'  => __( 'Insert the url referred to Testimonial', 'yit' ),
                'type'  => 'text',
                'std'   => '' ),

            'yit_testimonial_small_quote' => array(
                'label' => __( 'Small Quote', 'yit' ),
                'desc'  => __( 'Insert the text to show with blockquote', 'yit' ),
                'type'  => 'text',
                'std'   => '' ),
        );
    }
}

if( ! function_exists( 'yit_contact_form_buttons_style' ) ) {

    /*
    * add theme button styles
    *
    * @return void
    * @since 2.0
    * @author Francesco Grasso <francesco.grasso@yithemes.com>
    */

    function yit_contact_form_buttons_style(){
        return array(
            'ghost'         => __( 'Ghost', 'yit' ),
            'flat-red'      => __( 'Flat Red', 'yit' ),
            'flat-orange'   => __( 'Flat Orange', 'yit' ),
        );
    }
}

if ( ! function_exists( 'yit_quick_view' ) ) {
    function yit_quick_view() {
        do_action( 'yit_load_quick_view' );
        yit_get_template( 'quick-view/quick-view.php' );
    }
}

if ( !function_exists( 'yit_team_add_fields' ) ) {
    function yit_team_add_fields() {
        return array(
            'member_role' => array(
                'label' => __( 'Member role', 'yit' ),
                'type'  => 'text',
                'desc'  => __( 'Insert role of team member (leave empty to not use it)', 'yit' ),
                'std'   => ''
            ),

            'facebook'    => array(
                'label' => __( 'Facebook', 'yit' ),
                'type'  => 'text',
                'desc'  => __( 'Insert facebook address', 'yit' ),
                'std'   => ''
            ),

            'twitter'     => array(
                'label' => __( 'Twitter', 'yit' ),
                'type'  => 'text',
                'desc'  => __( 'Insert twitter address', 'yit' ),
                'std'   => ''
            ),

            'google-plus' => array(
                'label' => __( 'Google Plus', 'yit' ),
                'type'  => 'text',
                'desc'  => __( 'Insert google plus address', 'yit' ),
                'std'   => ''
            ),

            'pinterest'   => array(
                'label' => __( 'Pinterest', 'yit' ),
                'type'  => 'text',
                'desc'  => __( 'Insert pinterest address', 'yit' ),
                'std'   => ''
            ),

            'instagram'   => array(
                'label' => __( 'Instagram', 'yit' ),
                'type'  => 'text',
                'desc'  => __( 'Insert instagram address', 'yit' ),
                'std'   => ''
            ),
        );
    }
}

if( ! function_exists('yit_team_section_shortcode') ){
    function yit_team_section_shortcode(){
        return array(
            'team_section' => array(
                'title' => __( 'Team', 'yit' ),
                'description' => __( 'Adds a slider with team members', 'yit' ),
                'tab' => 'section',
                'create' => false,
                'has_content'  => false,
                'in_visual_composer' => true,
                'attributes' => array(
                    'team' => array(
                        'title' => __( 'Team', 'yit' ),
                        'type' => 'select',
                        'options' => YIT_Team()->get_teams(),
                        'std' => ''
                    ),

                    'nitems' => array(
                        'title' => __( 'Number of member', 'yit' ),
                        'type' => 'number',
                        'min' => -1,
                        'max' => 99,
                        'std' => -1
                    ),
                    'show_role' => array(
                        'title' => __( 'Show role', 'yit' ),
                        'type' => 'checkbox',
                        'std' => 'yes'
                    ),
                    'show_social' => array(
                        'title' => __( 'Show social', 'yit' ),
                        'type' => 'checkbox',
                        'std' => 'yes'
                    ),
                )
            )
        );
    }
}

if ( !function_exists( 'yit_logo_shortcode_options' ) ){
    function yit_logo_shortcode_options(){
        return array(
            'logos_slider' => array(
                'title' => __('Logos slider', 'yit' ),
                'description' =>  __('Show a slider with logos', 'yit' ),
                'tab' => 'cpt',
                'in_visual_composer' => true,
                'create' => false,
                'has_content' => false,
                'attributes' => array(
                    'title' => array(
                        'title' => __('Title', 'yit'),
                        'type' => 'text',
                        'std'  => ''
                    ),
                    'items' => array(
                        'title' => __('N. of items', 'yit'),
                        'type' => 'number',
                        'std'  => '-1'
                    ),
                    'height' => array(
                        'title' => __('Height (px)', 'yit'),
                        'type' => 'number',
                        'std'  => '20'
                    ),
                    'active_bw' => array(
                        'title' => __('Active Black and White', 'yit'),
                        'type' => 'checkbox',
                        'std'  => 'no'
                    ),
                    'is_slide' => array(
                        'title' => __('Show the slider', 'yit'),
                        'type' => 'checkbox',
                        'std'  => '1'
                    ),
                    'nitems' => array(
                        'title' => __('N. of items visible on the screen', 'yit'),
                        'type' => 'number',
                        'std'  => '5',
                        'deps'  => array(
                            'ids'    => 'is_slide',
                            'values' => '1'
                        )
                    ),
                    'speed' => array(
                        'title' => __('Speed (ms)', 'yit'),
                        'type' => 'number',
                        'std'  => '500',
                        'deps'  => array(
                            'ids'    => 'is_slide',
                            'values' => '1'
                        )
                    )
                )
            )
        );
    }
}

if ( !function_exists( 'yit_newsletter_shortcode_options' ) ){

    function yit_newsletter_shortcode_options( $options, $opt ){


        $options['newsletter_cta'] = array(
            'title' => __('Call to action newsletter', 'yit' ),
            'description' =>  __('Show a message with newsletter subscription', 'yit' ),
            'tab' => 'shortcodes',
            'create' => false,
            'in_visual_composer' => true,
            'has_content' => false,
            'attributes' => array(
                'title' => array(
                    'title' => __('Title', 'yit'),
                    'type' => 'text',
                    'std'  => ''
                ),
                'title_size' => array(
                    'title' => __('Title size', 'yit'),
                    'type' => 'number',
                    'min' => 10,
                    'max' => 99,
                    'std' => 18
                ),
                'title_color' => array(
                    'title' => __('Title color', 'yit'),
                    'type' => 'colorpicker',
                    'std' => '#383838'
                ),
                'incipit' => array(
                    'title' => __('Incipit', 'yit'),
                    'type' => 'text',
                    'std'  => ''
                ),
                'incipit_size' => array(
                    'title' => __('Incipit size', 'yit'),
                    'type' => 'number',
                    'min' => 10,
                    'max' => 99,
                    'std' => 14
                ),
                'incipit_color' => array(
                    'title' => __('Incipit color', 'yit'),
                    'type' => 'colorpicker',
                    'std' => '#5b5a5a'
                ),
                'post_name' => array(
                    'title' => __('Newsletter Form', 'yit'),
                    'type' => 'select',
                    'options' => $opt,
                    'std'  => ''
                ),
                'icon_form' => array(
                    'title' => __( 'Newsletter Form Icon', 'yit' ),
                    'type' => 'select',
                    'options' => YIT_Plugin_Common::get_awesome_icons(),
                    'std' => 'f003'
                ),
                'button_class' => array(
                    'title' => __( 'Form Button Class', 'yit' ),
                    'type' => 'select',
                    'options' => array(
                       'btn-ghost' => __( 'Ghost', 'yit' ),
                       'btn-flat-red' => __( 'Flat Red', 'yit' ),
                       'btn-flat-orange' => __( 'Flat Orange', 'yit' )
                    ),
                    'std'     => 'btn-flat-orange'
                )
            )
        );

        return $options;

    }
}