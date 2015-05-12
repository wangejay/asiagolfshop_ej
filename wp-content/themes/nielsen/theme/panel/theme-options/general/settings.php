<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Return an array with the options for Theme Options > General > Settings
 *
 * @package Yithemes
 * @author Andrea Grillo <andrea.grillo@yithemes.com>
 * @author Antonio La Rocca <antonio.larocca@yithemes.it>
 * @since 2.0.0
 * @return mixed array
 *
 */
return array(

    /* General > Settings */
    array(
        'type' => 'title',
        'name' => __( 'General Settings', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'general-layout-type',
        'type' => 'select',
        'options' => array(
            'stretched' => __( 'Stretched', 'yit' ),
            'boxed' => __( 'Boxed', 'yit' )
        ),
        'name' => __( 'Layout Type', 'yit' ),
        'desc' => __( 'Choose the layout type.', 'yit' ),
        'std' => 'stretched',
        'in_skin'        => true
    ),

    array(
        'id' => 'general-remove-scripts-version',
        'type' => 'onoff',
        'name' => __( 'Remove script version', 'yit' ),
        'desc' => __( 'This is an advanced setting that allows you remove query strings from static contents (eg. the ?v=3.5.1 string from CSS and JS files). <a href="http://gtmetrix.com/remove-query-strings-from-static-resources.html">More info</a>', 'yit' ),
        'std' => 'no'
    ),

    array(
        'id' => 'general-show-back-to-top',
        'type' => 'onoff',
        'name' => __('Show "Back to Top" button', 'yit'),
        'desc' => __('Enable this option to show the "Back to Top" button in all pages', 'yit'),
        'std' => 'no'
    ),

    array(
        'id' => 'general-activate-responsive',
        'type' => 'onoff',
        'name' => __( 'Enable responsive layout', 'yit' ),
        'desc' => __( 'Enable this option to make pages to adapt to screen dimensions', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'general-favicon',
        'type' => 'upload',
        'name' => __( 'Favicon', 'yit' ),
        'desc' => __( 'A favicon is a 16x16 pixel icon that represents your site; paste the URL to a icon image that you want to use as the image.', 'yit' ),
        'validate' => 'esc_url',
        'std' => get_template_directory_uri().'/favicon.ico'
    ),

    array(
        'id' => 'general-lock-down-admin',
        'type' => 'onoff',
        'name' => __( 'Disable WP admin bar', 'yit' ),
        'desc' => __( 'Use this option to disable the wordpress admin bar in frontend for user are logged in', 'yit' ),
        'std' => 'yes'
    ),


    ( defined('YITH_WCAS') ) ? false : array(
        'id' => 'general-search-type',
        'type' => 'select',
        'options' => array(
            'product' => __( 'Products', 'yit' ),
            'post' => __( 'Posts', 'yit' ),
            'any' => __( 'All', 'yit' ),
        ),
        'name' => __( 'Search type header', 'yit' ),
        'desc' => __( 'Select the type of posts you want to search with searchform of the header.', 'yit' ),
        'std' => 'post',
        'in_skin'        => true
    ),

    array(
        'id' => 'general-disable-comment-on-pages-admin',
        'type' => 'onoff',
        'name' => __( 'Disable Comments on Pages', 'yit' ),
        'desc' => __( 'Set this option to on for disable the wordpress comments on Pages. (Not include blog posts)', 'yit' ),
        'std' => 'no'
    ),


    array(
        'id' => 'general-enable-sidebar-border',
        'type' => 'onoff',
        'name' => __( 'Show sidebar border', 'yit' ),
        'desc' => __( 'Set this option to enable or disable border to each sidebar', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'type' => 'title',
        'name' => __( 'Open Graph', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'general-enable-open-graph',
        'type' => 'onoff',
        'name' => __( 'Enable open graph', 'yit' ),
        'desc' => __( 'Enable open graph or use your own plugin.', 'yit' ),
        'std' => 'yes'
    )


);

