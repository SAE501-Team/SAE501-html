$(document).ready(function() {
  $('.submitSaveGeneralSettings').live('click', function(e){
    e.preventDefault()
    saveGeneralSettings();
  });
});


function saveGeneralSettings(){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name="token_slider"]').val(),
      controller: 'AdminHomeSlider',
      fc: 'module',
      module : 'blockhomeslider',
      action: 'saveGeneralSettings',
      id_shop: $('input[name="idShop"]').val(),
      id_lang: $('input[name="idLang"]').val(),
      active: $('input[name="active"]:checked').val(),
      hook: $('select[name="hook"]').val(),
      width: $('input[name="width"]').val(),
      opacity: $('input[name="opacity"]').val(),
      height: $('input[name="height"]').val(),
      auto_play: $('input[name="auto_play"]').val(),
      speed: $('input[name="speed"]').val(),
      tracker_individual: $('input[name="tracker_individual"]:checked').val(),
      tracker_summation: $('input[name="tracker_summation"]:checked').val(),
      controls: $('input[name="controls"]:checked').val(),
      pause_hover: $('input[name="pause_hover"]:checked').val(),
      top_button: $('input[name="top_button"]:checked').val(),
    },
    success: function(json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else{
        if(json['success']){
          showSuccessMessage(json['success']);
          $('.block_size_slider .help-block').html(json['desc']);
        }
      }
    }
  });
}

function showSuccessMessage(msg) {
  $.growl.notice({ title: "", message:msg});
}

function showErrorMessage(msg) {
  $.growl.error({ title: "", message:msg});
}