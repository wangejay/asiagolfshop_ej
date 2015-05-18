<?php
$auc_post = get_post($_GET["ult_auc_id"]);

if($auc_post){
$auction_author_id = $auc_post->post_author;
$auction_author = new WP_User($auction_author_id);
$seller_name = $auction_author->user_login;
$e_count = 0;
$reviews = array();

if(is_user_logged_in()){

$curr_user = wp_get_current_user();

if(isset($_POST['ua_seller_review_title'])){

if(!empty($_POST['ua_seller_review_title'])){

if(isset($_POST['wdm_ua_s_rev']) && wp_verify_nonce($_POST['wdm_ua_s_rev'],'wdm_ua_s_rt')){
    
    $exists = get_user_meta($curr_user->ID, 'wdm_review_'.$auction_author_id, true);
    
    if($auction_author_id == get_current_user_id()){
        echo '<div class="wdm_auc_user_notice_err">'.__('Sorry, you can not submit a review for yourself.', 'wdm-ultimate-auction').'</div><br />';  
    }
    elseif($exists === "yes"){
        echo '<div class="wdm_auc_user_notice_err">'.__('You have already submitted a review for this seller.', 'wdm-ultimate-auction').'</div><br />';
    }
    else{
        $r_rate = !empty($_POST['wdm_ua_star_rate']) ? $_POST['wdm_ua_star_rate'] : 5;
        $r_title = !empty($_POST['ua_seller_review_title']) ? $_POST['ua_seller_review_title'] : '';
        $r_content = !empty($_POST['ua_seller_review_content']) ? $_POST['ua_seller_review_content'] : '';
        $review = array('r' =>  $r_rate, 't' => $r_title, 'd' => $r_content, 'u' => $curr_user->user_login, 'a' => $_GET["ult_auc_id"]);
        
        add_user_meta($auction_author_id, 'wdm_seller_review', $review);
        update_user_meta($curr_user->ID, 'wdm_review_'.$auction_author_id, "yes");
        
        echo '<div class="wdm_auc_user_notice_suc">'.__('Thank you for submitting your review.', 'wdm-ultimate-auction').'</div><br />';
    }
    
    }
else{
    die(__('Sorry, your nonce did not verify.', 'wdm-ultimate-auction'));
}
}
else{
    echo '<div class="wdm_auc_user_notice_err">'.__('Please enter review title.', 'wdm-ultimate-auction').'</div><br />';
}
}

$reviews = get_user_meta($auction_author_id, 'wdm_seller_review', false);

$e_tot = 0;
$e_cnt = 0;
$e_count = 0;

foreach( $reviews as $rvs ){
    $e_tot = $e_tot + $rvs['r'];
    $e_cnt++;
}

$e_count = $e_cnt;

if($e_cnt == 0)
    $e_cnt = 1;
    
$e_avg = ($e_tot/$e_cnt);

$e_avg = round($e_avg, 1);

$on_wdt = ($e_avg * 17);
//$off_wdt = (85 - $on_wdt);

echo "<div class='ua_review_page_title'>";
echo '<span class="ua_rpt">'.__('Review for', 'wdm-ultimate-auction').'</span>';
echo '<span class="ua_rpt ua_rpt_b">'.$seller_name.'</span>';
echo '<div class="ua_rpt ua_avg_rate_stars"><div class="ua_avg_rate_star" style="width: '.$on_wdt.'px;"></div><div class="ua_avg_rate_star_off"></div></div>';
echo '<span class="ua_rpt ua_rpt_b">'.sprintf('('._n('%s review', '%s reviews', $e_count, 'wdm-ultimate-auction'), $e_count).')</span>';
echo "</div>";

echo "<br /><br /><form name='ua_seller_review' id='ua_seller_review' action='' method='post'>";
echo "<br /><label>".__("Submit a review", "wdm-ultimate-auction")."</label>:<br /><br />";
echo "<label for='wdm_ua_star_rating'>".__('Rating', 'wdm-ultimate-auction')."</label><div id='wdm_ua_star_rating' class='wdm_ua_star_ratings'>";
echo "<input type='radio' class='wdm_ua_star_rate' data-rate='1' name='wdm_ua_star_rate' value='1' />";
echo "<input type='radio' class='wdm_ua_star_rate' data-rate='2' name='wdm_ua_star_rate' value='2' />";
echo "<input type='radio' class='wdm_ua_star_rate' data-rate='3' name='wdm_ua_star_rate' value='3' />";
echo "<input type='radio' class='wdm_ua_star_rate' data-rate='4' name='wdm_ua_star_rate' value='4' />";
echo "<input type='radio' class='wdm_ua_star_rate' data-rate='5' name='wdm_ua_star_rate' value='5' checked />";
echo "</div><div id='wdm_ua_star_rating_off' class='wdm_ua_star_ratings_off'></div><br /><br />";
echo "<input type='hidden' id='wdm_ua_temp_star' value='' />";
echo "<label for='ua_seller_review_title'>".__('Title', 'wdm-ultimate-auction')."</label><input type='text' id='ua_seller_review_title' name='ua_seller_review_title' class='wdm_ua_seller_review' value='' /><br />";

echo "<label for='ua_seller_review_content'>".__('Your Review', 'wdm-ultimate-auction')."</label>";

$ar = array(
			    'media_buttons' => false,
			    'textarea_name' => 'ua_seller_review_content',
			    'textarea_rows' => 10,
                            'tinymce' => false,
                            'editor_class' => 'wdm_ua_seller_review'
		    );

wp_editor("", 'ua_seller_review_content', $ar);

echo "<br />";
echo wp_nonce_field('wdm_ua_s_rt', 'wdm_ua_s_rev');
echo "<input type='submit' class='wdm-ua-submit' value='".__('Submit Review', 'wdm-ultimate-auction')."' id='ua_seller_review_submit' />";
echo "</form>";
}
else{
    _e('You must be logged in to post your review', 'wdm-ultimate-auction');
    echo ' <a href="'.wp_login_url( $_SERVER['REQUEST_URI']).'">'.__('Log in', 'wdm-ultimate-auction').'</a>';
}

echo "<br /><br />";

echo "<div class='wdm_ua_all_reviews'>";
foreach( array_reverse($reviews) as $rv ){
    echo "<div class='wdm_ua_ind_review'>";
    $rev = get_user_by('user_login', $rv['u']);
    echo "<div class='wdm_ua_rate_avtr'>".get_avatar( $rev->email, 40 )."</div>";
    echo "<div class='wdm_ua_rv_ttl'><strong>".$rv['t']."</strong><br /></div>";
    $on_wd = ($rv['r'] * 17);
    $off_wd = (85 - $on_wd);
    echo "<div class='wdm_ua_rv_rate'><span class='ua_star_u_rating ua_star_u_rating_".$rv['r']."' data-urate='".$rv['r']."' style='width: ".$on_wd."px;'></span><span class='ua_star_u_rating_off ua_star_u_rating_off_".$rv['r']."' data-urate='".$rv['r']."' style='width: ".$off_wd."px;'></span></div>";
    
    echo "<div class='wdm_ua_rv_usr'>".sprintf(__("By %s", "wdm-ultimate-auction"), "<strong>".$rv['u']."</strong> ")."</div>";
    echo "<div class='wdm_ua_rv_desc'>".$rv['d']."</div>";
    echo "</div>";
}
    echo "</div>";
?>
<script type="text/javascript">
    
    jQuery(document).ready(function($){
        
        $('.wdm_ua_star_rate').hover( function(){

            var rt = parseInt($(this).attr('data-rate'));
            var wdt = parseInt(rt * 17);
            
           $(this).parent().css('width', wdt);
           
           $('.wdm_ua_star_ratings_off').css('width', (85 - wdt));
          
            }, function(){
                var tmp_star = parseInt($('#wdm_ua_temp_star').val());
                
                if (!isNaN(tmp_star) && tmp_star > 0) {
                    $('.wdm_ua_star_ratings').css('width', tmp_star);
                    $('.wdm_ua_star_ratings_off').css('width', 85-tmp_star);
                }
                else{
                    $('.wdm_ua_star_ratings_off').css('width', 0);
                    $('.wdm_ua_star_ratings').css('width', 85);
                }
            });
        
            $('.wdm_ua_star_rate').click( function(){
            
            var rt = parseInt($(this).attr('data-rate'));
            var wdt = parseInt(rt * 17);
            $('#wdm_ua_temp_star').val(wdt);
            
           $(this).parent().css('width', wdt);
           
           $('.wdm_ua_star_ratings_off').css('width', (85-wdt));

            });
            
            $('.wdm_ua_star_ratings_off').hover( function(){
                $('.wdm_ua_star_ratings_off').css('width', 0);
                $('.wdm_ua_star_ratings').css('width', 85);
                
            });
        
        });
</script>
<?php } ?>