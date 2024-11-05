$(document).ready(function(){

  if (($(window).width()+scrollCompensate()) <= 767)
  {
    $('.top_menu').removeClass('desktop_device');
    $(document).on('click', '.mobile_device .content_hide', function(e){

      e.preventDefault()
      $('.top_menu_content').hide();

      if($(this).hasClass('active')){
        $(this).removeClass('active');
        $(this).next().hide()
      }
      else{
        $('.item_top_menu').removeClass('active');
        $(this).addClass('active');
        $(this).next().show()
      }
    });

  }

  $(document).on('click', '.mobile_menu', function(e){
    var obj = $('.mobile_menu')
    $('.item_top_menu').removeClass('active');
    if(obj.hasClass('active')){
      obj.removeClass('active');
      $('.top_menu').hide();
      $('.top_menu_content').hide();
      $('.item_top_menu').removeClass('active');
    }
    else{
      obj.addClass('active');
      $('.top_menu').show();
    }
  });


  $(window).resize(function(){

    if (($(window).width()+scrollCompensate()) <= 767)
    {
      $('.top_menu').addClass('mobile_device');
      $('.mobile_menu').addClass('mobile_device');
      $('.top_menu').removeClass('desktop_device');
    }
    else{
      $('.top_menu').removeClass('mobile_device');
      $('.mobile_menu').removeClass('mobile_device');
      $('.top_menu').addClass('desktop_device');

    }
  });


  menuDropDown();




});


function menuDropDown()
{

  if (($(window).width()+scrollCompensate()) <= 767)
  {
    $('.top_menu').addClass('mobile_device');
    $('.mobile_menu').addClass('mobile_device');
  }
  else{
    $('.top_menu').removeClass('mobile_device');
    $('.mobile_menu').removeClass('mobile_device');
  }


  $(".desktop_device .content_hide").hover(
    function () {
      $(".top_menu_content").hide();
      $(this).next().slideDown(100);
      // resizeDropDownBlock($(this));
    },
    function () {
      $(this).parent().mouseleave(function() {
        $(".top_menu_content").slideUp(100);
      });
    }
  );

  result = 0;

  $(".content_hide").each(function( index ) {

    var el_menu = $(this)
    var top_menu = $('.top_menu').offset().top
    var top = $(this).offset().top
    var result = top-top_menu+35;


    resizeDropDownBlock($(this));
    setTimeout(function () {

      $(".top_menu_content").hide();

      var el = el_menu.next();

      el.css('top', result+'px');

      $(".narrow_item .top_menu_content").css('top', '35px');
      $(".top_menu.mobile_device").css('top', '-10px');

      if($('.top_menu').hasClass('mobile_device')){
        $('.top_menu.mobile_device .top_menu_content').css('top', '35px');
        $(".top_menu.mobile_device").hide();
      }

    }, 500)

  });



}


function resizeDropDownBlock(el){

  var width_page = 1112;
  var left = 0;
  var margin_left = 0;
  var container = $('.block_after_top').width();
  var block = el.next();

  var block_left = block.find('.left_selection_front');
  var block_main = block.find('.main_selection_front');
  var block_right = block.find('.right_selection_front');
  var block_botton = block.find('.botton_selection_front');

  var section_left = block_left.find('.productsBlock');
  var section_main = block_main.find('.productsBlock');
  var section_right = block_right.find('.productsBlock');

  var block_width = block.width();
  var width = false;
  var height = false;
  var background_width = 0;
  var background_height = 0;

  var width_left = block_left.attr('data-width');
  var width_main = block_main.attr('data-width');
  var width_right = block_right.attr('data-width');
  var width_botton = block_botton.attr('data-width');

  var height_left = block_left.attr('data-height');
  var height_main = block_main.attr('data-height');
  var height_right = block_right.attr('data-height');
  var height_botton = block_botton.attr('data-height');

  var narrow_active = block.attr('data-narrow');
  var background_block = block.find('.block');
  var background = background_block.attr('data-background');

  if(background){
    background_width = background_block.attr('data-img-width');
    background_height = background_block.attr('data-img-height');
  }

  if(narrow_active == 1){
    width = block.attr('data-width');
    width_page = width;
    height = block.attr('data-height');
    left =  el.offset().left - $('.top_menu').offset().left;

    if(container <= (block_width+left)){
      if(container <= block_width){
        width = container;
        margin_left = -left;
      }
      else{
        var rez =  container - block_width;
        margin_left = rez - left;
      }
    }
  }
  else{
    width = container;
    height = 'auto';
  }



  setTimeout(function(){

    k = (parseFloat(width) + 2)/width_page;

    block.outerWidth(parseFloat(width) + 2);
    block.outerHeight(height);
    block.css('left', margin_left+'px');

    block_left.css('width', ((width_left*k)-1)+'px' );
    block_left.css( 'height',height_left+'px' );

    block_main.css( 'width', ((width_main*k)-1)+'px' );
    block_main.css('height', height_main+'px' );

    block_right.css( 'width', ((width_right*k)-1)+'px' );
    block_right.css( 'height',height_right+'px' );

    block_botton.css( 'width', ((width_botton*k)-1)+'px' );
    block_botton.css( 'height',height_botton+'px' );


    if (($(window).width()+scrollCompensate()) <= 767)
    {
      block.css( 'width', 'calc(100% + 2px)' );
      block.css( 'height', 'auto' );
      block.css('left', '0px');

      block_left.css('width', '100%' );
      block_left.css( 'height', 'auto' );

      block_main.css('width', '100%' );
      block_main.css( 'height', 'auto' );

      block_right.css('width', '100%' );
      block_right.css( 'height', 'auto' );

      block_botton.css('width', '100%' );
      block_botton.css( 'height', 'auto' );
    }


    if(!el.hasClass('active')){
      if(section_left.length > 0){
        productsBlock(section_left);
      }
      if(section_main.length > 0){
        productsBlock(section_main);
      }
      if(section_right.length > 0){
        productsBlock(section_right);
      }
      el.addClass('active');
    }


    if(block_left.find('.links_menu').length > 0){
      resizeLink(block_left)
    }
    if(block_main.find('.links_menu').length > 0){
      resizeLink(block_main)
    }
    if(block_right.find('.links_menu').length > 0){
      resizeLink(block_right)
    }
    if(block_botton.find('.links_menu').length > 0){
      resizeLink(block_botton)
    }


    if(background){
      if(background_width > width && background_height <= height){
        background_block.css('background-size', '100% auto')

      }
      else if(background_width <= width && background_height >= height){
        background_block.css('background-size', 'auto 100%')

      }
      else if(background_width >= width && background_height >= height){
        background_block.css('background-size', 'auto 100%');
      }
      else{
        background_block.css('background-size', 'auto');
      }
    }


  }, 150);





}

function resizeLink(el) {
  var width = 0;
  el.find('.links_menu div').each(function( index ) {
    width_item = $(this).width();
    if(width_item>width){
      width = width_item;
    }
  });
  el.find('.links_menu div').css('width', (width+10)+'px');

}


function productsBlock(el){
  var slideWidth = 180;
  var blockWidth = el.width()-40;
  var n = (blockWidth/slideWidth);

  n = Math.round(n);
  slideWidth = (blockWidth/n)

  if(n == 0){
    n = 1;
    slideWidth = blockWidth;
  }

  slideWidth = slideWidth -(10);

  if(slideWidth>200){
    slideWidth = 200
  }

  $(el.find('.products_list')).bxSlider({
    useCSS: false,
    minSlides: 1,
    maxSlides: parseInt(n),
    moveSlides: 1,
    slideWidth: slideWidth,
    autoControls: true,
    controls: true,
    slideMargin: 10,
    pause: 4000,
    speed: 1000,
    infiniteLoop: false,
    hideControlOnEnd: true,
    pager: false,
  });
}



