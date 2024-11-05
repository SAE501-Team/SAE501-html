$(document).ready(function(){

  if (($(window).width()+scrollCompensate()) <= 767){


    $('#block_left_menu').addClass('mobile_device')
    $('#block_left_menu').removeClass('desktop_device')
    $('#manufacturers_block_left').addClass('mobile_device')
    $('#suppliers_block_left').addClass('mobile_device')
    $('.block_featured').addClass('mobile_device')


    $('.block_search ').addClass('mobile_device')
    $('.block_categories').addClass('mobile_device')
    $('.block_archive').addClass('mobile_device')
    $('.block_tags').addClass('mobile_device')

    $(document).on('click', '#left-column .title_block', function(e){

      if(!$(this).hasClass('active')){
        $(this).addClass('active');
        $(this).parent().addClass('active');
        $(this).next().show()
      }
      else{
        $(this).removeClass('active')
        $(this).parent().removeClass('active')

        $(this).next().hide()
      }
    });


    $('#block_left_menu  li.level_depth_2').each(function( index ) {
      if($(this).find('.categories_block_left_menu').length > 0){
        $(this).find('.right_block_left_menu').remove();
        $(this).find('.bottom_block_left_menu').remove();
      }
      else{
        $(this).find('.arrow_category_block').remove();
        $(this).find('.subcategories_level_depth_2').remove();
      }
    });
  }
  else{

    $('#block_left_menu').addClass('desktop_device')
    $('#block_left_menu').removeClass('mobile_device')


    $('#block_left_menu  li.level_depth_2').each(function( index ) {
      var obj = $(this);
      if(!obj.hasClass('connected')){
        var el = obj.find('.subcategories_level_depth_2');
        if(el.length>0){
          resizeMenu(el);
          showContent(el);
          obj.addClass('connected');
        }
      }

    });




    $('.level_depth_2').hover(function(){
      var obj = $(this);
      if(!obj.hasClass('connected')){
        setTimeout(function(){
          var el = obj.find('.subcategories_level_depth_2');
          if(el.length>0){
            //resizeMenu(el);
            //showContent(el);
            //obj.addClass('connected');
          }
        }, 50)
      }
    });
  }






  if (($(window).width()+scrollCompensate()) <= 767)
  {
    $('#block_left_menu .level_depth_2').removeClass('no_mobive_menu');
    $('#block_left_menu .level_depth_2').addClass('mobive_menu');
  }
  else{
    $('#block_left_menu .level_depth_2').addClass('no_mobive_menu');
    $('#block_left_menu .level_depth_2').removeClass('mobive_menu');
  }

  $(document).on('click', '#block_left_menu .level_depth_2.mobive_menu .arrow_category_block', function(e){
    e.preventDefault();
    if($(this).hasClass('selected')){
      $(this).removeClass('selected');
      $(this).parent().next().hide()
    }
    else{
      $(this).addClass('selected');
      $(this).parent().next().show()
    }
  });

  setTimeout(function(){
    $('.subcategories_level_depth_2').hide();
    $('.subcategories_level_depth_2').css({ 'top' : '0'});
  }, 200)

  $("#block_left_menu .level_depth_2.no_mobive_menu").hover(
    function () {
      $(this).addClass('active');
      var el = $(this).find('.subcategories_level_depth_2');
      el.show();
    },
    function () {
      $(this).removeClass('active');
      var el = $(this).find('.subcategories_level_depth_2');
      el.hide();
    }
  );

  if($('.block_left_menu_prod').length>0){
    setTimeout(function(){
      $('.block_left_menu_prod').hide();
      $('.block_left_menu_prod').css({ 'top' : 'auto'});
    }, 200)
  }

});

function resizeMenu(el) {

  var widthWrapper = $('#content-wrapper').outerWidth()+15;
  var widthMenu = el.outerWidth();
  var widthLeftBlock = el.find('.right_block_left_menu').outerWidth();

  if($('body#product').length>0){
    widthWrapper = widthWrapper - 15 - $('#block_left_menu').outerWidth()
  }


  if(widthMenu>widthWrapper){
    el.css({width: widthWrapper+'px', right: -(widthWrapper-1)+'px'});
  }
  else{
    widthWrapper = el.attr('data-width')
    el.css({width: widthWrapper+'px', right: -(widthWrapper-1)+'px'});
  }

  var widthMenu = el.outerWidth();
  var widthLeftBlock = el.find('.right_block_left_menu').outerWidth();

  if(widthMenu<widthLeftBlock){
    el.find('.right_block_left_menu').css('width', '100%')
  }
  else{
    el.find('.right_block_left_menu').css('width', el.find('.right_block_left_menu').attr('data-width')+'px')
  }


  if (($(window).width()+scrollCompensate()) <= 767)
  {
    $('#block_left_menu .level_depth_2').removeClass('no_mobive_menu');
    $('#block_left_menu .level_depth_2').addClass('mobive_menu');
  }
  else{
    $('#block_left_menu .level_depth_2').addClass('no_mobive_menu');
    $('#block_left_menu .level_depth_2').removeClass('mobive_menu');
  }
}


function showContent(el){

  if(el.find('.links_left_menu').length > 0){
    var width = 0;
    el.find('.links_left_menu div').each(function( index ) {
      width_item = $(this).width();
      if(width_item>width){
        width = width_item;
      }
    });
    el.find('.links_left_menu div').css('width', width+'px')
  }

  if(el.find('.productsBlockLeftMenu').length > 0){
    var width = el.find('.right_block_left_menu').width();
    var slideWidth = el.find('.one_product').width() ;
    var n = parseInt(width/slideWidth);
    var w = (slideWidth+15)*n;
    el.parents('.right_block_content').css('width',  w);
    if(el.find('.productsBlockLeftMenu').attr('data-count')<=n){

    }
    else{
      $(el.find('.products_list')).bxSlider({
        useCSS: false,
        minSlides: 1,
        maxSlides: n,
        slideWidth: slideWidth,
        autoControls: true,
        controls: true,
        slideMargin: 15,
        pause: 4000,
        speed: 1000,
        infiniteLoop: false,
        hideControlOnEnd: true,
        pager: false,
        moveSlides: 1,
      });
      el.find('.right_block_left_menu').find('.bx-prev').css({'left': '1px'});
      el.find('.right_block_left_menu').find('.bx-next').css({'right' : '1px'});
    }
  }

  if(el.find('.productsBlockLeftMenuBottom').length > 0){
    var width = el.find('.bottom_block_left_menu').width();
    var slideWidth = el.find('.one_product_bottom').width() ;
    var n = parseInt(width/slideWidth);
    var w = (slideWidth+15)*n;
    el.parents('.bottom_block_content').css('width',  w);
    if(el.find('.productsBlockLeftMenuBottom').attr('data-count')<=n){

    }
    else{
      $(el.find('#products_list_bottom')).bxSlider({
        useCSS: false,
        minSlides: 1,
        maxSlides: n,
        slideWidth: slideWidth,
        autoControls: true,
        controls: true,
        slideMargin: 15,
        pause: 4000,
        speed: 1000,
        infiniteLoop: false,
        hideControlOnEnd: true,
        pager: false,
        moveSlides: 1,
      });
      el.find('.bottom_block_left_menu').find('.bx-prev').css({'left': '1px'});
      el.find('.bottom_block_left_menu').find('.bx-next').css({'right' : '1px'});
    }
  }

  if(el.find('.links_left_menu_bottom').length > 0){
    var width = el.find('.bottom_block_left_menu').width()-40 ;
    var slideWidth = 150;
    var n = parseInt(width/(slideWidth+15));
    var w = (slideWidth+15)*n;
    el.find('.bottom_block_content').css({'width': (w+60)+'px', 'padding' : '0 20px'});
    if(el.find('.links_left_menu_bottom').attr('data-count')<=n){

    }
    else{
      $(el.find('.links_left_menu_bottom')).bxSlider({
        useCSS: false,
        minSlides: 1,
        maxSlides: n,
        slideWidth: slideWidth,
        autoControls: true,
        controls: true,
        slideMargin: 15,
        pause: 4000,
        speed: 1000,
        infiniteLoop: false,
        hideControlOnEnd: true,
        pager: false,
        moveSlides: 1,
      });
    }
    var el2 =  el.find('.bottom_block_left_menu');
    el2.find('.bx-prev').css({'left': '-28px'});
    el2.find('.bx-next').css({'right' : '-28px'});
  }





}