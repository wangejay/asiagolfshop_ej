<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

$team           = ( ! empty( $team ) ) ? $team : false;
$nitems         = ( isset( $nitems ) ) ? intval( $nitems ) : - 1;
$show_role      = ( isset( $show_role ) && $show_role == 'yes' ) ? true : false;
$show_social    = ( isset( $show_social ) && $show_social == 'yes' ) ? true : false;
$socials        = array( 'envelope-o','facebook', 'twitter', 'google-plus', 'pinterest', 'instagram' );
$socials_title  = apply_filters( 'yit_team_section_social_title_before', __( "I'm on ", 'yit' ) );

$item_span          = 6;    //  single team member box span
$index              = 0;
$item_per_column    = 2;    //  if a sidebar is shown in both side, we force the layout to show 1 item per row

$sidebars = YIT_Layout()->sidebars;
if ( isset ( $sidebars ) ){
	$page_layout = $sidebars['layout'];
	if (isset ($page_layout) && ( 'sidebar-double' == $page_layout)){
		$item_per_column = 1;
		$item_span = 12;
	}
}


if ( $team !== false ):
	$team_members = get_posts( array(
		'post_type'      => $post_type,
		'posts_per_page' => $nitems
	) );

	if ( ! empty( $team_members ) ):
		?>

		<div class="team-section">
		<?php foreach ( $team_members as $member ): ?>
		<?php if ( ( $item_per_column == 1 ) || ( 0 == $index % $item_per_column ) ) : ?>
			<div class="row">
		<?php endif; ?>
		<div class="col-md-<?php echo $item_span; ?> team-author-box">
			<div class="team-author-innerbox team-clearfix">
				<div class="team-member-identity col-sm-5 col-xs-5">
					<?php if ( has_post_thumbnail( $member->ID ) && ! is_rtl() ): ?>
						<div class="team-thumb">
							<?php
							if ( function_exists( 'yit_image' ) ) {
								yit_image( array(
									'post_id' => $member->ID,
									'size'    => 'thumb_team_big',
									'class'   => 'img-responsive'
								) );
							} else {
								echo get_the_post_thumbnail( $member->ID, 'thumb_team_big' );
							}
							?>
						</div>
					<?php endif; ?>
					<div class="team-member-name">
						<?php echo yit_get_highlighted_title( $member->post_title, 'team-member-name-highlight' ); ?>
					</div>
				</div>
				<div class="team-member-info col-sm-7 col-xs-7">
					<div class="team-member-description">
                       <?php
                            if( $show_role ){
                                $role = get_post_meta( $member->ID, '_member_role', true );
                                if( ! empty( $role ) ){
                                    echo ' <div class="team-member-role">'.esc_attr( $role ).'</div>';
                                }
                            }
                            ?>
						<p><?php echo $member->post_content ?></p>
					</div>
					<?php if ( $show_social ): ?>
						<div class="team-member-social">
							<?php foreach ( $socials as $social ) {
								$curr_social = get_post_meta( $member->ID, '_' . $social, true );
								if ( $curr_social != '' ) {
                                    $social_name = ( 'google-plus' == $social ) ? 'google+' : $social;
									echo do_shortcode( '[social icon_size="18"  circle_size="25" circle_border_size="1" href="' . $curr_social . '" title="' . $socials_title . $social_name . '" icon_type="icon" icon_social="' . $social . '" circle="no" target="" ]' );
								}
							}
							?>
						</div>
					<?php endif ?>
				</div>

			</div>
		</div>
		<?php if ( ( $item_per_column == 1 ) || ( 1 == $index % $item_per_column ) ) : ?>
			</div>
		<?php endif; ?>
		<?php $index ++; ?>
	<?php endforeach; ?>
		</div>
	<?php
	endif;
endif;
?>