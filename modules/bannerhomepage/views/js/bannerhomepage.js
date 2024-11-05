$(document).ready(function(){

  if($('.banner-slider').length>0){
    bannerSlider();

    var count = $('.banner-slider').attr('data-count');

    if (($(window).width()+scrollCompensate()) > 1199 && count <= 3){
      $('.arrows_slider_banner').hide();
    }

    if (($(window).width()+scrollCompensate()) <= 1199 && ($(window).width()+scrollCompensate()) > 991 && count <= 3){
      $('.arrows_slider_banner').hide();
    }

    if (($(window).width()+scrollCompensate()) <= 991 && ($(window).width()+scrollCompensate()) > 730 && count <= 2){
      $('.arrows_slider_banner').hide();
    }

    if (($(window).width()+scrollCompensate()) <= 730 && ($(window).width()+scrollCompensate()) > 360 && count <= 1){
      $('.arrows_slider_banner').hide();
    }

  }
  $(document).on('click', '.arrows_slider_banner .arrow_prev', function(){
    $( ".banner-block .bx-prev" ).click();
    $( ".arrows_slider_banner .arrow_next").removeClass('disabled');
    if( $( ".banner-block .bx-prev" ).hasClass('disabled') ){
      $( ".arrows_slider_banner .arrow_prev").addClass('disabled');
    }
  });

  $(document).on('click', '.arrows_slider_banner .arrow_next', function(){
    $( ".banner-block .bx-next" ).click();
    $( ".arrows_slider_banner .arrow_prev").removeClass('disabled');
    if( $( ".banner-block .bx-next" ).hasClass('disabled') ){
      $( ".arrows_slider_banner .arrow_next").addClass('disabled');
    }
  });

});

function bannerSlider(){

  var count = 3;
  var slideWidth = 332;
  var slideMargin = 20;

  if (($(window).width()+scrollCompensate()) <= 1199){
    var count = 3;
    var slideWidth = 280;
    var slideMargin = 20;
  }

  if (($(window).width()+scrollCompensate()) <= 991){
    var count = 2;
    var slideWidth = 330;
    var slideMargin = 20;
  }

  if (($(window).width()+scrollCompensate()) <= 730){
    var count = 1;
    var slideWidth = 330;
    var slideMargin = 0;
  }

  if (($(window).width()+scrollCompensate()) <= 360){
    var count = 1;
    var slideWidth = 300;
    var slideMargin = 0;
  }

  $('.banner-slider').bxSlider({
    useCSS: false,
    minSlides: count,
    slideWidth: slideWidth,
    maxSlides: count,
    slideMargin: slideMargin,
    autoControls: true,
    controls: true,
    pause: 4000,
    speed: 800,
    infiniteLoop: false,
    hideControlOnEnd: true,
    pager: false,
    moveSlides: 1,
  });

}