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

if ( ! class_exists( 'YIT_Call_Action' ) ) :
    class YIT_Call_Action extends WP_Widget {
        function YIT_Call_Action() {
            $widget_ops = array(
                'classname'   => 'yit_call_action',
                'description' => __( 'Add a call to action phone to the sidebar', 'yit' )
            );

            $control_ops = array( 'id_base' => 'yit_call_action', 'width' => 430 );

            $this->WP_Widget( 'yit_call_action', __( 'YIT Call to Action Phone', 'yit' ), $widget_ops, $control_ops );

        }

        function form( $instance ) {
            global $icons_name;

            $defaults = array(
                'title' => '',
                'phone' => '',
                'icon_type' => 'icon',
                'text' => '',
                'class' => '',
            );

            $icon_list = YIT_Plugin_Common::get_icon_list();

            $instance = wp_parse_args( (array) $instance, $defaults );
            $current_icon = ( isset( $instance['icon']) ) ? YIT_Icon()->get_icon_data( $instance['icon'] ) : '';

            ?>

            <p>
                <label for="<?php esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'yit' ) ?></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
            </p>

            <p>
                <label for="<?php esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php _e( 'Phone', 'yit' ) ?></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" value="<?php echo esc_attr( $instance['phone'] ); ?>" />
            </p>

             <p>
                <label for="<?php esc_attr( $this->get_field_id( 'class' ) ); ?>"><?php _e( 'Extra Class', 'yit' ) ?></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'class' ) ); ?>" value="<?php echo esc_attr( $instance['class'] ); ?>" />
            </p>

            <div class="widget-icon-manager">
                <div class="icon-manager-wrapper">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ) ?>"><?php _e( 'Icon', 'yit' ) ?></label>
                    <div class="icon-manager-text">
                        <div class="icon-preview" <?php echo $current_icon ?>></div>
                        <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" class="icon-text" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" value="<?php echo esc_attr( $instance['icon'] ); ?>" />
                    </div>


                    <div class="icon-manager">
                        <ul class="icon-list-wrapper">
                            <?php foreach ( $icon_list as $font => $icons ):
                                foreach ( $icons as $key => $icon ): ?>
                                    <li data-font="<?php echo $font ?>" data-icon="<?php echo ( strpos( $key , '\\') === 0 ) ? '&#x' . substr( $key , 1 ) . ';' : $key  ?>" data-key="<?php echo $key ?>" data-name="<?php echo $icon ?>"></li>
                                <?php
                                endforeach;
                            endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text ( you can use html )', 'yit' ); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" cols="20" rows="16"><?php echo esc_attr( $instance['text'] ); ?></textarea>
            </p>

        <?php
        }

        function widget( $args, $instance ) {
            extract( $args );

            $title = apply_filters( 'widget_title', $instance['title'] );
            $text = ( isset( $instance['text'] ) && $instance['text'] != '' ) ? do_shortcode( $instance['text'] ) : '';
            $class = ( isset( $instance['class'] ) && $instance['class'] != '' ) ?  $instance['class'] : '';

            echo $before_widget;

            echo do_shortcode( '[call title="' . $title. '" phone="'. $instance['phone'] .'" class="call-to-action '. $class .'" icon_theme="'. $instance['icon'] .'"]'. $text .'[/call]');

            echo $after_widget;

        }

        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;

            $instance['title']          = strip_tags( $new_instance['title'] );
            $instance['phone']          = strip_tags( $new_instance['phone'] );
            $instance['class']          = strip_tags( $new_instance['class'] );
            $instance['icon']           = $new_instance['icon'];
            $instance['text']           = $new_instance['text'];

            return $instance;
        }

    }
endif;