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

if ( ! class_exists( 'YIT_Text_Image' ) ) :
    class YIT_Text_Image extends WP_Widget {
        function YIT_Text_Image() {
            $widget_ops = array(
                'classname'   => 'yit_text_image',
                'description' => __( 'Arbitrary text or HTML, with a simple image.', 'yit' )
            );

            $control_ops = array( 'id_base' => 'yit_text_image', 'width' => 430 );

            $this->WP_Widget( 'yit_text_image', __( 'YIT Text With Image', 'yit' ), $widget_ops, $control_ops );

        }

        function form( $instance ) {
            global $icons_name;

            /* Impostazioni di default del widget */
            $defaults = array(
                'title' => '',
                'icon_type' => 'icon',
                'icon' => '',
                'image' => '',
                'align' => '',
                'link' => '',
                'text'  => '',
                'autop' => false
            );

            $icon_type_option_list = array(
                'icon'   => __( 'Theme Icon', 'yit' ),
                'custom' => __( 'Custom Icon', 'yit' ),
                'none'   => __( 'None', 'yit' )
            );

            $icon_list = YIT_Plugin_Common::get_icon_list();

            $instance = wp_parse_args( (array) $instance, $defaults );
            $current_icon = YIT_Icon()->get_icon_data( $instance['icon'] );

            ?>

            <p>
                <label for="<?php esc_attr( $this->get_field_id( 'title' ) ) ?>"><?php _e( 'Title', 'yit' ) ?></label>
                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
            </p>

            <p class="widget_select_action">
                <label for="<?php echo esc_attr( $this->get_field_id( 'icon_type' ) ); ?>"><?php _e( 'Icon Type', 'yit' ) ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'icon_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_type' ) ); ?>" class="widget_icon_type">
                    <?php foreach( $icon_type_option_list as $key => $type ): ?>
                        <option value="<?php echo esc_attr( $key ) ?>" <?php selected( $instance['icon_type'], $key ); ?>><?php echo $type ?></option>
                    <?php endforeach; ?>
                </select>
                <span><?php _e( 'Choose the type of icon', 'yit' )?></span>
            </p>

            <div class="widget-icon-manager">
                <div class="icon-manager-wrapper">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php _e( 'Icon', 'yit' ) ?></label>
                    <div class="icon-manager-text">
                        <div class="icon-preview" <?php echo $current_icon ?>></div>
                        <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" class="icon-text" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" value="<?php echo esc_attr( $instance['icon'] ); ?>" />
                    </div>


                    <div class="icon-manager">
                        <ul class="icon-list-wrapper">
                            <?php foreach ( $icon_list as $font => $icons ):
                                foreach ( $icons as $key => $icon ): ?>
                                    <li data-font="<?php echo esc_attr( $font )  ?>" data-icon="<?php echo ( strpos( $key , '\\') === 0 ) ? '&#x'. substr( $key , 1 ) . ';' : $key  ?>" data-key="<?php echo esc_attr( $key ) ?>" data-name="<?php echo esc_attr( $icon ) ?>"></li>
                                <?php
                                endforeach;
                            endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>


            <p class="widget_custom_icon">
                <label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php _e( 'Image', 'yit' ) ?></label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" value="<?php echo esc_attr( $instance['image'] ); ?>" />
                <input type="button" value="Upload" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>-button" class="upload_button button" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"><?php _e( 'Image Alignment', 'yit' ) ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>">
                    <option value="left"<?php selected( $instance['align'], 'left' ); ?>><?php _e( 'Left', 'yit' ) ?></option>
                    <option value="center"<?php selected( $instance['align'], 'center' ); ?>><?php _e( 'Center', 'yit' ) ?></option>
                    <option value="right"<?php selected( $instance['align'], 'right' ); ?>><?php _e( 'Right', 'yit' ) ?></option>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php _e( 'Link Image', 'yit' ) ?></label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" value="<?php echo esc_attr( $instance['link'] ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text ( you can use html )', 'yit' ); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" cols="20" rows="16"><?php echo $instance['text']; ?></textarea>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'autop' ) ); ?>"><?php _e( 'Automatically add paragraphs', 'yit' ) ?></label>
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'autop' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'autop' ) ); ?>" value="1"<?php if ( $instance['autop'] ) { echo 'checked="checked"'; } ?> />
            </p>

        <?php
        }

        function widget( $args, $instance ) {
            extract( $args );

            $title = apply_filters( 'widget_title', $instance['title'] );

            if ( strpos( $before_widget, 'widget-wrap' ) === false ) {
                if ( $title != '' ){
                    $before_widget .= '<div class="clearfix widget-wrap">';
                }
                else{
                    $before_widget .= '<div class="clearfix widget-wrap" style="margin-bottom: 20px;">';
                }
                $after_widget .= '</div>';
            }

            echo $before_widget;

//            if ( $title ) {
//                echo $before_title . $title . $after_title;
//            }

            echo '<div>';

            if ( $instance['autop'] )
            {
                $instance['text'] = wpautop( apply_filters( 'widget_text', $instance['text'] ) );
            }

            $text = '<div class="clearfix widget_image ' . $instance['align'] . '">';

            if( isset( $instance[ 'link' ] ) && $instance['link'] != '' ) {
                $text .= '<a href ="' . esc_url( $instance['link'] ) . '">';
            }

            if( isset( $instance['icon_type'] ) ){
                switch ( $instance['icon_type'] ){
                    case 'icon':
                        if ( isset( $instance['icon'] ) ) {
                            $current_icon = YIT_Icon()->get_icon_data( $instance['icon'] );
                            $text .= '<i ' . $current_icon . ' ></i>';
                        }
                        break;
                    case 'custom':
                        if( isset( $instance['image'] ) && $instance['image'] != '' ){
                            $text .= yit_image( "echo=no&src=". $instance['image'] ."&getimagesize=1&alt=" . $instance['title'] );
                        }
                        break;
                    default:
                }
            }

            if( isset( $instance[ 'link' ] ) && $instance['link'] != '' ) {
                $text .= '</a>';
            }

            $localized_text = function_exists( 'icl_translate' ) ? icl_translate( 'Widgets', 'widget_text_yit_text_image' . sanitize_title( $instance['text'] ), $instance['text'] ) : $instance['text'];

            $text .= '</div><div class="widget_text left">';
            if ( $title ) {
                 $text .= $before_title . $title . $after_title;
            }
             $text .= $localized_text;
//             $text .= '</div><div class="widget_text left">' . $localized_text;
//
            echo do_shortcode( $text );

            echo '</div></div>';

            echo $after_widget;
        }

        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;

            $instance['title'] = strip_tags( $new_instance['title'] );

            $instance['icon_type'] = $new_instance['icon_type'];

            $instance['icon'] = $new_instance['icon'];

            $instance['image'] = $new_instance['image'];

            $instance['align'] = $new_instance['align'];

            $instance['link'] = $new_instance['link'];

            $instance['text'] = $new_instance['text'];

            $instance['autop'] = $new_instance['autop'];

            return $instance;
        }

    }
endif;