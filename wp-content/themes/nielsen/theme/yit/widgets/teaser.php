<?php
/**
 * Your Inspiration Themes
 *
 * @package    WordPress
 * @subpackage Your Inspiration Themes
 * @author     Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! class_exists( 'teaser' ) ) :
    class teaser extends WP_Widget {
        function teaser() {
            $widget_ops = array(
                'classname'   => 'teaser',
                'description' => __( 'An image with a text linkable', 'yit' )
            );

            $control_ops = array( 'id_base' => 'teaser', 'width' => 430 );

            $this->WP_Widget( 'teaser', __( 'Teaser', 'yit' ), $widget_ops, $control_ops );
        }

        function form( $instance ) {
            global $icons_name;

            /* Impostazioni di default del widget */
            $defaults = array(
                'title'           => '',
                'slogan'          => '',
                'slogan_color'    => '',
                'slogan_size'     => '',
                'subslogan'       => '',
                'subslogan_color' => '',
                'subslogan_size'  => '',
                'slogan_position' => '',
                'image'           => '',
                'link'            => '',
                'button_text'     => '',
                'button_style'    => '',
            );

            $button_styles = yit_button_style();
            $instance = wp_parse_args( (array) $instance, $defaults ); ?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'yit' ) ?></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'slogan' ) ); ?>"><?php _e( 'Slogan', 'yit' ) ?></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'slogan' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slogan' ) ); ?>" value="<?php echo esc_attr( $instance['slogan'] ); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'slogan_color' ) ); ?>"><?php _e( 'Slogan Color hex code', 'yit' ) ?> - <small>i.e. #ffffff</small></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'slogan_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slogan_color' ) ); ?>" value="<?php echo esc_attr( $instance['slogan_color'] ); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'slogan_size' ) ); ?>"><?php _e( 'Slogan Size in px', 'yit' ) ?></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'slogan_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slogan_size' ) ); ?>" value="<?php echo esc_attr( $instance['slogan_size'] ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'subslogan' ) ); ?>"><?php _e( 'Subslogan', 'yit' ) ?></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'subslogan' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subslogan' ) ); ?>" value="<?php echo esc_attr( $instance['subslogan'] ); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'subslogan_color' ) ); ?>"><?php _e( 'Subslogan Color hex code', 'yit' ) ?> - <small>i.e. #ffffff</small></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'subslogan_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subslogan_color' ) ); ?>" value="<?php echo esc_attr( $instance['subslogan_color'] ); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'subslogan_size' ) ); ?>"><?php _e( 'Subslogan Size in px', 'yit' ) ?></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'subslogan_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subslogan_size' ) ); ?>" value="<?php echo esc_attr( $instance['subslogan_size'] ); ?>" />
            </p>


            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'slogan_position' ) ); ?>"><?php _e( 'Slogan Position', 'yit' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'slogan_position' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slogan_position' ) ); ?>">
                    <option value="top" <?php selected( $instance['slogan_position'], 'top' ) ?>><?php _e( 'Top Left', 'yit' ) ?></option>
                    <option value="top center" <?php selected( $instance['slogan_position'], 'top center' ) ?>><?php _e( 'Top center', 'yit' ) ?></option>
                    <option value="center left" <?php selected( $instance['slogan_position'], 'center left' ) ?>><?php _e( 'Center Left', 'yit' ) ?></option>
                    <option value="center" <?php selected( $instance['slogan_position'], 'center' ) ?>><?php _e( 'Center', 'yit' ) ?></option>
                    <option value="bottom" <?php selected( $instance['slogan_position'], 'bottom' ) ?>><?php _e( 'Bottom Left', 'yit' ) ?></option>                 
                    <option value="bottom center" <?php selected( $instance['slogan_position'], 'bottom center' ) ?>><?php _e( 'Bottom center', 'yit' ) ?></option>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php _e( 'Label Button', 'yit' ) ?></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" value="<?php echo esc_attr( $instance['button_text'] ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'button_style' ) ); ?>"><?php _e( 'Button Style', 'yit' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'button_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>">
                    <?php foreach( $button_styles as $key => $value ): ?>
                    <option value="<?php echo esc_attr( $key ) ?>" <?php selected( $instance['button_style'], $key ) ?>><?php echo $value ?></option>
                    <?php endforeach; ?>
                </select>
            </p>


            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php _e( 'Image', 'yit' ) ?></label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" value="<?php echo esc_attr( $instance['image'] ); ?>" />
                <input type="button" value="Upload" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>-button" class="upload_button button" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php _e( 'Link', 'yit' ) ?></label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" value="<?php echo esc_attr( $instance['link'] ); ?>" />
            </p>
        <?php
        }

        function widget( $args, $instance ) {
            extract( $args );

            $title = apply_filters( 'widget_title', $instance['title'] );

            if ( strpos( $before_widget, 'widget-wrap' ) === false ) {
                $before_widget .= '<div class="widget-wrap">';
                $after_widget .= '</div>';
            }

            echo $before_widget;

            if ( isset( $title ) && $title != '' ) {
                echo $before_title . $title . $after_title;
            }

            echo do_shortcode( '[teaser title_color="' . $instance['slogan_color'] . '" title_size="' . $instance['slogan_size'] . '" subtitle_color="' . $instance['subslogan_color'] . '" subtitle_size="' . $instance['subslogan_size'] . '" title="' . $instance['slogan'] . '" subtitle="' . $instance['subslogan'] . '" image="' . $instance['image'] . '" link="' . $instance['link'] . '" button="' . $instance['button_text'] . '" button_style="' .$instance['button_style'] . '" slogan_position="' . $instance['slogan_position'] . '" ]' );

            echo $after_widget;
        }

        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;

            $instance['title'] = strip_tags( $new_instance['title'] );

            $instance['slogan'] = strip_tags( $new_instance['slogan'] );

            $instance['slogan_color'] = strip_tags( $new_instance['slogan_color'] );

            $instance['slogan_size'] = strip_tags( $new_instance['slogan_size'] );

            $instance['subslogan'] = strip_tags( $new_instance['subslogan'] );

            $instance['subslogan_color'] = strip_tags( $new_instance['subslogan_color'] );

            $instance['subslogan_size'] = strip_tags( $new_instance['subslogan_size'] );

            $instance['slogan_position'] = strip_tags( $new_instance['slogan_position'] );

            $instance['image'] = $new_instance['image'];

            $instance['link'] = esc_url( $new_instance['link'] );

            $instance['button_text'] =$new_instance['button_text'];

            $instance['button_style'] =$new_instance['button_style'];

            return $instance;
        }

    }
endif;