<?php global $product; ?>
<li>
    <a class="clearfix" href="<?php echo get_permalink( $product->id ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
        <?php echo $product->get_image(); ?>
        <span class="product_title"><?php echo $product->get_title(); ?></span>
        <span class="product_price"><?php echo $product->get_price_html(); ?></span>
        <?php if ( !empty( $show_rating ) ) {
            echo $product->get_rating_html();
        } ?>
    </a>
</li>