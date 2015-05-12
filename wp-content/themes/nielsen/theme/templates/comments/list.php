<?php
/**
 * Your Inspiration Themes
 *
 * In this files there is a collection of a functions useful for the core
 * of the framework.
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
?>
<!-- START COMMENTS -->
<li class="comment border-2 clearfix" id="comment-<?php echo $comment->comment_ID;?>">
     <?php if( $is_author ) : ?>
        <span class="is_author">
            <?php _e( 'Author', 'yit' ) ?>
        </span>
    <?php endif; ?>

    <div class="information col-sm-3">
        <span class="<?php echo esc_attr( $avatar_class ) ?>">
            <?php echo get_avatar( $comment_author_gravatar_mail, 60 ); ?>
        </span>

        <span class="user-info">
            <?php echo $comment->comment_author ?>
            <span class="date"><?php echo date('F d, Y', strtotime( $comment->comment_date ) ) ?></span>
        </span>
    </div>

    <div class="content col-sm-9">
        <?php echo $comment->comment_content; ?>
    </div>
    <?php if( comments_open() ) : ?>
        <span class="reply_link">
                <?php comment_reply_link( $args, $comment->comment_ID, $comment->comment_post_ID )  ?>
            </span>
    <?php endif; ?>
</li>
<!-- END COMMENTS -->