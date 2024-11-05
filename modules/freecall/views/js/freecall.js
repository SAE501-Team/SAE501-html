$(document).ready(function(){


  ios = /iPhone|iPad|iPod/i.test(navigator.userAgent);

  if(ios){
    $('.pozvonim-button').removeClass('no_ios');
  }


  $(document).on('click', '.close_form_freecall, .no_button_freecall, .overlay_freecall', function(){
    hideFreeCallForm();
  });

  $(document).on('click', '.da_button_freecall', function(){
   replaceForm();
  });

  $(document).on('click', '.pozvonim-button .block_button', function(){
    showFreeCallForm(true);
  });

  $(document).on('click', '.button_freecall button', function(){
    sendMails();
  });


});

function replaceForm(){
  $('.footer_freecall').show();
  $('.description_freecall.desc1').show();
  $('.description_freecall.desc2').hide();
  $('.header_freecall .title1').show();
  $('.header_freecall .title2').hide();
}

function sendMails(){
  var basePath = $('input[name="basePath"]').val();
  var phone = $('.footer_freecall .phone_user').val();
  var email = $('.footer_freecall .email_user').val();
  var comment = $('.footer_freecall .comment_user').val();
  var captcha = $('.footer_freecall .captcha_res').val();

  $.ajax({
    type: "POST",
    url: basePath+'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: "",
      controller: 'AjaxForm',
      fc: 'module',
      module : 'freecall',
      action: 'send',
      id_shop: $('input[name="idShop"]').val(),
      id_lang: $('input[name="idLang"]').val(),
      phone: phone,
      email: email,
      captcha: captcha,
      comment: comment
    },
    success: function(json) {
      if(json['error']){
        if(json['error'] == 'phone'){
          phoneError();
        }
        if(json['error'] == 'email'){
          emailError();
        }
        if(json['error'] == 'captcha'){
          captchaError();
        }
        else{
          if(json['title'] && json['description']){
            $('.header_freecall .title').html(json['title']);
            $('.header_freecall .description_freecall').html(json['description']);
            $('.footer_freecall').hide();
          }
        }
      }
      if(json['success']){
        if(json['title'] && json['description']){
          $('.header_freecall .title').html(json['title']);
          var text = json['description'];
          $('.description_freecall.desc1').html(text);
          $('.footer_freecall').hide();
        }
      }
    }
  });
}

function phoneError(){
  $('.footer_freecall .phone_user').css('background-color', '#F33D3D');
  setTimeout(function(){
    $('.footer_freecall .phone_user').css('background-color', '#ffffff');
  }, 300)
}

function captchaError(){
  $('.footer_freecall .captcha_res').css('background-color', '#F33D3D');
  setTimeout(function(){
    $('.footer_freecall .captcha_res').css('background-color', '#ffffff');
  }, 300)
}

function emailError(){
  $('.footer_freecall .email_user').css('background-color', '#F33D3D');
  setTimeout(function(){
    $('.footer_freecall .email_user').css('background-color', '#ffffff');
  }, 300)
}

function showFreeCallForm(click){
	if( $('.freecall_form').length > 0 ){
    return false;
  }
  var basePath = $('input[name="basePath"]').val();

  $.ajax({
    type: "POST",
    url: basePath+'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token	: "",
      controller: 'AjaxForm',
      fc: 'module',
      module : 'freecall',
      action: 'showForm',
      click: click,
      id_shop: $('input[name="idShop"]').val(),
      id_lang: $('input[name="idLang"]').val(),
    },
    success: function(json) {
      if(json['form']){
        $("body").prepend(json['form']);
        var top = $('body').scrollTop();
        $('.freecall_form').css('top', top);
      }
    }
  });
}

function hideFreeCallForm(){
  $('.overlay_freecall').fadeOut(700);
  $('.freecall_form').fadeOut(500);

  setTimeout(function(){
    $('.overlay_freecall').remove();
    $('.freecall_form').remove();
  }, 700)
}


