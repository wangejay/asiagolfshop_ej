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

if ( class_exists( 'YIT_Portfolio' ) && !class_exists( 'YIT_Widget_Portfolio' ) ) {

    class YIT_Widget_Portfolio extends WP_Widget {
        /**
         * constructor
         *
         * @access public
         * @return void
         */
        function YIT_Widget_Portfolio() {


            /* Widget settings. */
            $widget_ops = array( 'classname' => 'yit_portfolio', 'description' => __( 'Display a list of random items of your portfolio.', 'yit' ) );

            /* Create the widget. */
            $this->WP_Widget( 'yit-portfolio', 'YIT Portfolio', $widget_ops );
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

            ob_start();
            extract( $args );

            $title = apply_filters( 'widget_title', $instance['title'] );
            if ( !$number = (int) $instance['number'] ) {
                $number = 6;
            }
            else {
                if ( $number < 1 ) {
                    $number = 3;
                }
                else {
                    if ( $number > 15 ) {
                        $number = 15;
                    }
                }
            }

            $current_portfolio = $instance['portfolio'];
            $portfolio         = YIT_Portfolio()->get_portfolio( $current_portfolio );
            $portfolio->init_query();

            if ( $portfolio->have_posts() ) : ?>

                <?php echo $before_widget; ?>
                <div class="clearfix widget random-prjects">
                    <?php if ( $title ) {
                        echo '<h3>' . $title . '</h3>';
                    } ?>

                    <ul class="clearfix products-thumbnails">
                        <?php while ( $portfolio->have_posts() ) : $portfolio->the_post();
                            $image = $portfolio->get_image( 'portfolio_thumb', array( 'class' => 'img-responsive' ) );
                            if ( strcmp( $image, '' ) != 0 ) :
                                ?>
                                <li>
                                    <a href="<?php echo $portfolio->get( 'permalink' ); ?>" class="with-tooltip project_img" title="<?php echo esc_attr( $portfolio->get( 'title' ) ); ?>">
                                        <?php echo $image; ?>
                                    </a>
                                </li>
                            <?php endif ?>
                        <?php endwhile; ?>
                    </ul>
                </div>
                <?php echo $after_widget; ?>

            <?php endif;

            $content = ob_get_clean();


            if ( isset( $args['widget_id'] ) ) {
                $cache[$args['widget_id']] = $content;
            }

            echo $content;


            wp_reset_postdata();
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

            $instance              = $old_instance;
            $instance['title']     = strip_tags( $new_instance['title'] );
            $instance['number']    = (int) $new_instance['number'];
            $instance['portfolio'] = $new_instance['portfolio'];
            return $instance;
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
            $portfolios = yit_get_portfolios();
            if ( empty ( $portfolios ) ): ?>
                <p>Before adding this widget, you should create a new portfolio</p>
            <?php
            else:
                $defaults = array(
                    'title'     => '',
                    'number'    => 6,
                    'portfolio' => reset( $portfolios )
                );
                $instance = wp_parse_args( $instance, $defaults );
                ?>
                <p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'yit' ); ?>:</label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of products to show', 'yit' ); ?>:</label>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['number'] ); ?>" size="3" />
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'portfolio' ) ); ?>"><?php _e( 'Portfolio', 'yit' ) ?>:
                        <select id="<?php echo esc_attr( $this->get_field_id( 'portfolio' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'portfolio' ) ); ?>">
                            <?php foreach ( $portfolios as $k => $name ): ?>
                                <option value="<?php echo esc_attr( $k ) ?>" <?php echo selected( $instance['portfolio'], $k ) ?>><?php echo $name ?></option>
                            <?php endforeach ?>
                        </select>
                    </label>
                </p>
            <?php
            endif;
        }
    }
}