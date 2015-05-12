<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if (!defined('YIT')) {exit('Direct access forbidden.');
}

/**
 *
 *
 * @class YIT_Widget_Quick_Contact
 * @package	Yithemes
 * @since Version 2.0.0
 * @author Your Inspiration Themes
 *
 */
if( !class_exists( 'YIT_Widget_Quick_Contact' ) ) :
class YIT_Widget_Quick_Contact extends WP_Widget {

    public $woo_widget_cssclass;
    public $woo_widget_description;
    public $woo_widget_idbase;
    public $woo_widget_name;
    public $woo_widget_button_style;


    /**
     * constructor
     *
     * @access public
     * @return void
     */
    public function YIT_Widget_Quick_Contact() {

        /* Widget variable settings. */
        $this->woo_widget_cssclass 		= 'yit_quick_contact';
        $this->woo_widget_description 	= __('widget wiht quick contact form', 'yit' );
        $this->woo_widget_idbase 		= 'yit_quick_contact';
        $this->woo_widget_name 			=__( 'Quick Contact Form', 'yit' );

        $widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

        /* Create the widget. */
        $this->WP_Widget( 'yit_quick_contact', $this->woo_widget_name, $widget_ops );

    }

    /**
     * widget function.
     *
     * @see WP_Widget
     * @access public
     * @param array $args
     * @param array $instance
     * @return void
     */
    public function widget( $args, $instance ) {
        extract( $args );

        /* User-selected settings. */
        $title = apply_filters('widget_title', $instance['title'] );
        $text = wpautop( $instance['text'] );

        echo $before_widget;

        echo '<div class="contact_form_wrapper">';
        if ( $title ) echo $before_title . $title . $after_title;
        if ( $text ) echo $text;
        echo do_shortcode( '[contact_form name="' . $instance['id_form'] . '"]' );
        echo '</div>';

        echo $after_widget;
    }

    /**
     * update function.
     *
     * @see WP_Widget->update
     * @access public
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['text']  = strip_tags( $new_instance['text'] );

        $instance['id_form']      = $new_instance['id_form'];

        return $instance;
    }

    /**
     * form function.
     *
     * @see WP_Widget->form
     * @access public
     * @param array $instance
     * @return void
     */
    public function form( $instance ) {
        $defaults = array(
            'title'        => __( 'Quick Contact', 'yit' ),
            'text'         => __( 'Need a quick reply to your questions? Fill the form, we will reply in max 24h.', 'yit' ),
            'id_form'      => '',
            'button_style' => 'btn btn-flat'
        );

        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'yit' ) ?>:
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
            </label>
        </p>


        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text', 'yit' ) ?>:
                <textarea id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" class="widefat"><?php echo $instance['text']; ?></textarea>
            </label>
        </p>

        <p>
            <?php _e( 'Select here the form that you have created and configurated on Theme Options panel.', 'yit' ) ?>
        </p>


        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'label_name' ) ); ?>"><?php _e( 'Contact Form ', 'yit' ) ?>:
                <select id="<?php echo esc_attr( $this->get_field_id( 'id_form' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'id_form' ) ); ?>">
                    <?php
                    $forms = yit_get_contact_forms();

                    foreach( $forms as $id_form => $form ) : ?>
                        <option value="<?php echo esc_attr( $id_form ) ?>" <?php selected( $instance['id_form'], $id_form ) ?>><?php echo $form ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>

    <?php
    }

}
endif;