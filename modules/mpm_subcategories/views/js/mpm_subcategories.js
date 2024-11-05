$(document).ready(function(){

  subcategorySlider();


  var count = $('.subcategory-slider').attr('data-count');



  $(document).on('click', '.arrows_slider_subcategory .arrow_prev', function(){
    $( ".block_subcategories .bx-prev" ).click();
    $( ".arrows_slider_subcategory .arrow_next").removeClass('disabled');
    if( $( ".block_subcategories  .bx-prev" ).hasClass('disabled') ){
      $( ".arrows_slider_subcategory .arrow_prev").addClass('disabled');
    }
  });

  $(document).on('click', '.arrows_slider_subcategory .arrow_next', function(){
    $( ".block_subcategories  .bx-next" ).click();
    $( ".arrows_slider_subcategory .arrow_prev").removeClass('disabled');
    if( $( ".block_subcategories .bx-next" ).hasClass('disabled') ){
      $( ".arrows_slider_subcategory .arrow_next").addClass('disabled');
    }
  });
});

function subcategorySlider(){

  var count = 4;
  var slideWidth = 200;
  var slideMargin = 20;

  if (($(window).width()+scrollCompensate()) <= 767){
    var count = 3;
    var slideWidth = 150;
    var slideMargin = 20;
  }

  if (($(window).width()+scrollCompensate()) <= 450){
    var count = 2;
    var slideWidth = 150;
    var slideMargin = 10;
  }


  $('.categories_slider').bxSlider({
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