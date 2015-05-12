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
 * Return the list of shortcodes and their settings
 *
 * @package Yithemes
 * @author  Francesco Licandro  <francesco.licandro@yithemes.com>
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly


$config          = YIT_Plugin_Common::load();
$awesome_icons   = YIT_Plugin_Common::get_awesome_icons();
$list_icon       = YIT_Plugin_Common::get_icon_list();
$animate         = $config['animate'];

$shop_shortcodes = array ();

$theme_shortcodes = array(

    /*=== PRINT BORDER ===*/
    'border' => array(
        'title' => __('Print border line', 'yit' ),
        'description' =>  __('Print a border', 'yit' ),
        'tab' => 'shortcodes',
        'has_content' => false,
        'in_visual_composer' => true,
        'attributes' => array(
            'width'     => array(
                'title' => __( 'Width (px)', 'yit' ),
                'type'  => 'text',
                'std'   => '',
                'desc' => __('Leave empty to 100%', 'yit'),
            ),
            'animate' => array(
                'title' => __('Animation', 'yit'),
                'type' => 'select',
                'options' => $animate,
                'std'  => ''
            ),
            'animation_delay' => array(
                'title' => __('Animation Delay', 'yit'),
                'type' => 'text',
                'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                'std'  => '0'
            )
        )
    ),

    /*=== BOX SECTION ===*/
    'box_section' => array(
        'title' => __('Icon box', 'yit' ),
        'description' =>  __('Shows a box, with Title and icons on left and a text of section (you can use HTML tags)', 'yit' ),
        'tab' => 'shortcodes',
        'in_visual_composer' => true,
        'has_content' => true,
        'attributes' => array(
            'layout'          => array(
                'title'   => __( 'Layout', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'horizontal' => __( 'Horizontal', 'yit' ),
                    'vertical'   => __( 'Vertical', 'yit' )
                ),
                'std'     => 'horizontal'
            ),

            'icon_type'       => array(
                'title'   => __( 'Icon type', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'theme-icon' => __( 'Theme Icon', 'yit' ),
                    'custom'     => __( 'Custom Icon', 'yit' )
                ),
                'std'     => 'theme-icon'
            ),

            'icon_theme'      => array(
                'title' => __( 'Icon', 'yit' ),
                'type'  => 'icon-list',
                'deps'  => array(
                    'ids'    => 'icon_type',
                    'values' => 'theme-icon'
                ),
                'std'   => ''
            ),

            'icon_custom'     => array(
                'title' => __( 'Icon URL', 'yit' ),
                'type'  => 'text',
                'std'   => '',
                'deps'  => array(
                    'ids'    => 'icon_type',
                    'values' => 'custom'
                )
            ),

            'icon_size'       => array(
                'title' => __( 'Icon size', 'yit' ),
                'type'  => 'number',
                'min'   => '9',
                'max'   => '90',
                'std'   => '14',
                'deps'  => array(
                    'ids'    => 'icon_type',
                    'values' => 'theme-icon'
                )
            ),
            'color'           => array(
                'title' => __( 'Icon Color', 'yit' ),
                'type'  => 'colorpicker',
                'std'   => '#797979',
                'deps'  => array(
                    'ids'    => 'icon_type',
                    'values' => 'theme-icon'
                )
            ),
            'circle_size'     => array(
                'title' => __( 'Square Size', 'yit' ),
                'type'  => 'number',
                'std'   => '0',
                'deps'  => array(
                    'ids'    => 'icon_type',
                    'values' => 'theme-icon'
                )
            ),

            'border_size'     => array(
                'title' => __( 'Border Square Size', 'yit' ),
                'type'  => 'number',
                'std'   => '3',
                'deps'  => array(
                    'ids'    => 'icon_type',
                    'values' => 'theme-icon'
                )
            ),

            'color_circle'    => array(
                'title' => __( 'Border Color Icon', 'yit' ),
                'type'  => 'colorpicker',
                'std'   => '#797979',
                'deps'  => array(
                    'ids'    => 'icon_type',
                    'values' => 'theme-icon'
                )
            ),

            'title'           => array(
                'title' => __( 'Title', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),

            'title_size'      => array(
                'title'   => __( 'Title tag', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    ''   => __( 'Default', 'yit' ),
                    'h2' => __( 'h2', 'yit' ),
                    'h3' => __( 'h3', 'yit' ),
                    'h4' => __( 'h4', 'yit' ),
                    'h5' => __( 'h5', 'yit' ),
                    'h6' => __( 'h6', 'yit' )
                ),
                'std'     => 'h3'
            ),

            'class'           => array(
                'title' => __( 'Add CSS class', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),

            'link'            => array(
                'title' => __( 'Link', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),

            'link_title'      => array(
                'title' => __( 'Link title', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),

            'animate'         => array(
                'title'   => __( 'Animation', 'yit' ),
                'type'    => 'select',
                'options' => $animate,
                'std'     => ''
            ),

            'animation_delay' => array(
                'title' => __( 'Animation Delay', 'yit' ),
                'type'  => 'text',
                'desc'  => __( 'This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit' ),
                'std'   => '0'
            )
        )
    ),

    /*=== ONE PAGE ANCHOR ===*/
    'onepage_anchor' => array(
        'title' => __( 'OnePage Anchor', 'yit' ),
        'description' => __( 'Add the anchor for your OnePage', 'yit' ),
        'tab' => 'shortcodes',
        'has_content' => false,
        'in_visual_composer' => true,
        'attributes' => array(
            'name' => array(
                'title' => __('Name anchor (the name of anchor you define in the menu with #)', 'yit'),
                'type' => 'text',
                'std'  => ''
            )
        )
    ),

    /*=== GOOGLE MAPS ===*/
    'googlemap'    => array(
        'title'              => __( 'Google Maps', 'yit' ),
        'description'        => __( 'Print the google map box', 'yit' ),
        'tab'                => 'shortcodes',
        'in_visual_composer' => true,
        'has_content'        => false,
        'attributes'         => array(
            'full_width'      => array(
                'title' => __( 'Full Width', 'yit' ),
                'type'  => "checkbox",
                'std'   => 'yes'
            ),
            'width'           => array(
                'title' => __( 'Width', 'yit' ),
                'type'  => 'number',
                'std'   => '',
                'deps'  => array(
                    'ids'    => 'full_width',
                    'values' => '0'
                )
            ),
            'height'          => array(
                'title' => __( 'Height', 'yit' ),
                'type'  => 'number',
                'std'   => '424'
            ),
            'address'             => array(
                'title' => __( 'Address', 'yit' ),
                'type'  => 'text',
                'desc'  => 'like "1600 Amphitheatre Parkway, Mountain View, CA"',
                'std'   => ''
            ),

            'latitude'             => array(
                'title' => __( 'Latitude', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),

            'longitude'             => array(
                'title' => __( 'Longitude', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),


            'zoom'             => array(
                'title' => __( 'zoom', 'yit' ),
                'type'  => 'number',
                'desc'  => 'enter a number between 0 and 19',
                'std'   => '15'
            ),

            'marker'  => array(
                'title' => __( 'Marker', 'yit' ),
                'type'  => 'text',
                'desc'  => 'add an icon for the Marker',
                'std'   => ''
            ),
            'style'  => array(
                'title' => __( 'Style', 'yit' ),
                'type'  => 'select',
                'options' => array(
                  'color' => __( 'Color', 'yit'),
                  'black' => __( 'Black and White', 'yit'),
                ),
                'desc'  => 'choose the style',
                'std'   => 'black'
            ),

        )
    ),

    /*=== BLOG SECTION ===*/
    'blog_section' =>  array(
        'title' => __( 'Blog', 'yit' ),
        'description' => __( 'Print a blog slider', 'yit' ),
        'tab' => 'section',
        'has_content' => false,
        'in_visual_composer' => true,
        'create' => true,
        'attributes' => array(
            'nitems' => array(
                'title' => __( 'Number of items', 'yit' ),
                'description' => __( '-1 to show all elements', 'yit' ),
                'type' => 'number',
                'min' => -1,
                'max' => 99,
                'std' => -1
            ),
            'cat_name'        => array(
                'title'    => __( 'Category', 'yit' ),
                'type'     => 'select', // list of all categories
                'multiple' => true,
                'options'  => $categories,
                'std'      => serialize( array() )
            ),
            'enable_slider' => array(
                'title' => __( 'Enable Slider', 'yit' ),
                'type' => 'checkbox',
                'std' => 'yes'
            ),
            'enable_thumbnails' => array(
                'title' => __( 'Show Thumbnails', 'yit' ),
                'type' => 'checkbox',
                'std' => 'yes'
            ),
            'enable_date' => array(
                'title' => __( 'Show Date', 'yit' ),
                'type' => 'checkbox',
                'std' => 'yes'
            ),
            'date_style' => array(
                'title' => __('Alternative date style', 'yit'),
                'type' => 'checkbox',
                'std' => 'no'
            ),
            'enable_title' => array(
                'title' => __( 'Show Title', 'yit' ),
                'type' => 'checkbox',
                'std' => 'yes'
            ),
            'enable_author' => array(
                'title' => __( 'Show Author', 'yit' ),
                'type' => 'checkbox',
                'std' => 'yes'
            ),
            'enable_comments' => array(
                'title' => __( 'Show Comments', 'yit' ),
                'type' => 'checkbox',
                'std' => 'yes'
            ),
            'animate' => array(
                'title' => __('Animation', 'yit'),
                'type' => 'select',
                'options' => $animate,
                'std'  => ''
            ),
            'animation_delay' => array(
                'title' => __('Animation Delay', 'yit'),
                'type' => 'text',
                'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                'std'  => '0'
            )
        )
    ),

    /*=== SHARE ===*/
    'share'        => array(
        'title'              => __( 'Share', 'yit' ),
        'description'        => __( 'Print share buttons', 'yit' ),
        'has_content'        => false,
        'in_visual_composer' => true,
        'tab'                => 'shortcodes',
        'attributes'         => array(

            'title'       => array(
                'title' => __( 'Title', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),
            'socials'     => array(
                'title'    => __( 'Socials', 'yit' ),
                'type'     => 'select',
                'multiple' => true,
                'options'  => array(
                    'facebook'  => __( 'Facebook', 'yit' ),
                    'twitter'   => __( 'Twitter', 'yit' ),
                    'google'    => __( 'Google+', 'yit' ),
                    'pinterest' => __( 'Pinterest', 'yit' )
                ),
                'std'      => serialize( array() )
            ),
            'class'       => array(
                'title' => __( 'CSS Class', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),
            'size'        => array(
                'title'   => __( 'Size', 'yit' ),
                'type'    => 'select', // small|
                'options' => array(
                    'small' => __( 'Small', 'yit' ),
                    ''      => __( 'Normal', 'yit' )
                ),
                'std'     => ''
            ),
            'icon_type'   => array(
                'title'   => __( 'Icon Type', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'icon' => __( 'Icon', 'yit' ),
                    'text' => __( 'Text', 'yit' )
                ),
                'std'     => 'icon',
            ),

        )
    ),

    /*=== SEPARATOR ===*/
    'separator'    => array(
        'title'              => __( 'Separator', 'yit' ),
        'description'        => __( 'Print a separator line', 'yit' ),
        'tab'                => 'shortcodes',
        'has_content'        => false,
        'create'             => true,
        'in_visual_composer' => true,
        'attributes'         => array(
            'style'         => array(
                'title'   => __( 'Separator style', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'single' => __( 'Single line', 'yit' ),
                    'double' => __( 'Double line', 'yit' ),
                    'dotted' => __( 'Dotted line', 'yit' ),
                    'dashed' => __( 'Dashed line', 'yit' )
                ),
                'std'     => 'single'
            ),
            'color'         => array(
                'title' => __( 'Separator color', 'yit' ),
                'type'  => 'colorpicker',
                'std'   => '#b5b4b4'
            ),
            'margin_top'    => array(
                'title' => __( 'Margin top', 'yit' ),
                'type'  => 'number',
                'min'   => 0,
                'max'   => 999,
                'std'   => 0
            ),
            'margin_bottom' => array(
                'title' => __( 'Margin bottom', 'yit' ),
                'type'  => 'number',
                'min'   => 0,
                'max'   => 999,
                'std'   => 35
            )
        )
    ),

    /*=== MODAL ===*/
    'modal'        => array(
        'title'              => __( 'Modal Window', 'yit' ),
        'description'        => __( 'Create a modal window', 'yit' ),
        'tab'                => 'shortcodes',
        'in_visual_composer' => true,
        'has_content'        => true,
        'attributes'         => array(
            'title'              => array(
                'title' => __( 'Modal Title', 'yit' ),
                'type'  => 'text',
                'std'   => __( 'Your title here', 'yit' )
            ),
            'opener'             => array(
                'title'   => __( 'Type of modal opener', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'button' => __( 'Button', 'yit' ),
                    'text'   => __( 'Textual Link', 'yit' ),
                    'image'  => __( 'Image', 'yit' )
                ),
                'std'     => 'button'
            ),
            'button_text_opener' => array(
                'title' => __( 'Text of the button', 'yit' ),
                'type'  => 'text',
                'std'   => __( 'Open Modal', 'yit' ),
                'deps'  => array(
                    'ids'    => 'opener',
                    'values' => 'button'
                )
            ),
            'button_style'       => array(
                'title'   => __( 'Style of the button', 'yit' ),
                'type'    => 'select',
                'options' => yit_button_style(),
                'std'     => 'flat-red',
                'deps'    => array(
                    'ids'    => 'opener',
                    'values' => 'button'
                )
            ),
            'link_text_opener'   => array(
                'title' => __( 'Text of the link', 'yit' ),
                'type'  => 'text',
                'std'   => __( 'Open Modal', 'yit' ),
                'deps'  => array(
                    'ids'    => 'opener',
                    'values' => 'text'
                )
            ),
            'link_icon_type'     => array(
                'title'   => __( 'Icon type', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'none'       => __( 'None', 'yit' ),
                    'theme-icon' => __( 'Theme Icon', 'yit' ),
                    'custom'     => __( 'Custom Icon', 'yit' )
                ),
                'std'     => 'none',
                'deps'    => array(
                    'ids'    => 'opener',
                    'values' => 'text'
                )
            ),
            'link_icon_theme'    => array(
                'title'   => __( 'Icon', 'yit' ),
                'type'    => 'select-icon', // home|file|time|ecc
                'options' => $awesome_icons,
                'std'     => '',
                'deps'    => array(
                    'ids'    => 'link_icon_type',
                    'values' => 'theme-icon'
                )
            ),
            'link_icon_url'      => array(
                'title' => __( 'Icon URL', 'yit' ),
                'type'  => 'text',
                'std'   => '',
                'deps'  => array(
                    'ids'    => 'link_icon_type',
                    'values' => 'custom'
                )
            ),
            'link_text_size'     => array(
                'title' => __( 'Font size of the link', 'yit' ),
                'type'  => 'number',
                'std'   => 17,
                'min'   => 1,
                'max'   => 99,
                'deps'  => array(
                    'ids'    => 'opener',
                    'values' => 'text'
                )
            ),
            'image_opener'       => array(
                'title' => __( 'Url of the image', 'yit' ),
                'type'  => 'text',
                'std'   => '',
                'deps'  => array(
                    'ids'    => 'opener',
                    'values' => 'image'
                )
            ),
        )
    ),

    /*=== SOCIAL ===*/
    'social' => array(
        'title' => __('Social', 'yit' ),
        'description' =>  __('Print a simple icon link for social', 'yit' ),
        'tab' => 'shortcodes',
        'has_content' => false,
        'in_visual_composer' => true,
        'attributes' => array(
            'icon_type' => array(
                'title' => __('Icon Type', 'yit'),
                'type'  => 'select',
                'options' => array(
                    'icon' => __('Icon', 'yit'),
                    'text' => __('Text', 'yit')
                ),
                'std' => 'icon',
            ),
            'icon_social' => array(
                'title' => __('Icon', 'yit'),
                'type' => 'select-icon',
                'options' => $awesome_icons_socials,
                'std'  => 'f09a',
                'deps' => array(
                    'ids' => 'icon_type',
                    'values' => 'icon'
                )
            ),
            'icon_size' => array(
                'title' => __('Icon size', 'yit'),
                'type' => 'number',
                'min' => '9',
                'max' => '90',
                'std'  => '14',
                'deps' => array(
                    'ids' => 'icon_type',
                    'values' => 'icon'
                )
            ),
            'color' => array(
                'title' => __('Color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#b1b1b1',
                'deps' => array(
                    'ids' => 'icon_type',
                    'values' => 'icon'
                )
            ),
            'color_hover' => array(
                'title' => __('Color on Hover', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#000000',
                'deps' => array(
                    'ids' => 'icon_type',
                    'values' => 'icon'
                )
            ),
            'circle' => array(
                'title' => __('Square', 'yit'),
                'type'  => 'select',
                'options' => array(
                    'yes' => __('Yes', 'yit'),
                    'no' => __('No', 'yit')
                ),
                'std' => 'no',
                'deps' => array(
                    'ids' => 'icon_type',
                    'values' => 'icon'
                )
            ),
            'circle_size' => array(
                'title' => __('Square Size', 'yit'),
                'type'  => 'number',
                'std' => '35',
                'deps' => array(
                    'ids' => 'circle',
                    'values' => 'yes'
                )

            ),
            'circle_border_size' => array(
                'title' => __('Square Border Width', 'yit'),
                'type'  => 'number',
                'std' => '1',
                'deps' => array(
                    'ids' => 'circle',
                    'values' => 'yes'
                )

            ),
            'href' => array(
                'title' => __('URL', 'yit'),
                'type' => 'text',
                'std'  => '#'
            ),
            'target' => array(
                'title' => __('Target', 'yit'),
                'type' => 'select',
                'options' => array(
                    '' => __('Default', 'yit'),
                    '_blank' => __('Blank', 'yit'),
                    '_parent' => __('Parent', 'yit'),
                    '_top' => __('Top', 'yit')
                ),
                'std'  => ''
            ),
            'title' => array(
                'title' => __('Title', 'yit'),
                'type' => 'text',
                'std'  => ''
            )
        )
    ) ,

    /*=== BOX TITLE ===*/
    'box_title' => array(
        'title' => __('Box title', 'yit' ),
        'description' =>  __('Show a title centered with line', 'yit' ),
        'tab' => 'shortcodes',
        'in_visual_composer' => true,
        'has_content' => true,
        'attributes' => array(
            'class' => array(
                'title' => __('Class', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'subtitle' => array(
                'title' => __( 'Subtitle', 'yit' ),
                'type' => 'text',
                'std' => ''
            ),

            'subtitle_font_size' => array(
                'title' => __( 'Subtitle Font size (px)', 'yit' ),
                'type' => 'text',
                'min' => 1,
                'max' => 99,
                'std' => 15
            ),
            'font_size' => array(
                'title' => __( 'Title Font size (px)', 'yit' ),
                'type' => 'number',
                'min' => 1,
                'max' => 99,
                'std' => 15
            ),
            'font_alignment' => array(
                'title' => __( 'Font alignment', 'yit' ),
                'type' => 'select',
                'options' => array(
                    'left' => __( 'Left', 'yit' ),
                    'right' => __( 'Right', 'yit' ),
                    'center' => __( 'Center', 'yit' )
                ),
                'std' => 'center'
            ),
            'border' => array(
                'title' => __('Border', 'yit'),
                'type' => 'select',
                'options' => array(
                    'bottom' => __('Bottom', 'yit'),
                    'bottom-little-line' => __('Bottom Little Line', 'yit'),
                    'middle' => __('Middle', 'yit'),
                    'around' => __('Around', 'yit'),
                    'double' => __('Double', 'yit'),
                    'none' => __('none', 'yit')
                ),
                'std'  => 'middle'
            ),
            'border_color' => array(
                'title' => __('Border Color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#f2f2f2'
            ),
            'animate' => array(
                'title' => __('Animation', 'yit'),
                'type' => 'select',
                'options' => $animate,
                'std'  => ''
            ),
            'animation_delay' => array(
                'title' => __('Animation Delay', 'yit'),
                'type' => 'text',
                'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                'std'  => '0'
            )        )
    ),

    /*=== CONTACT INFO ===*/
    'contact_info' => array(
        'title' => __('Contact info', 'yit' ),
        'description' =>  __('Show a contact info', 'yit' ),
        'tab' => 'shortcodes',
        'in_visual_composer' => true,
        'has_content' => false,
        'attributes' => array(
            'title' => array(
                'title' => __('Title', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'subtitle' => array(
                'title' => __('Subtitle', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'address_title' => array(
                'title' => __('Address Title', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'address' => array(
                'title' => __('Address', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'address_icon' => array(
                'title' => __('Address icon', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'phone_title' => array(
                            'title' => __('Phone Title', 'yit'),
                            'type' => 'text',
                            'std'  => ''
                        ),
            'phone' => array(
                'title' => __('Phone', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),

            'phone_icon' => array(
                'title' => __('Phone icon', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'mobile_title' => array(
                'title' => __('Mobile Title', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'mobile' => array(
                'title' => __('Mobile', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'mobile_icon' => array(
                'title' => __('Mobile icon', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'fax_title' => array(
                'title' => __('Fax Title', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'fax' => array(
                'title' => __('Fax', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'fax_icon' => array(
                'title' => __('Fax icon', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'email_title' => array(
                'title' => __('E-mail Title', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'email' => array(
                'title' => __('E-mail text', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'email_icon' => array(
                'title' => __('E-mail icon', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'email_link' => array(
                'title' => __('E-mail link', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),

            'animate' => array(
                'title' => __('Animation', 'yit'),
                'type' => 'select',
                'options' => $animate,
                'std'  => ''
            ),
            'animation_delay' => array(
                'title' => __('Animation Delay', 'yit'),
                'type' => 'text',
                'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                'std'  => '0'
            )
        )
    ),

    /*=== TEASER ===*/
    'teaser'       => array(
        'title'              => __( 'Teaser', 'yit' ),
        'description'        => __( 'Create a banner with an image, a link and text.', 'yit' ),
        'tab'                => 'shortcode',
        'has_content'        => false,
        'in_visual_composer' => true,
        'hide'               => false,
        'attributes'         => array(
            'title'           => array(
                'title' => __( 'Title', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),

            'title_color'  => array(
                'title' => __( 'Title color', 'yit' ),
                'type'  => 'colorpicker',
                'std'   => '#ffffff'
            ),

            'title_size' => array(
                'title' => __( 'Title font size', 'yit' ),
                'type'  => 'number',
                'std'   => '24'
            ),

            'border_inside'  => array(
                'title' => __( 'Border inside teaser color', 'yit' ),
                'type'  => 'colorpicker',
                'std'   => 'transparent'
            ),


            'subtitle'        => array(
                'title' => __( 'Subtitle', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),

            'subtitle_color'  => array(
                'title' => __( 'Subtitles color', 'yit' ),
                'type'  => 'colorpicker',
                'std'   => '#ffffff'
            ),


            'subtitle_size' => array(
                'title' => __( 'Subtitle font Size', 'yit' ),
                'type'  => 'number',
                'std'   => '15'
            ),


            'image'           => array(
                'title' => __( 'Image URL', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),


            'image_effect'           => array(
                'title' => __( 'Image Effect', 'yit' ),
                'type'  => 'select',
                'options' => array(
                    'no_image_effect' => 'None',
                    'zoomin'          => 'Zoom In',
                    'zoomout'         => 'Zoom Out',
                ),
                'std'   => 'zoomin'
            ),

            'link'            => array(
                'title' => __( 'Link', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),

            'button'          => array(
                'title' => __( 'Label button', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),


            'button_style' => array(
                'title'   => __( 'Button Style', 'yit' ),
                'type'    => 'select',
                'options' => yit_button_style(),
                'std'     => 'ghost'
            ),

            'slogan_position' => array(
                'title'   => __( 'Slogan Position', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'center'       => __( 'Center Center', 'yit' ),
                    'top left'     => __( 'Top Left', 'yit' ),
                    'center left'  => __( 'Center Left', 'yit' ),
                    'bottom left'  => __( 'Bottom Left', 'yit' ),
                    'top right'    => __( 'Top Right', 'yit' ),
                    'center right' => __( 'Center Right', 'yit' ),
                    'bottom right' => __( 'Bottom Right', 'yit' ),
                ),
                'std'     => ''
            ),
            'animate'         => array(
                'title'   => __( 'Animation', 'yit' ),
                'type'    => 'select',
                'options' => $animate,
                'std'     => ''
            ),
            'animation_delay' => array(
                'title' => __( 'Animation Delay', 'yit' ),
                'type'  => 'text',
                'desc'  => __( 'This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit' ),
                'std'   => '0'
            )
        )
    ),

    /*=== FEATURED COLUMNS ===*/
    'featured_column' =>  array(
        'title' => __( 'Featured Columns', 'yit' ),
        'description' => __( 'Print a column with image, description and button', 'yit' ),
        'tab' => 'shortcodes',
        'has_content' => true,
        'in_visual_composer' => true,
        'create' => true,
        'attributes' => array(
            'title' => array(
                'title' => __( 'Title', 'yit' ),
                'type' => 'text',
                'std' => ''
            ),
            'subtitle' => array(
                'title' => __( 'Subtitle', 'yit' ),
                'type' => 'text',
                'std' => ''
            ),
            'show_button' => array(
                'title' => __( 'Show Button', 'yit' ),
                'type' => 'checkbox',
                'std' => 'yes'
            ),

            'label_button' => array(
                'title' => __( 'Label Button', 'yit' ),
                'type' => 'text',
                'std' => '',
                'deps' => array(
                    'ids' => 'show_button',
                    'values' => '1'
                )
            ),
            'url_button' => array(
                'title' => __( 'Url Button', 'yit' ),
                'type' => 'text',
                'std' => '',
                'deps' => array(
                    'ids' => 'show_button',
                    'values' => '1'
                )
            ),

            'button_style' => array(
                'title'   => __( 'Button Style', 'yit' ),
                'type'    => 'select',
                'options' => yit_button_style(),
                'std'     => 'ghost'
            ),


            'background_image' => array(
                'title' => __( 'Background image URL', 'yit' ),
                'type' => 'text',
                'std' => ''
            ),
            'first' => array(
                'title' => __( 'First column?', 'yit' ),
                'type' => 'checkbox',
                'std' => 'no'
            ),
            'last' => array(
                'title' => __( 'Last Columns?', 'yit' ),
                'type' => 'checkbox',
                'std' => 'no'
            ),
            'animate' => array(
                'title' => __('Animation', 'yit'),
                'type' => 'select',
                'options' => $animate,
                'std'  => ''
            ),
            'animation_delay' => array(
                'title' => __('Animation Delay', 'yit'),
                'type' => 'text',
                'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                'std'  => '0'
            )


        )
    ),

    /*=== PARALLAX ===*/
    'parallax'     => array(
        'title'              => __( 'Parallax effect', 'yit' ),
        'description'        => __( 'Create a fancy full-width parallax effect', 'yit' ),
        'tab'                => 'shortcodes',
        'has_content'        => true,
        'in_visual_composer' => true,
        'create'             => true,
        'attributes'         => array(
            'height'             => array(
                'title' => __( 'Container height', 'yit' ),
                'type'  => 'number',
                'std'   => 300
            ),
            'image'              => array(
                'title' => __( 'Background Image URL', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),
            'valign'             => array(
                'title'   => __( 'Vertical Align', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'top'    => __( 'Top', 'yit' ),
                    'center' => __( 'Center', 'yit' ),
                    'bottom' => __( 'Bottom', 'yit' ),
                ),
                'std'     => 'center'
            ),
            'halign'             => array(
                'title'   => __( 'Horizontal Align', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'left'   => __( 'Left', 'yit' ),
                    'center' => __( 'Center', 'yit' ),
                    'right'  => __( 'Right', 'yit' ),
                ),
                'std'     => 'center'
            ),
            'font_p'             => array(
                'title' => __( 'Paragraph Font Size', 'yit' ),
                'type'  => 'number',
                'std'   => 24
            ),
            'color'              => array(
                'title' => __( 'Content Text Color', 'yit' ),
                'type'  => 'colorpicker',
                'std'   => '#ffffff'
            ),
            'overlay_opacity'    => array(
                'title'       => __( 'Overlay', 'yit' ),
                'description' => __( 'Set an opacity of overlay (0-100)', 'yit' ),
                'type'        => 'number',
                'std'         => '0'
            ),
            'border_bottom'      => array(
                'title'       => __( 'Border Bottom', 'yit' ),
                'description' => __( 'Set a size for border bottom (0-10)', 'yit' ),
                'type'        => 'number',
                'min'         => 0,
                'max'         => 10,
                'std'         => '0'
            ),
            'effect'             => array(
                'title'   => __( 'Effect', 'yit' ),
                'type'    => 'select',
                'options' => array(
                    'fadeIn'            => __( 'fadeIn', 'yit' ),
                    'fadeInUp'          => __( 'fadeInUp', 'yit' ),
                    'fadeInDown'        => __( 'fadeInDown', 'yit' ),
                    'fadeInLeft'        => __( 'fadeInLeft', 'yit' ),
                    'fadeInRight'       => __( 'fadeInRight', 'yit' ),
                    'fadeInUpBig'       => __( 'fadeInUpBig', 'yit' ),
                    'fadeInDownBig'     => __( 'fadeInDownBig', 'yit' ),
                    'fadeInLeftBig'     => __( 'fadeInLeftBig', 'yit' ),
                    'fadeInRightBig'    => __( 'fadeInRightBig', 'yit' ),
                    'bounceIn'          => __( 'bounceIn', 'yit' ),
                    'bounceInDown'      => __( 'bounceInDown', 'yit' ),
                    'bounceInUp'        => __( 'bounceInUp', 'yit' ),
                    'bounceInLeft'      => __( 'bounceInLeft', 'yit' ),
                    'bounceInRight'     => __( 'bounceInRight', 'yit' ),
                    'rotateIn'          => __( 'rotateIn', 'yit' ),
                    'rotateInDownLeft'  => __( 'rotateInDownLeft', 'yit' ),
                    'rotateInDownRight' => __( 'rotateInDownRight', 'yit' ),
                    'rotateInUpLeft'    => __( 'rotateInUpLeft', 'yit' ),
                    'rotateInUpRight'   => __( 'rotateInUpRight', 'yit' ),
                    'lightSpeedIn'      => __( 'lightSpeedIn', 'yit' ),
                    'hinge'             => __( 'hinge', 'yit' ),
                    'rollIn'            => __( 'rollIn', 'yit' ),
                ),
                'std'     => 'fadeIn'
            ),

            'video_upload_mp4'   => array(
                'title' => __( 'Video Mp4', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),
            'video_upload_ogg'   => array(
                'title' => __( 'Video Ogg', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),
            'video_upload_webm'  => array(
                'title' => __( 'Video Webm', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),
            'video_poster'       => array(
                'title' => __( 'Video Poster', 'yit' ),
                'type'  => 'text',
                'std'   => ''
            ),
            'video_button'       => array(
                'title'       => __( 'Add a button', 'yit' ),
                'description' => __( 'Add a button to see a video in a lightbox', 'yit' ),
                'type'        => 'checkbox',
                'std'         => 'no'
            ),
            'video_button_style' => array(
                'title'       => __( 'Video button style', 'yit' ),
                'description' => __( 'Choose a style for video button', 'yit' ),
                'type'        => 'select',
                'options'     => yit_button_style(),
                'std'         => 'ghost'
            ),
            'video_url'          => array(
                'title'       => __( 'Video URL', 'yit' ),
                'description' => __( 'Paste the url of the video that will be opened in the lightbox', 'yit' ),
                'type'        => 'text',
                'std'         => ''
            ),
            'label_button_video' => array(
                'title'       => __( 'Button Label', 'yit' ),
                'description' => __( 'Add the label of the button', 'yit' ),
                'type'        => 'text',
                'std'         => ''
            )
        )
    ),

    /*=== CALL TO ACTION PHONE ===*/
    'call' => array(
          'title' => __('Call to action phone', 'yit' ),
          'description' =>  __('Shows a box with an incipit and a number phone', 'yit' ),
          'tab' => 'shortcodes',
          'in_visual_composer' => true,
          'create' => true,
          'has_content' => true,
          'attributes' => array(
              'title' => array(
                  'title' => __('Title', 'yit'),
                  'type' => 'text',
                  'std'  => ''
              ),
              'phone' => array(
                  'title' => __('Phone', 'yit'),
                  'type' => 'text',
                  'std'  => ''
              ),
              'icon_theme'      => array(
                  'title' => __( 'Icon', 'yit' ),
                  'type'  => 'icon-list',
                  'std'   => ''
              ),
              'class' => array(
                  'title' => __('CSS class', 'yit'),
                  'type' => 'text',
                  'std'  => 'call-to-action'
              ),
              'animate' => array(
                  'title' => __('Animation', 'yit'),
                  'type' => 'select',
                  'options' => $animate,
                  'std'  => ''
              ),
              'animation_delay' => array(
                  'title' => __('Animation Delay', 'yit'),
                  'type' => 'text',
                  'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                  'std'  => '0'
              )
          )
      ),

    /*=== CALL TO ACTION BUTTON ===*/
    'call_two' => array(
        'title' => __('Call to action with button', 'yit' ),
        'description' =>  __('Shows a box with an incipit and a button', 'yit' ),
        'tab' => 'shortcodes',
        'in_visual_composer' => true,
        'has_content' => false,
        'attributes' => array(
            'href' => array(
                'title' => __('URL', 'yit'),
                'type' => 'text',
                'std'  => '#'
            ),

            'background_color' => array(
                'title' => __('Background Color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#ffffff'
            ),
            'title_text' => array(
                'title' => __('Title text', 'yit'),
                'type' => 'text',
                'std'  => 'Title text'
            ),
            'title_font_size' => array(
                'title' => __('Title font size', 'yit'),
                'type' => 'number',
                'std'  => '18'
            ),
            'title_color' => array(
                'title' => __('Title font color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#6d6c6c'
            ),
            'subtitle_text' => array(
                'title' => __('Subtitle text', 'yit'),
                'type' => 'text',
                'std'  => 'Subtitle text'
            ),
            'subtitle_font_size' => array(
                'title' => __('Subtitle font size', 'yit'),
                'type' => 'number',
                'std'  => '14'
            ),
            'subtitle_color' => array(
                'title' => __('Subtitle font color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#5b5a5a'
            ),
            'label_button' => array(
                'title' => __('Label button', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'label_size' => array(
                'title' => __('Label font size', 'yit'),
                'type' => 'number',
                'std'  => '14'
            ),
            'class' => array(
                'title' => __('CSS class', 'yit'),
                'type' => 'text',
                'std'  => 'call-to-action-two'
            ),

            'animate' => array(
                'title' => __('Animation', 'yit'),
                'type' => 'select',
                'options' => $animate,
                'std'  => ''
            ),
            'animation_delay' => array(
                'title' => __('Animation Delay', 'yit'),
                'type' => 'text',
                'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                'std'  => '0'
            )
        )
    ),

    /*=== CALL TO ACTION BUTTON ALTERNATIVE ===*/
    'call_four' => array(
        'title' => __('Call to action with button alternative', 'yit' ),
        'description' =>  __('Shows a box with an incipit and a button', 'yit' ),
        'tab' => 'shortcodes',
        'in_visual_composer' => true,
        'has_content' => false,
        'attributes' => array(
            'href' => array(
                'title' => __('URL', 'yit'),
                'type' => 'text',
                'std'  => '#'
            ),

            'background_color' => array(
                'title' => __('Background Color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#ffffff'
            ),
            'title_text' => array(
                'title' => __('Title text', 'yit'),
                'type' => 'text',
                'type' => 'text',
                'std'  => 'Title text'
            ),
            'title_font_size' => array(
                'title' => __('Title font size', 'yit'),
                'type' => 'number',
                'std'  => '18'
            ),
            'title_color' => array(
                'title' => __('Title font color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#6d6c6c'
            ),
            'subtitle_text' => array(
                'title' => __('Subtitle text', 'yit'),
                'type' => 'text',
                'std'  => 'Subtitle text'
            ),
            'subtitle_font_size' => array(
                'title' => __('Subtitle font size', 'yit'),
                'type' => 'number',
                'std'  => '14'
            ),
            'subtitle_color' => array(
                'title' => __('Subtitle font color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#5b5a5a'
            ),
            'label_button' => array(
                'title' => __('Label button', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'label_size' => array(
                'title' => __('Label font size', 'yit'),
                'type' => 'number',
                'std'  => '14'
            ),
            'class' => array(
                'title' => __('CSS class', 'yit'),
                'type' => 'text',
                'std'  => 'call-to-action-two'
            ),

            'animate' => array(
                'title' => __('Animation', 'yit'),
                'type' => 'select',
                'options' => $animate,
                'std'  => ''
            ),
            'animation_delay' => array(
                'title' => __('Animation Delay', 'yit'),
                'type' => 'text',
                'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                'std'  => '0'
            )
        )
    ),

    /* === COUNTER === */
    'counter' => array(
        'title' => __('Counter', 'yit' ),
        'description' =>  __('Show ', 'yit' ),
        'tab' => 'shortcodes',
        'has_content' => false,
        'in_visual_composer' => true,
        'attributes' => array(
            'text' => array(
                'title' => __('Text', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'text_color' => array(
                'title' => __('Text Color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#363636',
            ),
            'text_size' => array(
                'title' => __('Text size', 'yit'),
                'type' => 'number',
                'std'  => '16'
            ),
            'number' => array(
                'title' => __('Number', 'yit'),
                'type' => 'text',
                'std'  => ''
            ),
            'number_size' => array(
                'title' => __('Number size', 'yit'),
                'type' => 'text',
                'std'  => '52'
            ),
            'number_color' => array(
                'title' => __('Number Color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#363636'
            ),
            'percent' => array(
                'title' => __('Percent', 'yit'),
                'type' => 'checkbox',
                'std' => '1'
            ),
            'percent_color' => array(
                'title' => __('Percent Color', 'yit'),
                'type' => 'colorpicker',
                'std' => '#6d6c6c'
            ),
            'icon_type' => array(
                'title' => __('Icon type', 'yit'),
                'type'  => 'select',
                'options' => array(
                    'none' => __('None', 'yit'),
                    'theme-icon' => __('Theme Icon', 'yit'),
                    'custom' => __('Custom Icon', 'yit')
                ),
                'std' => 'none'
            ),
            'icon_theme' => array(
                'title' => __('Icon', 'yit'),
                'type' => 'icon-list',  // home|file|time|ecc
                'options' => $list_icon,
                'std'  => '',
                'deps' => array(
                    'ids' => 'icon_type',
                    'values' => 'theme-icon'
                )
            ),
            'icon_url' =>  array(
                'title' => __('Icon URL', 'yit'),
                'type' => 'text',
                'std'  => '',
                'deps' => array(
                    'ids' => 'icon_type',
                    'values' => 'custom'
                )
            ),
            'icon_size' => array(
                'title' => __('Icon size', 'yit'),
                'type' => 'number',
                'min' => '9',
                'max' => '90',
                'std'  => '55',
                'deps' => array(
                    'ids' => 'icon_type',
                    'values' => 'theme-icon'
                )
            ),
            'icon_color' => array(
                'title' => __('Icon Color', 'yit'),
                'type' => 'colorpicker',
                'std'  => '#000000',
                'deps' => array(
                    'ids' => 'icon_type',
                    'values' => 'theme-icon'
                )
            ),
            'hide_border' => array(
                'title' => __('Hide Border', 'yit'),
                'type' => 'checkbox',
                'std'  => '0'
            ),
            'animate' => array(
                'title' => __( 'Animate numbers', 'yit' ),
                'type' => 'checkbox',
                'std' => '1'
            ),

            'animation_start_number' => array(
                'title' => __( 'Start number', 'yit'),
                'type' => 'text',
                'std' => 0,
                'deps' => array(
                    'ids' => 'animate',
                    'values' => '1'
                )
            ),
            'animation_duration' => array(
                'title' => __( 'Animation duration (ms)', 'yit' ),
                'type' => 'text',
                'std' => 2000,
                'deps' => array(
                    'ids' => 'animate',
                    'values' => '1'
                )
            ),
            'animation_step' =>array(
                'title' => __( 'Animation step', 'yit' ),
                'type' => 'text',
                'std' => 10,
                'deps' => array(
                    'ids' => 'animate',
                    'values' => '1'
                )
            )
        )
    ),
);

if ( function_exists( 'YIT_Team' ) ) {
    $theme_shortcodes['team_section'] = array(
        'title'              => __( 'Team', 'yit' ),
        'description'        => __( 'Adds team members', 'yit' ),
        'tab'                => 'section',
        'create'             => false,
        'has_content'        => false,
        'in_visual_composer' => true,
        'attributes'         => array(
            'team'          => array(
                'title'   => __( 'Team', 'yit' ),
                'type'    => 'select',
                'options' => YIT_Team()->get_teams(),
                'std'     => ''
            ),
            'nitems'        => array(
                'title' => __( 'Number of member', 'yit' ),
                'type'  => 'number',
                'min'   => - 1,
                'max'   => 99,
                'std'   => - 1
            ),
            'show_role'     => array(
                'title' => __( 'Show role', 'yit' ),
                'type'  => 'checkbox',
                'std'   => 'yes'
            ),
            'show_social'   => array(
                'title' => __( 'Show social', 'yit' ),
                'type'  => 'checkbox',
                'std'   => 'yes'
            )
        )
    );
}


if ( function_exists( 'WC' ) ) {
    $shop_shortcodes = array(

        /* === PRODUCTS TABS === */
        'products_tabs' => array(
            'title' => __('Products Tabs', 'yit'),
            'description' => __('List products in tabs', 'yit'),
            'tab' => 'shop',
            'multiple' => true,
            'unlimited'   => true,
            'has_content' => false,
            'in_visual_composer' => false,
            'attributes' => array(
                'title_1' => array(
                    'title' => __('Title', 'yit'),
                    'type' => 'text',
                    'std'  => '',
                    'multiple' => true
                ),
                'per_page_1' => array(
                    'title' => __('N. of items', 'yit'),
                    'description' => __('Show all with -1', 'yit'),
                    'type' => 'number',
                    'std'  => '10',
                    'multiple' => true
                ),
                'product_in_a_row_1' => array(
                    'title' => __('Visible items', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        '2' => __('2', 'yit' ),
                        '3' => __('3', 'yit' ),
                        '4' => __('4', 'yit' ),
                        '6' => __('6', 'yit' )
                    ),
                    'std'  => '4',
                    'multiple' => true
                ),
                'category_1' => array(
                    'title' => __('Category', 'yit'),
                    'type' => 'select',
                    'options' => yit_get_shop_categories(false),
                    'std'  => serialize( array() ),
                    'multiple' => true
                ),
                'show_1' => array(
                    'title' => __('Show', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        'all' => __('All', 'yit'),
                        'featured' => __('Featured', 'yit'),
                        'onsale' => __('On sale', 'yit'),
                      ),
                    'std' => serialize ( array( 'all' ) ),
                    'multiple' => true
                ),
                'orderby_1' => array(
                    'title' => __( 'Order by', 'yit' ),
                    'type' => 'select',
                    'options' => apply_filters( 'woocommerce_catalog_orderby', array(
                        'rand' => __( 'Random', 'yit'),
                        'title' => __( 'Sort alphabetically', 'yit' ),
                        'date' => __( 'Sort by most recent', 'yit' ),
                        'price' => __( 'Sort by price', 'yit' ),
                        'sales' => __( 'Sort by sales', 'yit')
                    ) ),
                    'std' => serialize( array( 'rand' ) ),
                    'multiple' => true
                ),
                'order_1' => array(
                    'title' => __('Sorting', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        'desc' => __('Descending', 'yit'),
                        'asc' => __('Crescent', 'yit')
                    ),
                    'std'  => serialize( array( 'desc' ) ),
                    'multiple' => true
                ),
                'animate' => array(
                    'title' => __('Animation', 'yit'),
                    'type' => 'select',
                    'options' => $animate,
                    'std'  => ''
                ),
                'animation_delay' => array(
                    'title' => __('Animation Delay', 'yit'),
                    'type' => 'text',
                    'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                    'std'  => '0'
                )
            )
        ),

        /* === PRODUCTS SLIDER === */
        'products_slider' => array(
            'title' => __('Products slider', 'yit'),
            'description' => __('Add a products slider', 'yit'),
            'tab' => 'shop',
            'has_content' => false,
            'in_visual_composer' => true,
            'attributes' => array(
                'title' => array(
                    'title' => __( 'Title', 'yit' ),
                    'type' => 'text',
                    'std' => ''
                ),
                'per_page' => array(
                    'title' => __( 'Number of Items', 'yit' ),
                    'type' => 'number',
                    'std' => '12'
                ),
                'product_in_a_row' => array(
                    'title' => __('Visible Items', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        '2' => __('2', 'yit' ),
                        '3' => __('3', 'yit' ),
                        '4' => __('4', 'yit' ),
                        '6' => __('6', 'yit' )
                    ),
                    'std'  => '4'
                ),
                'category' => array(
                    'title' => __('Category', 'yit'),
                    'type' => 'select',
                    'options' => yit_get_shop_categories(true),
                    'std'  => serialize( array() ),
                    'multiple' => true
                ),
                'product_type' => array(
                    'title' => __('Product Type', 'yit' ),
                    'type' => 'select',
                    'options' => array(
                        'all' => __('All products', 'yit' ),
                        'featured' => __('Featured Products', 'yit' ),
                        'on_sale' => __( 'On Sale Products', 'yit' )
                    ),
                    'std'  => 'all'
                ),
                'orderby' => array(
                    'title' => __( 'Order by', 'yit' ),
                    'type' => 'select',
                    'options' => apply_filters( 'woocommerce_catalog_orderby', array(
                        'rand' => __( 'Random', 'yit' ),
                        'title' => __( 'Sort alphabetically', 'yit' ),
                        'date' => __( 'Sort by most recent', 'yit' ),
                        'price' => __( 'Sort by price', 'yit' ),
                        'sales' => __( 'Sort by sales', 'yit')
                    ) ),
                    'std' => 'rand'
                ),
                'order' => array(
                    'title' => __('Sorting', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        'desc' => __('Descending', 'yit'),
                        'asc' => __('Crescent', 'yit')
                    ),
                    'std'  => 'desc'
                ),
                'hide_free' => array(
                    'title' => __( 'Hide free products', 'yit' ),
                    'type'  => 'checkbox',
                    'std'   => 'no'
                ),
                'show_hidden' => array(
                    'title' => __( 'Show hidden products', 'yit' ),
                    'type'  => 'checkbox',
                    'std'   => 'no'
                ),
                'autoplay' => array(
                    'title' => __('Autoplay', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        'true' => __('True', 'yit'),
                        'false' => __('False', 'yit'),
                    ),
                    'std'  => 'true'
                ),
                'animate' => array(
                    'title' => __('Animation', 'yit'),
                    'type' => 'select',
                    'options' => $animate,
                    'std'  => ''
                ),
                'animation_delay' => array(
                    'title' => __('Animation Delay', 'yit'),
                    'type' => 'text',
                    'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                    'std'  => '0'
                )
            )
        ),

        /* === Single product === */
        'single_product' => array(
            'title' => __('Single Product', 'yit' ),
            'description' =>  __('Show a single product', 'yit' ),
            'tab' => 'shop',
            'has_content' => false,
            'in_visual_composer' => true,
            'attributes' => array(
                'product_id' => array(
                    'title' => __('Product id', 'yit'),
                    'type'  => 'text',
                    'desc'  => __( 'This value is the Id of the product', 'yit' ),
                    'std'   => '0'
                ),
                'animate'         => array(
                    'title'   => __( 'Animation', 'yit' ),
                    'type'    => 'select',
                    'options' => $animate,
                    'std'     => ''
                ),
                'animation_delay' => array(
                    'title' => __( 'Animation Delay', 'yit' ),
                    'type'  => 'text',
                    'desc'  => __( 'This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit' ),
                    'std'   => '0'
                ),
                'size' => array(
                    'title' => __( 'Image Thumb Size', 'yit' ),
                    'type'  => 'select',
                    'options' => array(
                        'small' => __('Small ( 70x70 )', 'yit'),
                        'big'   => __('Big ( 90x90 )', 'yit')
                    ),
                    'desc'  => __( 'This value determines the product image thumb size.', 'yit' ),
                    'std'   => 'small'
                )
            )

        ),

        /* === Single product two === */
        'single_product_two' => array(
            'title' => __('Single Product Two', 'yit' ),
            'description' =>  __('Show a single product', 'yit' ),
            'tab' => 'shop',
            'has_content' => false,
            'in_visual_composer' => true,
            'attributes' => array(
                'product_id' => array(
                    'title' => __('Product id', 'yit'),
                    'type'  => 'text',
                    'desc'  => __( 'This value is the Id of the product', 'yit' ),
                    'std'   => '0'
                ),
                'border' => array(
                    'title' => __( 'Border Box', 'yit' ),
                    'type'  => 'select',
                    'options' => array(
                        'none' => __('None', 'yit'),
                        'right'   => __('Right', 'yit'),
                        'left'   => __('Left', 'yit'),
                    ),
                    'desc'  => __( 'This value determines the product image thumb size.', 'yit' ),
                    'std'   => 'none'
                ),
                'animate'         => array(
                    'title'   => __( 'Animation', 'yit' ),
                    'type'    => 'select',
                    'options' => $animate,
                    'std'     => ''
                ),
                'animation_delay' => array(
                    'title' => __( 'Animation Delay', 'yit' ),
                    'type'  => 'text',
                    'desc'  => __( 'This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit' ),
                    'std'   => '0'
                )
            )

        ),

        /* === SHOW PRODUCTS === */
        'show_products' => array(
            'title' => __('Show the products', 'yit'),
            'description' => __('Show the products', 'yit'),
            'tab' => 'shop',
            'has_content' => false,
            'in_visual_composer' => true,
            'attributes' => array(
                'layout' => array(
                    'title' => __('Layout', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        'default' => __('Default', 'yit'),
                        'grid' => __('Grid', 'yit'),
                        'list' => __('List', 'yit')
                    ),
                    'std'  => 'grid'
                ),

                'product_in_a_row' => array(
                    'title' => __('Product in a row', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        '2' => __('2', 'yit'),
                        '3' => __('3', 'yit'),
                        '4' => __('4', 'yit'),
                        '6' => __('6', 'yit')
                    ),
                    'std'  => '3',
                    'deps'  => array(
                        'ids'    => 'layout',
                        'values' => 'grid'
                    ),
                ),
                'filter_type' => array(
                    'title' => __( 'Filter by', 'yit' ),
                    'type' => 'select',
                    'options' => array(
                        'category' => __( 'Category', 'yit' ),
                        'ids' => __( 'Products ID', 'yit' ),
                    ),
                    'std' => 'category'
                ),
                'ids' => array(
                    'title' => __('Products ID es: 15,20,25', 'yit'),
                    'type' => 'text',
                    'desc' => __('insert a comma separated list of ids', 'yit'),
                    'std' => '' ,
                    'deps'  => array(
                        'ids'    => 'filter_type',
                        'values' => 'ids'
                    ),

                ),
                'per_page' => array(
                    'title' => __('N. of items', 'yit'),
                    'description' => __('Show all with -1', 'yit'),
                    'type' => 'number',
                    'std'  => '8' ,
                    'deps'  => array(
                        'ids'    => 'filter_type',
                        'values' => 'category'
                    ),
                ),
                'category' => array(
                    'title' => __('Category', 'yit'),
                    'type' => 'select',
                    'multiple' => true,
                    'options' => yit_get_shop_categories(true),
                    'std'  => serialize( array() ),
                    'deps'  => array(
                        'ids'    => 'filter_type',
                        'values' => 'category'
                    ),
                ),
                'show' => array(
                    'title' => __('Show', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        'all' => __('All Products', 'yit'),
                        'featured' => __('Featured Products', 'yit'),
                        'on_sale' => __('On Sale Products', 'yit'),
                    ),
                    'std' => 'all',
                    'deps'  => array(
                        'ids'    => 'filter_type',
                        'values' => 'category'
                    ),
                ),
                'orderby' => array(
                    'title' => __( 'Order by', 'yit' ),
                    'type' => 'select',
                    'options' => apply_filters( 'woocommerce_catalog_orderby', array(
                        'rand' => __( 'Random', 'yit' ),
                        'title' => __( 'Sort alphabetically', 'yit' ),
                        'date' => __( 'Sort by most recent', 'yit' ),
                        'price' => __( 'Sort by price', 'yit' ),
                        'sales' => __( 'Sort by sales', 'yit' )
                    ) ),
                    'std' => 'rand'
                ),
                'hide_free' => array(
                    'title' => __( 'Hide free products', 'yit' ),
                    'type'  => 'checkbox',
                    'std'   => 'no',
                    'deps'  => array(
                        'ids'    => 'filter_type',
                        'values' => 'category'
                    ),
                ),
                'show_hidden' => array(
                    'title' => __( 'Show hidden products', 'yit' ),
                    'type'  => 'checkbox',
                    'std'   => 'no',
                    'deps'  => array(
                        'ids'    => 'filter_type',
                        'values' => 'category'
                    ),
                ),
                'order' => array(
                    'title' => __('Sorting', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        'desc' => __( 'Descending', 'yit'),
                        'asc' => __( 'Crescent', 'yit')
                    ),
                    'std'  => 'desc'
                ),
                'animate' => array(
                    'title' => __('Animation', 'yit'),
                    'type' => 'select',
                    'options' => $animate,
                    'std'  => ''
                ),
                'animation_delay' => array(
                    'title' => __('Animation Delay', 'yit'),
                    'type' => 'text',
                    'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                    'std'  => '0'
                )
            )
        ),

        /* === SHOW CATEGORY === */
        'show_category' => array(
            'title'              => __( 'Show Category', 'yit' ),
            'description'        => __( 'List all (or limited) product categories', 'yit' ),
            'tab'                => 'shop',
            'has_content'        => false,
            'in_visual_composer' => true,
            'attributes'         => array(
                'category'     => array(
                    'title'   => __( 'Category', 'yit' ),
                    'type'    => 'select',
                    'options' => yit_get_shop_categories( true ),
                    'std'     => ''
                ),
                'animate' => array(
                    'title' => __('Animation', 'yit'),
                    'type' => 'select',
                    'options' => $animate,
                    'std'  => ''
                ),
                'animation_delay' => array(
                    'title' => __('Animation Delay', 'yit'),
                    'type' => 'text',
                    'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                    'std'  => '0'
                )
            )
        ),

        /* === PRODUCTS CATEGORY === */
        'products_categories' => array(
            'title'              => __( 'Product Categories', 'yit' ),
            'description'        => __( 'List all (or limited) product categories', 'yit' ),
            'tab'                => 'shop',
            'has_content'        => false,
            'in_visual_composer' => true,
            'attributes'         => array(
                'category'     => array(
                    'title'   => __( 'Category', 'yit' ),
                    'type'    => 'checklist',
                    'options' => yit_get_shop_categories( true ),
                    'std'     => ''
                ),
                'hide_empty'   => array(
                    'title' => __( 'Hide empty', 'yit' ),
                    'type'  => 'checkbox',
                    'std'   => 'yes'
                ),
                'show_counter' => array(
                    'title' => __( 'Show Counter', 'yit' ),
                    'type'  => 'checkbox',
                    'std'   => 'yes'
                ),
                'orderby'      => array(
                    'title'   => __( 'Order by', 'yit' ),
                    'type'    => 'select',
                    'options' => apply_filters( 'woocommerce_catalog_orderby', array(
                        'menu_order' => __( 'Default sorting', 'yit' ),
                        'title'      => __( 'Sort alphabetically', 'yit' ),
                        'date'       => __( 'Sort by most recent', 'yit' ),
                        'price'      => __( 'Sort by price', 'yit' )
                    ) ),
                    'std'     => 'menu_order'
                ),
                'order'        => array(
                    'title'   => __( 'Sorting', 'yit' ),
                    'type'    => 'select',
                    'options' => array(
                        'desc' => __( 'Descending', 'yit' ),
                        'asc'  => __( 'Crescent', 'yit' )
                    ),
                    'std'     => 'desc'
                ),
                'animate'         => array(
                    'title'   => __( 'Animation', 'yit' ),
                    'type'    => 'select',
                    'options' => $animate,
                    'std'     => ''
                ),
                'animation_delay' => array(
                    'title' => __( 'Animation Delay', 'yit' ),
                    'type'  => 'text',
                    'desc'  => __( 'This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit' ),
                    'std'   => '0'
                )
            )
        ),

        /* === PRODUCTS CATEGORY SLIDER === */
        'products_categories_slider' => array(
            'title' => __('Categories slider', 'yit'),
            'description' => __('List all (or limited) product categories', 'yit'),
            'tab' => 'shop',
            'has_content' => false,
            'in_visual_composer' => true,
            'attributes' => array(
                'title' => array(
                    'title' => __( 'Title', 'yit' ),
                    'type' => 'text',
                    'std' => ''
                ),
                'category' => array(
                    'title' => __('Category', 'yit'),
                    'type' => 'checklist',
                    'options' => yit_get_shop_categories( true , 'all_cat'),
                    'std'  => 'all_cat'
                ),
                'show_counter' => array(
                    'title' => __('Show Counter', 'yit'),
                    'type' => 'checkbox',
                    'std'  => 'yes'
                ),
                'hide_empty' => array(
                    'title' => __('Hide empty', 'yit'),
                    'type' => 'checkbox',
                    'std'  => 'yes'
                ),
                'orderby' => array(
                    'title' => __( 'Order by', 'yit' ),
                    'type' => 'select',
                    'options' => apply_filters( 'woocommerce_catalog_orderby', array(
                        'menu_order' => __( 'Default sorting', 'yit' ),
                        'title' => __( 'Sort alphabetically', 'yit' ),
                        'count' => __( 'Sort by products count', 'yit' )
                    ) ),
                    'std' => 'menu_order'
                ),
                'order' => array(
                    'title' => __('Sorting', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        'desc' => __('Descending', 'yit'),
                        'asc' => __('Crescent', 'yit')
                    ),
                    'std'  => 'desc'
                ),
                'animate' => array(
                    'title' => __('Animation', 'yit'),
                    'type' => 'select',
                    'options' => $animate,
                    'std'  => ''
                ),
                'animation_delay' => array(
                    'title' => __('Animation Delay', 'yit'),
                    'type' => 'text',
                    'desc' => __('This value determines the delay to which the animation starts once it\'s visible on the screen.', 'yit'),
                    'std'  => '0'
                ),
                'autoplay' => array(
                    'title' => __('Autoplay', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        'true' => __('True', 'yit'),
                        'false' => __('False', 'yit'),
                    ),
                    'std'  => 'true'
                )
            )
        ),

        /* === CREDIT CARD === */
        'credit_card' => array(
            'title' => __('Credit card', 'yit' ),
            'description' =>  __('Show an images of credit cards', 'yit' ),
            'tab' => 'shortcodes',
            'has_content' => false,
            'in_visual_composer' => true,
            'attributes' => array(
                'type' => array(
                    'title' => __('Type', 'yit'),
                    'type' => 'checklist',
                    'options'  => array(
                        'c200' => '200',
                        'amazon' => 'Amazon',
                        'amex' => 'American Express',
                        'apple' => 'Apple',
                        'cirrus' => 'Cirrus',
                        'delta' => 'Delta',
                        'discover' => 'Discover',
                        'direct-debit' => 'Direct Debit',
                        'google' => 'Google',
                        'mastercard' => 'Mastercard',
                        'maestro' => 'Maestro',
                        'moneybookers' => 'Moneybookers',
                        'moneygram' => 'Moneygram',
                        'novus' => 'Novus',
                        'paypal-1' => 'Paypal 1',
                        'paypal-2' => 'Paypal 2',
                        'plain' => 'Plain',
                        'sage' => 'Sage',
                        'solo' => 'Solo',
                        'switch' => 'Switch',
                        'visa' => 'Visa',
                        'visa-debit' => 'Visa Debit',
                        'visa-electron' => 'Visa Electron',
                        'western-union' => 'Western Union',
                        'worldpay' => 'WorldPay'
                    ),
                    'std' => 'plain'
                ),
            )
        ),

    );
}
return ! empty( $shop_shortcodes ) ? array_merge( $theme_shortcodes, $shop_shortcodes ) : $theme_shortcodes;