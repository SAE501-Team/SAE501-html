$(document).ready(function(){

  if($('.supplier-slider').length>0){
    supplierSlider();
    var count = $('.supplier-slider').attr('data-count');

    if (($(window).width()+scrollCompensate()) > 1199 && count <= 6){
      $('.arrows_slider_supplier').hide();
    }

    if (($(window).width()+scrollCompensate()) <= 1199 && ($(window).width()+scrollCompensate()) > 636 && count <= 5){
      $('.arrows_slider_supplier').hide();
    }

    if (($(window).width()+scrollCompensate()) <= 636 && ($(window).width()+scrollCompensate()) > 535 && count <= 4){
      $('.arrows_slider_supplier').hide();
    }

    if (($(window).width()+scrollCompensate()) <= 535 && ($(window).width()+scrollCompensate()) > 360 && count <= 3){
      $('.arrows_slider_supplier').hide();
    }

    if (($(window).width()+scrollCompensate()) <= 360 && count <= 2){
      $('.arrows_slider_supplier').hide();
    }
  }

  $(document).on('click', '.arrows_slider_supplier .arrow_prev', function(){
    $( ".supplier-block .bx-prev" ).click();
    $( ".arrows_slider_supplier .arrow_next").removeClass('disabled');
    if( $( ".supplier-block .bx-prev" ).hasClass('disabled') ){
      $( ".arrows_slider_supplier .arrow_prev").addClass('disabled');
    }
  });

  $(document).on('click', '.arrows_slider_supplier .arrow_next', function(){
    $( ".supplier-block .bx-next" ).click();
    $( ".arrows_slider_supplier .arrow_prev").removeClass('disabled');
    if( $( ".supplier-block .bx-next" ).hasClass('disabled') ){
      $( ".arrows_slider_supplier .arrow_next").addClass('disabled');
    }
  });

});

function supplierSlider(){

  var count = 6;
  var slideWidth = 178;
  var slideMargin = 20;

  if (($(window).width()+scrollCompensate()) <= 1199){
    var count = 5;
    var slideWidth = 178;
    var slideMargin = 20;
  }

  if (($(window).width()+scrollCompensate()) <= 636){
    var count = 4;
    var slideWidth = 140;
    var slideMargin = 10;
  }

  if (($(window).width()+scrollCompensate()) <= 535){
    var count = 3;
    var slideWidth = 150;
    var slideMargin = 10;
  }

  if (($(window).width()+scrollCompensate()) <= 360){
    var count = 2;
    var slideWidth = 150;
    var slideMargin = 10;
  }

  $('.supplier-slider').bxSlider({
    useCSS: false,
    minSlides: count,
    slideWidth: slideWidth,
    maxSlides: count,
    slideMargin: slideMargin,
    autoControls: true,
    controls: true,
    pause: 4000,
    speed: 600,
    infiniteLoop: false,
    hideControlOnEnd: true,
    pager: false,
    moveSlides: 1,
  });

}

function autoUrl(name, dest)
{
  var loc;
  var id_list;

  id_list = document.getElementById(name);
  loc = id_list.options[id_list.selectedIndex].value;
  if (loc != 0)
    location.href = dest+loc;
  return ;
}