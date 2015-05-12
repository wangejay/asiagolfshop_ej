<?php
/**
 * Your Inspiration Themes
 *
 * @package WordPress
 * @subpackage Your Inspiration Themes
 * @author Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

class YIT_Products extends WP_Widget {
    /**
     * constructor
     *
     * @access public
     * @return void
     */
    function YIT_Products() {

        /* Widget variable settings. */
        $this->woo_widget_idbase = 'yit_products';

        /* Widget settings. */
        $widget_ops = array( 'classname' => 'yit_products', 'description' => __( 'Display a list of random products on your site.', 'yit' ) );

        /* Create the widget. */
        $this->WP_Widget('yit-products', 'YIT Woocommerce Random Products', $widget_ops);
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
    function widget($args, $instance) {
        global $woocommerce;

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

        $query_args = array( 'posts_per_page' => $number, 'orderby' => 'rand', 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product' );

        if ( !empty ( $instance['category'] ) && $instance['category'][0] != '' ) {
            $query_args ['tax_query'] = implode( ',', $instance['category'] );
        }

        $r = new WP_Query($query_args);

        if ($r->have_posts()) : ?>

            <?php echo $before_widget; ?>
            <div class="clearfix widget random-products">
                <?php if ( $title ) echo '<h3>' . $title . '</h3>'; ?>
                <ul class="clearfix products-thumbnails">
                    <?php while ($r->have_posts()) : $r->the_post(); global $product; ?>
                        <li>
                            <?php if( has_post_thumbnail() ) : ?>
                                    <a href="<?php echo esc_url( get_permalink( $r->post->ID ) ); ?>" class="with-tooltip product_img" title="<?php echo esc_attr($r->post->post_title ? $r->post->post_title : $r->post->ID); ?>">
                                        <?php the_post_thumbnail( 'shop_thumbnail' )  ?>
                                    </a>
                            <?php endif ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <?php echo $after_widget; ?>

        <?php endif;

        $content = ob_get_clean();



        if ( isset( $args['widget_id'] ) ) $cache[$args['widget_id']] = $content;

        echo $content;


        wp_reset_postdata();
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
    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['category'] = $new_instance['category'];
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
    function form( $instance ) {
        $defaults = array(
            'title' => '',
            'number' => 6,
            'category' => array('')
        );
        $cats = yit_woocommerce_get_shop_categories(false);
        $instance = wp_parse_args( $instance, $defaults );
        ?>
        <p><label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php _e('Title', 'yit'); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>

        <p><label for="<?php echo esc_attr( $this->get_field_id('number') ); ?>"><?php _e('Number of products to show', 'yit'); ?>:</label>
            <input id="<?php echo esc_attr( $this->get_field_id('number') ); ?>" name="<?php echo esc_attr( $this->get_field_name('number') ); ?>" type="text" value="<?php echo esc_attr( $instance['number'] ); ?>" size="3" /></p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php _e( 'Product Category', 'yit' ) ?>:
                <select id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>[]" multiple="multiple">
                    <option value="" <?php echo ( in_array( '' , $instance['category']  ) ? 'selected' : '' ) ?>><?php echo __('All Categories', 'yit') ?></option>
                    <?php foreach( $cats as $k => $cat ): ?>
                        <option value="<?php echo esc_attr( $k ) ?>" <?php echo ( in_array( $k , $instance['category']  ) ? 'selected' : '' ) ?>><?php echo $cat ?></option>
                    <?php endforeach ?>
                </select>
            </label>
        </p>
    <?php
    }
}