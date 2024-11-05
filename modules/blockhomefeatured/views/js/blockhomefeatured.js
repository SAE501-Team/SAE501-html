$(document).ready(function(){


  $(document).on('click', '.homefeatured_categories .arrow_top', function(){
    $( ".homefeatured_categories .bx-prev" ).click();
    $( ".homefeatured_categories .arrow_bottom").show();
    if( $( ".homefeatured_categories .bx-prev" ).hasClass('disabled') ){
      $( ".homefeatured_categories .arrow_top").hide();
    }
  });

  $(document).on('click', '.homefeatured_categories .arrow_bottom', function(){
    $( ".homefeatured_categories .bx-next" ).click();
    $( ".homefeatured_categories .arrow_top").show();
    if( $( ".homefeatured_categories .bx-next" ).hasClass('disabled') ){
      $( ".homefeatured_categories .arrow_bottom").hide();
    }
  });

  $(document).on('click', '.arrow_featured_prev', function(){
    $( ".homefeatured_products .bx-prev" ).click();
    $( ".arrow_featured_next").removeClass('disabled');
    if( $( ".homefeatured_products .bx-prev" ).hasClass('disabled') ){
      $( ".arrow_featured_prev").addClass('disabled');
    }
  });

  $(document).on('click', '.arrow_featured_next', function(){
    $( ".homefeatured_products .bx-next" ).click();
    $( ".arrow_featured_prev").removeClass('disabled');
    if( $( ".homefeatured_products .bx-next" ).hasClass('disabled') ){
      $( ".arrow_featured_next").addClass('disabled');
    }
  });

  $(document).on('click', '.homepage_tabs .tab_featured', function(){
    $( ".arrow_featured_next").removeClass('disabled');
    $( ".arrow_featured_prev").addClass('disabled');
    $('.homepage_tabs_list li').removeClass('active');
    $(this).addClass('active');
    showHomeFeatured($(this).attr('data-id-tab'));
  });

  if($('.first_tab_featured').attr('data-id-tab')){
    showHomeFeatured($('.first_tab_featured').attr('data-id-tab'));
  }

  $(document).on('click', '.category_products', function(){
    getProductsCategory($(this).attr('data-id-cat'), $('.tab_featured.active').attr('data-id-tab'));
  });

});



function getProductsCategory(id_category, id ){

  var basePath = $('input[name="basePath"]').val();
  var id_shop = $('.id_shop').val();
  var id_lang = $('.id_lang').val();

  $('.category_products').removeClass('active');
  $('.tab_'+id_category).addClass('active');

  $.ajax({
    type: "POST",
    url: basePath+'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    beforeSend: function(){
      $(".featured_products_overlay").show();
      $(".featured_products_overlay_loading").show();
    },
    complete: function(){
      $(".featured_products_overlay").hide();
      $(".featured_products_overlay_loading").hide();
    },
    data: {
      ajax	: true,
      token: '',
      controller: 'AjaxForm',
      fc: 'module',
      module : 'blockhomefeatured',
      action: 'productsCategory',
      id_shop: id_shop,
      id_lang: id_lang,
      id: id,
      id_category: id_category,
    },
    success: function(json) {
      if(json['content']){
        $('.homefeatured_products .content').html(json['content']);
        if($('.products_featured_slider').length>0){
          $('.arrow_featured_slider').css('opacity', 1);
          productsSlider();
        }
        else{
          $('.arrow_featured_slider').css('opacity', 0);
        }

        labelCategoryPage('grid', '.homefeatured_products .product-miniature');
      }
    }
  });
}



function showHomeFeatured(id){

  var basePath = $('input[name="basePath"]').val();
  var id_shop = $('.id_shop').val();
  var id_lang = $('.id_lang').val();
  $.ajax({
    type: "POST",
    url: basePath+'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    beforeSend: function(){
      $(".featured_overlay").show();
      $(".featured_overlay_loading").show();
    },
    complete: function(){
      $(".featured_overlay").hide();
      $(".featured_overlay_loading").hide();
    },
    data: {
      ajax	: true,
      token: '',
      controller: 'AjaxForm',
      fc: 'module',
      module : 'blockhomefeatured',
      action: 'showContent',
      id_shop: id_shop,
      id_lang: id_lang,
      id: id,
    },
    success: function(json) {
      if(json['content']){
        $('.homepage_tab_content').html(json['content']);

        if($('.add_slider').length>0){
          categoriesSlider();
        }

        var count = $('#products_list_featured').attr('data-count');

        if (($(window).width()+scrollCompensate()) <= 991 && count>2){
          $('#products_list_featured').addClass('products_featured_slider');
          $('#products_list_featured').removeClass('products_featured_noslider');
        }

        if (($(window).width()+scrollCompensate()) <= 636 && count>1){
          $('#products_list_featured').addClass('products_featured_slider');
          $('#products_list_featured').removeClass('products_featured_noslider');
        }

        if($('.products_featured_slider').length>0){
          $('.arrow_featured_slider').css('opacity', 1);
          productsSlider();
        }
        else{
          $('.arrow_featured_slider').css('opacity', 0);
        }

        labelCategoryPage('grid', '.homefeatured_products .product-miniature');
      }
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

function productsSlider(){

  var count = 3;
  var slideWidth = 279;

  if (($(window).width()+scrollCompensate()) <= 1199){
    var count = 3;
    var slideWidth = 279;
  }
  if (($(window).width()+scrollCompensate()) <= 991){
    var count = 2;
    var slideWidth = 318;
  }
  if (($(window).width()+scrollCompensate()) <= 768){
    var count = 2;
    var slideWidth = 267;
  }

  if (($(window).width()+scrollCompensate()) <= 566){
    var count = 1;
    var slideWidth = 267;
  }

  $('.products_featured_slider').bxSlider({
    useCSS: false,
    minSlides: count,
    slideWidth: slideWidth,
    slideHeight: 424,
    maxSlides: count,
    autoControls: true,
    speed: 600,
    controls: true,
    pause: 2000,
    infiniteLoop: false,
    hideControlOnEnd: true,
    pager: false,
    moveSlides: 1,
  });

}

function categoriesSlider(){

  $('.add_slider').bxSlider({
    useCSS: false,
    mode: 'vertical',
    minSlides: 5,
    slideHeight: 341,
    maxSlides: 5,
    autoControls: true,
    controls: true,
    pause: 2000,
    speed: 400,
    infiniteLoop: false,
    hideControlOnEnd: true,
    pager: false,
    moveSlides: 1,
  });

}



