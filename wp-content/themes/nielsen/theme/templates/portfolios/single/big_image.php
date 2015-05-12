<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

global $post;
$terms                      = $portfolio->terms_list( ', ' );
$previous_post              = get_adjacent_post( false, '', true );
$next_post                  = get_adjacent_post( false, '', false );
$previous_post_terms        = is_object( $previous_post ) ? yit_get_terms_list_by_id( $previous_post->ID,$taxonomy ) : false;
$next_post_terms            = is_object( $next_post )     ? yit_get_terms_list_by_id( $next_post->ID, $taxonomy ) : false;
$post_attachment_id         = get_post_thumbnail_id();
$featured_image_size        = apply_filters( 'yit_portfolio_single_image_size', 'portfolio_single' );
$featured_image_size_thumb  = apply_filters( 'yit_portfolio_single_image_size_thumb', 'portfolio_single_thumb' );
$post_featured_image_args   = wp_get_attachment_image_src( $post_attachment_id, $featured_image_size );
$post_featured_image        = $post_featured_image_args[0];
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio_' . $layout ) ?> >
    <div class="meta clearfix portfolio single">
        <div id="portfolio_content" class="row">
            <?php if ( has_post_thumbnail() || ! empty( $attachments ) ) : ?>
                <div class="col-sm-8">
                    <div class="row">
                        <div id="portfolio-big-image-wrap" class="portfolio-single-featured col-sm-12">
                            <?php echo $portfolio->get_image( $featured_image_size, array( 'class' => 'img-responsive border' ) ); ?>
                        </div>
                        <?php if( ! empty( $attachments ) ) : ?>
                            <ul class="portfolio-single-attachment">
                                <li class="portfolio-single-thumb active" data-image-url="<?php echo esc_url( $post_featured_image ) ?>">
                                    <?php echo $portfolio->get_image( $featured_image_size_thumb, array( 'class' => 'img-responsive' ) ); ?>
                                </li>
                                <?php foreach ( $attachments as $key => $attachment ) : ?>
                                    <?php $image_big = yit_image( "id=$attachment->ID&size=$featured_image_size&class=img-responsive&echo=false&output=url" ); ?>
                                    <li class="portfolio-single-thumb" data-image-url="<?php echo esc_url( $image_big ) ?>">
                                        <?php yit_image( "id=$attachment->ID&size=$featured_image_size_thumb&class=img-responsive" ); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-sm-<?php echo has_post_thumbnail() || ! empty( $attachments ) ? '4' : 12 ?> ">
                <div class="title">
                    <h1 class="portfolios-title"><?php the_title() ?></h1>
                    <div id="portfolio_nav">
                        <!-- PREV -->
                        <?php if( ! empty( $previous_post ) ) : ?>
                            <a class="border a-style-2" href="<?php echo get_permalink( $previous_post->ID ); ?>" rel="prev">
                                <span data-icon="&#xe012;" data-font="retinaicon-font"></span>
                                <div class="prev-post general-background-color">
                                    <?php echo get_the_post_thumbnail( $previous_post->ID, 'portfolio_nav', array( 'class' => 'img-responsive portfolio_thumb' ) );; ?>
                                    <div class="info shade-2">
                                        <h5 class="prev_title"><?php echo $previous_post->post_title ?></h5>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>

                        <!-- NEXT -->
                        <?php if( ! empty( $next_post ) ) : ?>
                            <a class="border a-style-2" href="<?php echo get_permalink( $next_post->ID ); ?>" rel="next">
                                <span data-icon="&#xe013;" data-font="retinaicon-font"></span>
                                <div class="next-post general-background-color">
                                    <?php echo get_the_post_thumbnail( $next_post->ID, 'portfolio_nav', array( 'class' => 'img-responsive portfolio_thumb' ) ); ?>
                                    <div class="info shade-2">
                                        <h5 class="next_title"><?php echo $next_post->post_title ?></h5>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if ( $enable_categories ) : ?>
                        <div class="categories">
                            <?php echo $terms ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if( $enable_extra_info ) : ?>
                    <?php yit_get_template( 'portfolios/common/extra_info.php', $extra_info_variables ); ?>
                <?php endif; ?>
                <div class="the_content">
                    <?php the_content() ?>
                </div>

                <?php if( $show_share ) : ?>
                    <div class="socials-wrap border">
                        <?php if( !empty( $share_title ) ) : ?>
                            <h3 class="share-section-title" >
                                <?php echo $share_title ?>
                            </h3>
                        <?php endif; ?>
                        <?php yit_get_social_share( 'icon', 'portfolio-share content-style-social', $share_socials )?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
