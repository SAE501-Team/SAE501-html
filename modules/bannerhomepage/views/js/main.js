$(document).ready(function() {
  $(document).on('click', '.submitSaveGeneralSettingsBanner', function(e){
    e.preventDefault()
    saveSettingsBanner();
  });
});


function saveSettingsBanner(){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name="token_banner"]').val(),
      controller: 'AdminBlockBanner',
      fc: 'module',
      module : 'bannerhomepage',
      action: 'saveGeneralSettings',
      id_shop: $('input[name="idShop"]').val(),
      id_lang: $('input[name="idLang"]').val(),

      show_on_all_pages: $('input[name="show_on_all_pages"]:checked').val(),
      show_description: $('input[name="show_description"]:checked').val(),
    },
    success: function(json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else{
        if(json['success']){
          showSuccessMessage(json['success']);
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