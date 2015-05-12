<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( !defined( 'YIT' ) ) {
    exit( 'Direct access forbidden.' );
}
/**
 * Contact info Cart Widget
 *
 * Displays custom contact info
 *
 * @class      YIT_Widget_Contact_Info
 * @extends    WP_Widget
 * @package    Yithemes
 * @author     YIThemes
 *
 * @since      Version 2.0.0
 */
if ( ! class_exists( 'YIT_Widget_Contact_Info' ) ) :

    class YIT_Widget_Contact_Info extends WP_Widget {
        var $woo_widget_cssclass;
        var $woo_widget_description;
        var $woo_widget_idbase;
        var $woo_widget_name;
        var $awesome_icons;

        /**
         * constructor
         *
         * @access public
         * @return void
         */
        function YIT_Widget_Contact_Info() {
            /* Widget variable settings. */
            $this->woo_widget_cssclass    = 'contact-info';
            $this->woo_widget_description = __( 'Widget with a simple contact information.', 'yit' );
            $this->woo_widget_idbase      = 'contact-info';
            $this->woo_widget_name        = __( 'Contact Info', 'yit' );
            $this->awesome_icons          = YIT_Plugin_Common::get_awesome_icons();

            $widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

            /* Create the widget. */
            $this->WP_Widget( 'contact-info', $this->woo_widget_name, $widget_ops );

            //wp_enqueue_script( 'media-upload' );
        }

        /**
         * form function.
         *
         * @see    WP_Widget->form
         * @access public
         *
         * @param array $instance
         *
         * @return void
         */
        function form( $instance ) {
            global $icons_name;

            /* Impostazioni di default del widget */
            $defaults = array(
                'title'         => __( 'Contacts', 'yit' ),
                'subtitle'      => '',
                'address'       => '',
                'address_image' => '',
                'phone'         => '',
                'phone_image'   => '',
                'mobile'        => '',
                'mobile_image'  => '',
                'email'         => '',
                'email_text'    => '',
                'email_image'   => '',
                'fax'           => '',
                'fax_image'     => '',
                'bbm'     => '',
                'bbm_image'     => '',
                'whatsup'     => '',
                'whatsup_image'     => '',
            );

            $instance = wp_parse_args( (array) $instance, $defaults ); ?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'yit' ) ?>:</label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><?php _e( 'Sub Title', 'yit' ) ?>:</label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subtitle' ) ); ?>" value="<?php echo esc_attr( $instance['subtitle'] ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php _e( 'Address', 'yit' ) ?>:</label>
                <textarea id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>"><?php echo esc_attr( $instance['address'] ); ?></textarea>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'address_image' ) ); ?>"><?php _e( 'Address icon', 'yit' ) ?>:</label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'address_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address_image' ) ); ?>" value="<?php echo esc_attr( $instance['address_image'] ); ?>" />
                <input type="button" value="<?php _e( 'Upload', 'yit' ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'address_image' ) ); ?>-button" class="upload_button button" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php _e( 'Phone', 'yit' ) ?>:</label>
                <textarea id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>"><?php echo esc_attr( $instance['phone'] ) ?></textarea>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'phone_image' ) ); ?>"><?php _e( 'Phone icon', 'yit' ) ?>:</label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'phone_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone_image' ) ); ?>" value="<?php echo esc_attr( $instance['phone_image'] ); ?>" />
                <input type="button" value="<?php _e( 'Upload', 'yit' ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'phone_image' ) ); ?>-button" class="upload_button button" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'mobile' ) ); ?>"><?php _e( 'Mobile', 'yit' ) ?>:</label>
                <textarea id="<?php echo esc_attr( $this->get_field_id( 'mobile' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'mobile' ) ); ?>"><?php echo esc_attr( $instance['mobile'] ) ?></textarea>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'mobile_image' ) ); ?>"><?php _e( 'Mobile icon', 'yit' ) ?>:</label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'mobile_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'mobile_image' ) ); ?>" value="<?php echo esc_attr( $instance['mobile_image'] ); ?>" />
                <input type="button" value="<?php _e( 'Upload', 'yit' ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'mobile_image' ) ); ?>-button" class="upload_button button" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'fax' ) ); ?>"><?php _e( 'Fax', 'yit' ) ?>:</label>
                <textarea id="<?php echo esc_attr( $this->get_field_id( 'fax' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fax' ) ); ?>"><?php echo esc_attr( $instance['fax'] ) ?></textarea>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'fax_image' ) ); ?>"><?php _e( 'Fax icon', 'yit' ) ?>:</label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'fax_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fax_image' ) ); ?>" value="<?php echo esc_attr( $instance['fax_image'] ); ?>" />
                <input type="button" value="<?php _e( 'Upload', 'yit' ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'fax_image' ) ); ?>-button" class="upload_button button" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'email_text' ) ); ?>"><?php _e( 'Email Text', 'yit' ) ?>:</label>
                <textarea id="<?php echo esc_attr( $this->get_field_id( 'email_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email_text' ) ); ?>"><?php echo esc_attr( $instance['email_text'] ) ?></textarea>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php _e( 'Email', 'yit' ) ?>:</label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" value="<?php echo esc_attr( $instance['email'] ) ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'email_image' ) ); ?>"><?php _e( 'Email icon', 'yit' ) ?>:</label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'email_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email_image' ) ); ?>" value="<?php echo esc_attr( $instance['email_image'] ); ?>" />
                <input type="button" value="<?php _e( 'Upload', 'yit' ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'email_image' ) ); ?>-button" class="upload_button button" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bbm' ) ); ?>"><?php _e( 'Black Barry Messenger', 'yit' ) ?>:</label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'bbm' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bbm' ) ); ?>" value="<?php echo esc_attr( $instance['bbm'] ) ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'bbm_image' ) ); ?>"><?php _e( 'Black Barry Messenger icon', 'yit' ) ?>:</label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'bbm_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bbm_image' ) ); ?>" value="<?php echo esc_attr( $instance['bbm_image'] ); ?>" />
                <input type="button" value="<?php _e( 'Upload', 'yit' ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'bbm_image' ) ); ?>-button" class="upload_button button" />
            </p>


            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'whatsup' ) ); ?>"><?php _e( 'Whatsup', 'yit' ) ?>:</label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'whatsup' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'whatsup' ) ); ?>" value="<?php echo esc_attr( $instance['whatsup'] ) ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'whatsup_image' ) ); ?>"><?php _e( 'Whatsup icon', 'yit' ) ?>:</label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'whatsup_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'whatsup_image' ) ); ?>" value="<?php echo esc_attr( $instance['whatsup_image'] ); ?>" />
                <input type="button" value="<?php _e( 'Upload', 'yit' ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'whatsup_image' ) ); ?>-button" class="upload_button button" />
            </p>

        <?php
        }

        /**
         * widget function.
         *
         * @see    WP_Widget
         * @access public
         *
         * @param array $args
         * @param array $instance
         *
         * @return void
         */
        function widget( $args, $instance ) {
            extract( $args );

            $title    = apply_filters( 'widget_title', $instance['title'] );
            $subtitle = $instance['subtitle'];

            echo $before_widget;


            if ( $title ) {
                echo $before_title . $title . $after_title;
            }

            if ( $subtitle ) {
                echo "<h2>" . $subtitle . "</h2>";
            }

            $phone_image   = ( isset( $instance['phone_image'] ) && $instance['phone_image'] != '' ) ? '<div class="icon-container background-image" style="background-image:url(' . $instance['phone_image'] . ')"></div>' : '';
            $address_image = ( isset( $instance['address_image'] ) && $instance['address_image'] != '' ) ? '<div class="icon-container background-image" style="background-image:url(' . $instance['address_image'] . ')"></div>' : '';
            $mobile_image  = ( isset( $instance['mobile_image'] ) && $instance['mobile_image'] != '' ) ? '<div class="icon-container background-image" style="background-image:url(' . $instance['mobile_image'] . ')"></div>' : '';
            $fax_image     = ( isset( $instance['fax_image'] ) && $instance['fax_image'] != '' ) ? '<div class="icon-container background-image" style="background-image:url(' . $instance['fax_image'] . ')"></div>' : '';
            $email_image   = ( isset( $instance['email_image'] ) && $instance['email_image'] != '' ) ? '<div class="icon-container background-image email" style="background-image:url(' . $instance['email_image'] . ')"></div>' : '';
            $bbm_image   = ( isset( $instance['bbm_image'] ) && $instance['bbm_image'] != '' ) ? '<div class="icon-container background-image bbm" style="background-image:url(' . $instance['bbm_image'] . ')"></div>' : '';
            $whatsup_image   = ( isset( $instance['whatsup_image'] ) && $instance['whatsup_image'] != '' ) ? '<div class="icon-container background-image whatsup" style="background-image:url(' . $instance['whatsup_image'] . ')"></div>' : '';

            $text = '<div class="sidebar-nav">';
            $text .= '  <ul>';
            $text .= ( ! empty( $instance['address'] ) ) ? '<li>' . $address_image . '<div class="info-container">' . do_shortcode( $instance['address'] ) . '</div></li>' : '';
            $text .= ( ! empty( $instance['phone'] ) ) ? '<li>' . $phone_image . '<div class="info-container">' . do_shortcode( $instance['phone'] ) . '</div></li>' : '';
            $text .= ( ! empty( $instance['mobile'] ) ) ? '<li>' . $mobile_image . '<div class="info-container">' . do_shortcode( $instance['mobile'] ) . '</div></li>' : '';
            $text .= ( ! empty( $instance['fax'] ) ) ? '<li>' . $fax_image . '<div class="info-container">' . do_shortcode( $instance['fax'] ) . '</div></li>' : '';
            $text .= ( ! empty( $instance['email'] ) ) ? '<li>' . $email_image . '<div class="info-container"><a href="mailto:' . $instance['email'] . '" class="contact_info_email">' . do_shortcode( $instance['email_text'] ) . '</a></div></li>' : '';
            $text .= ( ! empty( $instance['bbm'] ) ) ? '<li>' . $bbm_image . '<div class="info-container">' . do_shortcode( $instance['bbm'] ) . '</div></li>' : '';
            $text .= ( ! empty( $instance['whatsup'] ) ) ? '<li>' . $whatsup_image . '<div class="info-container">' . do_shortcode( $instance['whatsup'] ) . '</div></li>' : '';
            $text .= '  </ul>';
            $text .= '</div>';

            echo $text . $after_widget;
        }

        /**
         * update function.
         *
         * @see    WP_Widget->update
         * @access public
         *
         * @param array $new_instance
         * @param array $old_instance
         *
         * @return array
         */
        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;

            $instance['title'] = strip_tags( $new_instance['title'] );

            $instance['subtitle'] = strip_tags( $new_instance['subtitle'] );

            $instance['phone'] = str_replace( '"', "'", $new_instance['phone'] );

            $instance['phone_image'] = $new_instance['phone_image'];

            $instance['mobile'] = str_replace( '"', "'", $new_instance['mobile'] );

            $instance['mobile_image'] = $new_instance['mobile_image'];

            $instance['email'] = str_replace( '"', "'", $new_instance['email'] );

            $instance['email_text'] = str_replace( '"', "'", $new_instance['email_text'] );

            $instance['email_image'] = $new_instance['email_image'];

            $instance['address'] = str_replace( '"', "'", $new_instance['address'] );

            $instance['address_image'] = $new_instance['address_image'];

            $instance['fax'] = str_replace( '"', "'", $new_instance['fax'] );

            $instance['fax_image'] = $new_instance['fax_image'];

            $instance['bbm'] = str_replace( '"', "'", $new_instance['bbm'] );

            $instance['bbm_image'] = $new_instance['bbm_image'];

            $instance['whatsup'] = str_replace( '"', "'", $new_instance['whatsup'] );

            $instance['whatsup_image'] = $new_instance['whatsup_image'];

            return $instance;
        }

    }
endif;