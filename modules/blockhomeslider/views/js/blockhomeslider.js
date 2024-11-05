$(document).ready(function() {
  carouselSlider();
});

function carouselSlider(){

  var parent_block = $('.carousel-container').parent().width();
  var height = height_slider-25;
  var pause_hover_slider = false;
  var tracker_summation_slider = false;
  var tracker_individual_slider = false;
  var k = 1;
  
  var imageHeight = height_slider-25;
  var imageWidth = parseInt(0.8*width_slider);

  if(parent_block < width_slider){
    k = parent_block/width_slider;
    var padding = 25-(k*25)
    $('.carousel-container').width(parseInt($('.carousel-container').width())*k);
    $('.carousel-container').height((parseInt($('.carousel-container').height())+padding)*k);
    
    imageHeight = 1*k;
    imageWidth = 1*k;
  }

  if(pause_hover == 1){
    pause_hover_slider = true;
  }
  if(tracker_summation == 1){
    tracker_summation_slider = true;
  }
  if(tracker_individual == 1){
    tracker_individual_slider = true;
  }


  var carousel = $("#carousel").featureCarousel({
    largeFeatureWidth: imageWidth,
    largeFeatureHeight: imageHeight,
    smallFeatureOffset: 0.1*k*parseInt(height),
    smallFeatureWidth: 0.8*k,
    smallFeatureHeight: 0.8*k,
    autoPlay: parseInt(auto_play),
    carouselSpeed: parseInt(speed_slider),
    topPadding: 0,
    pauseOnHover: pause_hover_slider,
    trackerSummation: tracker_summation_slider,
    trackerIndividual: tracker_individual_slider,
  });

  var w = parseInt($('.carousel-container').width());
  var h = parseInt($('.carousel-container').height());
  var position_desc = $('.carousel-container .carousel-caption').attr('data-position-desc')

  if(position_desc == 'center'){

    var caption_width = $('.carousel-container .carousel-caption').outerWidth();
    if(caption_width > (w - 0.2*w)){
      $('.carousel-container .carousel-caption').css({'margin-left': '0px', 'margin-top': '0px', 'left': '0px', 'top': '0px'})
    }

  }

  $('.carousel-container .carousel-caption').css('max-width', w - 0.2*w);
  $('.carousel-container .carousel-caption').css('max-height', h - 25);

}


