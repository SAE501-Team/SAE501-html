$(document).ready(function(){



  if($('.product-category').length>0){
    productsCategorySlider($('.product-category .products'));
    labelCategoryPage('grid', '.product-category .product-miniature');
  }

  if($('.product-accessories').length>0){
    productsCategorySlider($('.product-accessories .products'));
    labelCategoryPage('grid', '.product-accessories .product-miniature');
  }



  $(document).on('click', '.product-accessories-title .arrows_slider_productscategory .arrow_prev', function(e){
    $( ".product-accessories .bx-prev" ).click();
    $( ".product-accessories-title .arrows_slider_productscategory .arrow_next").removeClass('disabled');
    if( $( ".product-accessories .bx-prev" ).hasClass('disabled') ){
      $( ".product-accessories-title .arrows_slider_productscategory .arrow_prev").addClass('disabled');
    }
  });

  $(document).on('click', '.product-accessories-title .arrows_slider_productscategory .arrow_next', function(e){
    $( ".product-accessories .bx-next" ).click();
    $( ".product-accessories-title .arrows_slider_productscategory .arrow_prev").removeClass('disabled');
    if( $( ".product-accessories .bx-next" ).hasClass('disabled') ){
      $( ".product-accessories-title .arrows_slider_productscategory .arrow_next").addClass('disabled');
    }
  });


  $(document).on('click', '.product-category-title .arrows_slider_productscategory .arrow_prev', function(e){
    $( ".product-category .bx-prev" ).click();
    $( ".product-category-title .arrows_slider_productscategory .arrow_next").removeClass('disabled');
    if( $( ".product-category .bx-prev" ).hasClass('disabled') ){
      $( ".product-category-title .arrows_slider_productscategory .arrow_prev").addClass('disabled');
    }
  });

  $(document).on('click', '.product-category-title .arrows_slider_productscategory .arrow_next', function(e){
    $( ".product-category .bx-next" ).click();
    $( ".product-category-title .arrows_slider_productscategory .arrow_prev").removeClass('disabled');
    if( $( ".product-category .bx-next" ).hasClass('disabled') ){
      $( ".product-category-title .arrows_slider_productscategory .arrow_next").addClass('disabled');
    }
  });



  if($('.product_left_block').length>0){
    labelProductPage('.product_left_block .product-flags li');
  }


  if($('#products').length>0){
    if($.cookie){
      if($.cookie("category_view")){
        displayListGrid($.cookie("category_view"));
      }
    }
    else{
      labelCategoryPage('grid', '#products .product-miniature');
    }
  }

  $(document).on('click', '.display_list_grid li', function(e){
    e.preventDefault();
    if(!$(this).hasClass('selected')){
      if($(this).hasClass('list')){
        displayListGrid('list');
        setInCookie('list');
      }
      else{
        displayListGrid('grid');
        setInCookie('grid');
      }
    }
  });
});

function setInCookie(type){
  $.cookie("category_view",type,{ expires : 100, path:'/' });
}

function displayListGrid(type) {

  if(type == 'list'){
    $('.display_list_grid li.list').addClass('selected');
    $('.display_list_grid li.grid').removeClass('selected');
    $('#category #products').addClass('list');
    $('#category #products').removeClass('grid');
  }
  else{
    $('.display_list_grid li.grid').addClass('selected');
    $('.display_list_grid li.list').removeClass('selected');
    $('#category #products').addClass('grid');
    $('#category #products').removeClass('list');
  }
  labelCategoryPage(type, '#products .product-miniature' );

}

function labelProductPage(block) {

  var margin = 10;
  var top = -5;
  n = 0;

  $(block).each(function(k) {
    if(!$(this).hasClass('discount')){
      var res = top + (margin*(n+1)) + (n*32);
      $(this).css('top', res+'px');
      n = n+1;
    }
  });
}

function labelCategoryPage(type, block){

  $(block).each(function(){
    var current = $(this);
    var obj = current.find('.product-flags');
    var percentage = current.find('.discount-percentage').length;
    var margin = 10;
    var top = 38;
    current.find('.product-flags').find('.discount').remove();

    if(percentage){
      if(type == 'list'){
        top = 55;
        current.find('.discount-percentage').css('top', '25px');
      }
      else{
        current.find('.discount-percentage').css('top', '-249px');
      }
    }
    else{
      if(type == 'list'){
        top = 15;
      }
      else{
        top = 0;
      }
    }

    obj.find('li').each(function(k){
      var res = top + (margin*(k+1)) + (k*30);
      $(this).css('top', res+'px');
    });

  });
}



function productsCategorySlider(el){

  var count_all = el.attr('data-count');

  if (($(window).width()+scrollCompensate()) > 1199 && count_all <= 4){
    el.find('.arrows_slider_productscategory').hide();
  }

  if (($(window).width()+scrollCompensate()) <= 1199 && ($(window).width()+scrollCompensate()) > 991 && count_all <= 3){
    el.find('.arrows_slider_productscategory').hide();
  }
  if (($(window).width()+scrollCompensate()) <= 991 && ($(window).width()+scrollCompensate()) > 636 && count_all <= 2){
    el.find('.arrows_slider_productscategory').hide();
  }

  if (($(window).width()+scrollCompensate()) <= 636 && count_all <= 1 ){
    el.find('.arrows_slider_productscategory').hide();
  }

  var count = 4;
  var slideWidth = 300;

  if (($(window).width()+scrollCompensate()) <= 1199){
    var count = 3;
  }
  if (($(window).width()+scrollCompensate()) <= 900){
    var count = 2;
  }

  if (($(window).width()+scrollCompensate()) <= 600){
    var count = 1;
  }

  if (($(window).width()+scrollCompensate()) <= 360){
    var count = 1;
    var slideWidth = 200;
  }

  el.bxSlider({
    useCSS: false,
    minSlides: count,
    slideWidth: slideWidth,
    slideMargin: 15,
    maxSlides: count,
    autoControls: true,
    speed: 500,
    controls: true,
    pause: 4000,
    infiniteLoop: false,
    hideControlOnEnd: true,
    pager: false,
    moveSlides: 1,
  });

}
