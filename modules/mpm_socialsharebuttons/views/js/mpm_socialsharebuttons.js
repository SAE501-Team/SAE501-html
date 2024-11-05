$(document).ready(function(){
  if( $('.rrssb-buttons').length > 0 ){

    $('.rrssb-buttons').rrssb({
      // required:
      title: $('.rrssb-buttons').attr('data-title'),
      url: $('.rrssb-buttons').attr('data-url'),

      // optional:
      description: $('.rrssb-buttons').attr('data-description'),
      emailBody: $('.rrssb-buttons').attr('data-emailBody'),
      image: $('.rrssb-buttons').attr('data-image'),
    });
  }

});

