jQuery(document).ready(function($){
    
$('#bx-pager').bxSlider({
  minSlides: 4,
  maxSlides: 4,
  moveSlides: 2,
  slideWidth: 70,
  slideMargin: 5,
  infiniteLoop: false,
  hideControlOnEnd: true
});

var wdm_ssnum;

if (typeof ua_ss_num === 'undefined') {
  wdm_ssnum = 0;
}
else{
  wdm_ssnum = ua_ss_num.ssn;
}

$('.bxslider').bxSlider({
  pagerCustom: '#bx-pager',
  startSlide: wdm_ssnum,
  nextSelector: '#slider-next',
  prevSelector: '#slider-prev',
  nextText: 'Onward →',
  prevText: '← Go back'
});

$(".auction-main-img-a").boxer({'fixed': true});

$(".login_popup_boxer").boxer();
});