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
 * Return an array with the options for Theme Options > Footer > Settings
 *
 * @package Yithemes
 * @author Andrea Grillo <andrea.grillo@yithemes.com>
 * @author Antonio La Rocca <antonio.larocca@yithemes.it>
 * @since 2.0.0
 * @return mixed array
 *
 */
return array(

    /* Footer > Settings */
    array(
        'type' => 'title',
        'name' => __( 'Footer Settings', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'footer-type',
        'type' => 'select',
        'options' => array(
            'normal' => __( 'Two Columns Copyright', 'yit' ),
            'centered' => __( 'Centered Copyright', 'yit' ),
            'big-normal' => __( 'Big Footer + Two Columns Copyright', 'yit' ),
            'big-centered' => __( 'Big Footer + Centered Copyright', 'yit' )
        ),
        'name' => __( 'Footer type', 'yit' ),
        'desc' => __( 'Select the footer layout for the theme.', 'yit' ),
        'std' =>'big-centered',
        'in_skin'        => true
    ),

    array(
        'id' => 'footer-rows',
        'type' => 'slider',
        'min' => 1,
        'max' => 4,
        'step' => 1,
        'name' => __( 'Footer Rows', 'yit' ),
        'desc' => __( "Select the number of widget area you'd like to use. Note: It will work only if you've chosen one of Big Footer types above. ", 'yit' ),
        'std' => 1,
        'deps' => array(
            'ids' => 'footer-type',
            'values' => 'big-normal,big-centered'
        )  ,
        'in_skin'        => true
    ),

    array(
        'id' => 'footer-columns',
        'type' => 'slider',
        'min' => 1,
        'max' => 4,
        'step' => 1,
        'name' => __( 'Widgets in each footer row', 'yit' ),
        'desc' => __( "Select the number of widget you'd like to use in each footer widget area. Note: It will work only if you've chosen one of Big Footer types above.", 'yit' ),
        'std' => 4,
        'deps' => array(
            'ids' => 'footer-type',
            'values' => 'big-normal,big-centered'
        ),
        'in_skin'        => true
    ),

    array(
        'type' => 'title',
        'name' => __( 'Footer Extra Sidebar', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'footer-add-extra-area',
        'type' => 'onoff',
        'name' => __('Footer Show Extra Area', 'yit'),
        'desc' => __('This area will be shown near the footer columns'  , 'yit' ),
        'std' => 'yes',
        'deps' => array(
            'ids' => 'footer-type',
            'values' => 'big-normal,big-centered'
        ),
    ),

    array(
        'id' => 'footer-extra-area-width',
        'type' => 'slider',
        'min' => 1,
        'max' => 12,
        'step' => 1,
        'name' => __( 'With of footer extra area', 'yit' ),
        'desc' => __( "Choose how much long will be the sidebar", 'yit' ),
        'std' => 4,
        'deps' => array(
            'ids' => 'footer-type',
            'values' => 'big-normal,big-centered'
        ),
        'in_skin'        => true
    ),


    array(
        'id' => 'footer-extra-area-position',
        'type' => 'select',
        'options' => array(
            'right' => __( 'Right', 'yit' ),
            'left' => __( 'Left', 'yit' ),
        ),
        'name' => __( 'Footer extra area position', 'yit' ),
        'desc' => __( 'Select the position of extra area', 'yit' ),
        'std' =>'right',
        'deps' => array(
            'ids' => 'footer-type',
            'values' => 'big-normal,big-centered'
        ),
        'in_skin'        => true
    ),


    array(
        'type' => 'title',
        'name' => __( 'Copyright', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'footer-left-text',
        'type' => 'textarea',
        'name' => __( 'Footer copyright text Left', 'yit' ),
        'desc' => __( 'Enter text used in the left side of the footer. It can be HTML. NB: not figured on "centered footer"', 'yit' ),
        'std' => 'Copyright '. date('Y').' - Nielsen, the perfect e-commerce theme by YIThemes',
        'deps' => array(
            'ids' => 'footer-type',
            'values' => 'normal,big-normal'
        )
    ),

    array(
        'id' => 'footer-center-text',
        'type' => 'textarea',
        'name' => __( 'Footer centered text', 'yit' ),
        'desc' => __( 'Enter text used in centered footer. It can be HTML.', 'yit' ),
        'std' => 'Copyright '. date('Y').' - Nielsen, the perfect e-commerce theme by YIThemes',
        'deps' => array(
            'ids' => 'footer-type',
            'values' => 'centered,big-centered'
        )
    ),

    array(
        'id' => 'footer-right-text',
        'type' => 'textarea',
        'name' => __( 'Footer copyright text Right', 'yit' ),
        'desc' => __( 'Enter text used in the right side of the footer. It can be HTML. NB: not figured on "centered footer"', 'yit' ),
        'std' => '',
        'deps' => array(
            'ids' => 'footer-type',
            'values' => 'normal,big-normal'
        )
    ),

    array(
        'type' => 'title',
        'name' => __( 'Footer Extra Row', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'footer-add-extra-row',
        'type' => 'onoff',
        'name' => __('Footer Show Extra Row', 'yit'),
        'desc' => __('Want to use an extra row between Footer Rows and Copyright', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'footer-extra-row-content',
        'type' => 'textarea',
        'name' => __( 'Footer copyright text Right', 'yit' ),
        'desc' => __( 'Enter text used in the right side of the footer. It can be HTML. NB: not figured on "centered footer"', 'yit' ),
        'std' => '[logos_slider title="PARTNERS" items="-1" height="20" active_bw="0" is_slide="0" nitems="3" speed="500"]',
        'deps' => array(
            'ids' => 'footer-add-extra-row',
            'values' => 'yes'
        )
    ),


);

