$(document).ready(function(){
  window.onscroll = function() {
    var nav = $('.header-nav').outerHeight();
    var top = $('.header-top').outerHeight();
    var h = nav + top;
    if($(window).width()+scrollCompensate() > 950){
      var scrolled = window.pageYOffset || document.documentElement.scrollTop;
      if(scrolled>=h){
        $('.min-header-nav').fadeIn(200);
      }
      else{
        $('.min-header-nav').fadeOut(200);
      }
    }
  }
});