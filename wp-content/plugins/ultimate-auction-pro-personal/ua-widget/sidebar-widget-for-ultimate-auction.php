<?php
class WDM_UA_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'wdm_ua_widget', // Base ID
			__('Ultimate Auction', 'wdm-ultimate-auction'), // Name
			array( 'description' => __( 'An Auction Widget', 'wdm-ultimate-auction' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['ua_widget_title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		
		wp_enqueue_style('wdm_widget_css', plugins_url('ua-widget.css', __FILE__));
    
    $options = $instance['wdm_sb_auc_listing'];
    $get_link = $instance['wdm_sb_auc_see_all_link'];
    $max_auctions = $instance['wdm_sb_auc_max_no_auctions'];
    
    if(isset($options) && $options == 'New_Listing'){
    
    $arg_data = array(
		'posts_per_page'=> $max_auctions,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'suppress_filters' => false
		);
    }
    
    if(isset($options) && $options == 'Most_Active'){
    
    $arg_data = array(
		'posts_per_page'=> $max_auctions,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish',
		'meta_key' => 'total_bids_count',
		'orderby' => 'total_bids_count',
		'order' => 'DESC',
		'suppress_filters' => false
		);
    }
    
    if(isset($options) && $options == 'Ending_Soon'){
    
    $arg_data = array(
		'posts_per_page'=> $max_auctions,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish',
		'meta_key' => 'wdm_ending_soon',
		'orderby' => 'wdm_ending_soon',
		'order' => 'ASC',
		'suppress_filters' => false
		);
    }
    
    if(isset($options) && $options == 'Just_Sold'){
    
    $arg_data = array(
		'posts_per_page'=> $max_auctions,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'expired',
		'post_status' => 'publish',
		'meta_key' => 'wdm_listing_ends',
		'orderby' => 'wdm_listing_ends',
		'order' => 'DESC',
		'suppress_filters' => false
		);
    }
    
    $wdm_auction_array = get_posts($arg_data);
    
    if(empty($wdm_auction_array)){
		 
	    echo "<div class='wdm-auction-sidebar_single-item'> ".__('No Entries yet.','wdm-ultimate-auction')."</div>";
	}
		    
    else{
		?>
		<aside class = "wdm_auction_sidebar_content">
		    
		<?php
		$auction_count = 0;
		    foreach($wdm_auction_array as $wdm_single_auction){
			if($auction_count != $max_auctions) {
	    
			global $wpdb;
			?>
			 <div class="wdm-single-auction-wrap">
			    <div class = "wdm_auction_sidebar_auc_thumbnail">
				<?php $vid_arr = array('mpg', 'mpeg', 'avi', 'mov', 'wmv', 'wma', 'mp4', '3gp', 'ogm', 'mkv', 'flv');
					$auc_thumb = get_post_meta($wdm_single_auction->ID, 'wdm_auction_thumb', true);
					$imgMime = wdm_get_mime_type($auc_thumb); 
					$img_ext = end(explode(".",$auc_thumb));
					
					if(strstr($imgMime, "video/") || in_array($img_ext, $vid_arr) || strstr($auc_thumb, "youtube.com") || strstr($auc_thumb, "vimeo.com")){
					$auc_thumb = plugins_url('../img/film.png', __FILE__);	
				}
				if(empty($auc_thumb)){$auc_thumb = plugins_url('../img/no-pic.jpg', __FILE__);}
				?>
				<?php if(!empty($get_link)){ ?>
				<a href="<?php echo add_query_arg('ult_auc_id', $wdm_single_auction->ID, $get_link); ?>" class="wdm-auction-sidebar-auc-link"><img src="<?php echo $auc_thumb; ?>" width="50" height="50" alt="<?php echo $wdm_single_auction->post_title; ?>" /></a>
				<?php } ?>
			    </div>
			    <div class="wdm-auction-content">
			    <div class = "wdm_auction_sidebar_auc_title">
				<?php if(!empty($get_link)){ ?>
				<a href="<?php echo add_query_arg('ult_auc_id', $wdm_single_auction->ID, $get_link); ?>" class="wdm-auction-sidebar-auc-link"><?php echo $wdm_single_auction->post_title; ?></a>
				<?php } ?>
			    </div>
			     <div class = "wdm_auction_sidebar_auc_bids">
				<?php
				    $query="SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$wdm_single_auction->ID;
				    $curr_price = $wpdb->get_var($query);
				    $cc = substr(get_option('wdm_currency'), -3);
				    $ob = get_post_meta($wdm_single_auction->ID, 'wdm_opening_bid', true);
				    $bnp = get_post_meta($wdm_single_auction->ID, 'wdm_buy_it_now', true);
				    
				    if((!empty($curr_price) || $curr_price > 0) && !empty($ob))
					    echo $cc ." ". sprintf("%.2f", $curr_price);
				    elseif(!empty($ob))
					    echo $cc ." ".sprintf("%.2f", $ob);
				    elseif(empty($ob) && !empty($bnp))
					    printf(__('Buy at %s %.2f', 'wdm-ultimate-auction'), $cc, $bnp);
				    ?>
					    </div>
			 </div>
			 <div class="clear"></div>
		    </div>
		    <?php
		    $auction_count++;
			}
		    } ?>
		    
		    <?php if(!empty($get_link)){ ?>
		    <a href="<?php echo $get_link ;?>" class = "wdm-auction-sb-listing-all" target="_blank"> <?php _e('See All', 'wdm-ultimate-auction'); ?></a>
		<?php } ?>
		
		    </aside>
			 
		
		
	    <?php
}
		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'ua_widget_title' ] ) ) {
			$title = $instance[ 'ua_widget_title' ];
		}
		else {
			$title = __( 'Auctions', 'wdm-ultimate-auction' );
		}
		
		if ( isset( $instance[ 'wdm_sb_auc_listing' ] ) ) {
			$listing = $instance[ 'wdm_sb_auc_listing' ];
		}
		else {
			$listing = 'New_Listing';
		}
		
		if ( isset( $instance[ 'wdm_sb_auc_see_all_link' ] ) ) {
			$see_all_link = $instance[ 'wdm_sb_auc_see_all_link' ];
		}
		
		if ( isset( $instance[ 'wdm_sb_auc_max_no_auctions' ] ) ) {
			$count = $instance[ 'wdm_sb_auc_max_no_auctions' ];
		}
		else {
			$count = 3;
		}
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'ua_widget_title' ); ?>"><?php echo __( 'Title', 'wdm-ultimate-auction' ).":"; ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'ua_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'ua_widget_title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'wdm_sb_auc_listing' ); ?>"><?php echo __( 'Listings', 'wdm-ultimate-auction' ).":"; ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'wdm_sb_auc_listing' ); ?>" name="<?php echo $this->get_field_name( 'wdm_sb_auc_listing' ); ?>">
          <option value='New_Listing' <?php if($listing =='New_Listing'){echo 'selected="selected"';} ?>> <?php _e('New Listing', 'wdm-ultimate-auction') ?></option>
          <option value='Most_Active'<?php if($listing =='Most_Active'){echo 'selected="selected"';} ?>> <?php _e('Most Active', 'wdm-ultimate-auction') ?></option> 
          <option value='Ending_Soon'<?php if($listing =='Ending_Soon'){echo 'selected="selected"';} ?>><?php _e('Ending Soon', 'wdm-ultimate-auction') ?></option>
	  <option value='Just_Sold'<?php if($listing =='Just_Sold'){echo 'selected="selected"';} ?>><?php _e('Just Sold', 'wdm-ultimate-auction') ?></option> 
    </select>    
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'wdm_sb_auc_see_all_link' ); ?>"><?php echo __( "'See All' URL", "wdm-ultimate-auction" ).":"; ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'wdm_sb_auc_see_all_link' ); ?>" name="<?php echo $this->get_field_name( 'wdm_sb_auc_see_all_link' ); ?>" type="text" placeholder = "<?php _e('Enter your feed page URL', 'wdm-ultimate-auction');?>" value="<?php echo esc_attr( $see_all_link ); ?>">
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'wdm_sb_auc_max_no_auctions' ); ?>"><?php echo __( 'Maximum no. of auctions to show' , 'wdm-ultimate-auction').":"; ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'wdm_sb_auc_max_no_auctions' ); ?>" name="<?php echo $this->get_field_name( 'wdm_sb_auc_max_no_auctions' ); ?>" type="number" min="0" value="<?php echo esc_attr( $count ); ?>">
		</p>
		
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['ua_widget_title'] = ( ! empty( $new_instance['ua_widget_title'] ) ) ? strip_tags( $new_instance['ua_widget_title'] ) : '';
		$instance['wdm_sb_auc_listing'] = ( ! empty( $new_instance['wdm_sb_auc_listing'] ) ) ? strip_tags( $new_instance['wdm_sb_auc_listing'] ) : '';
		$instance['wdm_sb_auc_see_all_link'] = ( ! empty( $new_instance['wdm_sb_auc_see_all_link'] ) ) ? strip_tags( $new_instance['wdm_sb_auc_see_all_link'] ) : '';
		$instance['wdm_sb_auc_max_no_auctions'] = ( ! empty( $new_instance['wdm_sb_auc_max_no_auctions'] ) ) ? strip_tags( $new_instance['wdm_sb_auc_max_no_auctions'] ) : '';

		return $instance;
	}

} // class WDM_UA_Widget

// register WDM_UA_Widget widget
function register_wdm_ua_widget() {
    register_widget( 'WDM_UA_Widget' );
}
add_action( 'widgets_init', 'register_wdm_ua_widget' );
