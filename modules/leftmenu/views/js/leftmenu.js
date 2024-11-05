$(document).ready(function(){

  getSectionContent('right');
  getSectionContent('bottom');

  $(document).on('click', '.right_block_section #add_links_menu', function(){
    addNewLink($(this).attr('data-id-link'), 'right');
  });

  $(document).on('click', '.bottom_block_section #add_links_menu', function(){
    addNewLink($(this).attr('data-id-link'), 'bottom');
  });

  $(document).on('click', '.bottom_block_section #add_products_item', function(){
    addProductItem('bottom');
  });

  $(document).on('click', '.right_block_section #add_products_item', function(){
    addProductItem('right');
  });

  $(document).on('click', '.right_block_section #save_description_left', function(){
    saveDescriptionSectionMenu('right');
  });

  $(document).on('click', '.bottom_block_section #save_description_left', function(){
    saveDescriptionSectionMenu('bottom');
  });


  $(document).on("change", "input[name='right_section_val']", function(){
    var val = $(this).val();
    $('.right_section_val_form .right_radio').removeClass('active');
    $(this).parent().parent().addClass('active');

    if(val == 'links'){
      showForm('links', 'right');
    }
    if(val == 'description'){
      showForm('description', 'right');
    }
    if(val == 'products'){
      showForm('products', 'right');
    }
    if(val == 'cms'){
      showForm('cms', 'right');
    }
    if(val == 'manufacturers'){
      showForm('manufacturers', 'right');
    }
    if(val == 'suppliers'){
      showForm('suppliers', 'right');
    }
    if(val == 'video'){
      showForm('video', 'right');
    }
  });


  $(document).on("change", "input[name='bottom_section_val']", function(){
    var val = $(this).val();
    $('.bottom_section_val_form .bottom_radio').removeClass('active');
    $(this).parent().parent().addClass('active');

    if(val == 'links'){
      showForm('links', 'bottom');
    }
    if(val == 'description'){
      showForm('description', 'bottom');
    }
    if(val == 'products'){
      showForm('products', 'bottom');
    }
    if(val == 'cms'){
      showForm('cms', 'bottom');
    }
    if(val == 'manufacturers'){
      showForm('manufacturers', 'bottom');
    }
    if(val == 'suppliers'){
      showForm('suppliers', 'bottom');
    }
    if(val == 'video'){
      showForm('video', 'bottom');
    }
  });

  $(document).on('click', '.bottom_block_section #save_video_code_menu', function(){
    saveVideoCodeMenu('bottom');
  });

  $(document).on('click', '.right_block_section #save_video_code_menu', function(){
    saveVideoCodeMenu('right');
  });



  $(document).on('change', '.bottom_block_section .cms_block .itemCheckBox', function(){
    var type = 'add';
    if($(this).attr('checked') != 'checked'){
      type = 'remove';
    }
    addItemMenu($(this).val(), type, 'cms', 'bottom');
  });

  $(document).on('change', '.bottom_block_section .manufacturers_block .itemCheckBox', function(){
    var type = 'add';
    if($(this).attr('checked') != 'checked'){
      type = 'remove';
    }
    addItemMenu($(this).val(), type, 'manufacturers', 'bottom');
  });

  $(document).on('change', '.bottom_block_section .suppliers_block .itemCheckBox', function(){
    var type = 'add';
    if($(this).attr('checked') != 'checked'){
      type = 'remove';
    }
    addItemMenu($(this).val(), type, 'suppliers', 'bottom');
  });


  $(document).on('change', '.right_block_section .cms_block .itemCheckBox', function(){
    var type = 'add';
    if($(this).attr('checked') != 'checked'){
      type = 'remove';
    }
    addItemMenu($(this).val(), type, 'cms', 'right');
  });

  $(document).on('change', '.right_block_section .manufacturers_block .itemCheckBox', function(){
    var type = 'add';
    if($(this).attr('checked') != 'checked'){
      type = 'remove';
    }
    addItemMenu($(this).val(), type, 'manufacturers', 'right');
  });

  $(document).on('change', '.right_block_section .suppliers_block .itemCheckBox', function(){
    var type = 'add';
    if($(this).attr('checked') != 'checked'){
      type = 'remove';
    }
    addItemMenu($(this).val(), type, 'suppliers', 'right');
  });

  $(document).on('click', '.right_block_section .table_list_delete a', function(){
    removeProductsMenu($(this).attr('data-id-product'), 'right');
  });

  $(document).on('click', '.bottom_block_section .table_list_delete a', function(){
    removeProductsMenu($(this).attr('data-id-product'), 'bottom');
  });

  $(document).on('click', '.right_block_section .button_add_new_link', function(){
    editLinkMenu(false, 'right');
  });

  $(document).on('click', '.bottom_block_section .button_add_new_link', function(){
    editLinkMenu(false, 'bottom');
  });

  $(document).on('click', '.right_block_section .linkEditL a', function(){
    editLinkMenu($(this).attr('data-id-link'), 'right');
  });

  $(document).on('click', '.bottom_block_section .linkEditL a', function(){
    editLinkMenu($(this).attr('data-id-link'), 'bottom');
  });

  $(document).on('click', '.right_block_section .linkRemoveL a', function(){
    removeLinkMenu($(this).attr('data-id-link'), 'right');
  });

  $(document).on('click', '.bottom_block_section .linkRemoveL a', function(){
    removeLinkMenu($(this).attr('data-id-link'), 'bottom');
  });



  $(document).on('click', '.save_max_depth', function(){
    saveMaxDepth($(this).attr('data-token'));
  });

});


function saveMaxDepth(token){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: token,
      controller: 'AdminLeftMenu',
      fc: 'module',
      module : 'leftmenu',
      action: 'saveMaxDepth',
      max_depth: $('input[name="left_menu_max_depth"]').val(),
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



function removeLinkMenu(id, section){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data:$('.'+section+'_block_section form').serialize()+ '&ajax=true&id='+id+'&token='+$('input[name=token_left_menu]').val()+ '&controller=AdminLeftMenuItem&fc=module&module=leftmenu&action=removeLink&id_lang='+$('#idLang').val()+'&id_shop='+$('#idShop').val()+'&id_left_menu='+$('input[name="id_left_menu"]').val()+'&section='+section,
    beforeSend: function() {
      $('.'+section+'_block_section').append('<div class="progres_bar"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar').remove();
      if(json['success']){
        showSuccessMessage(json['success']);
      }

      if(json['form']){
        $('.'+section+'_block_section').html(json['form']);
      }
    }
  });
}

function editLinkMenu(id, section){
  $('.'+section+'_block_section .title_new_link').css('border-color', '#C7D6DB');
  $('.'+section+'_block_section .new_link').css('border-color', '#C7D6DB');

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data:$('.'+section+'_block_section form').serialize()+ '&ajax=true&id='+id+'&token='+$('input[name=token_left_menu]').val()+ '&controller=AdminLeftMenuItem&fc=module&module=leftmenu&action=editLink&id_lang='+$('#idLang').val()+'&id_shop='+$('#idShop').val()+'&id_left_menu='+$('input[name="id_left_menu"]').val()+'&section='+section,
    beforeSend: function() {
      $('.'+section+'_block_section').append('<div class="progres_bar"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar').remove();
      if (json['error']) {

        if(json['error'] == '1'){
          $('.'+section+'_block_section .title_new_link').css('border-color', 'red');
        }
        if(json['error'] == '2'){
          $('.'+section+'_block_section .new_link').css('border-color', 'red');
        }
      }

      if(json['form']){
        $('.'+section+'_block_section').html(json['form']);
        $('.'+section+'_block_section .title_new_link').focus()
      }
    }
  });
}

function addNewLink(id, section){
  $('.'+section+'_block_section .title_new_link').css('border-color', '#C7D6DB');
  $('.'+section+'_block_section .new_link').css('border-color', '#C7D6DB');

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: $('.'+section+'_block_section form').serialize()+ '&ajax=true&id='+id+'&token='+$('input[name=token_left_menu]').val()+ '&controller=AdminLeftMenuItem&fc=module&module=leftmenu&action=saveNewLink&id_lang='+$('#idLang').val()+'&id_shop='+$('#idShop').val()+'&id_left_menu='+$('input[name="id_left_menu"]').val()+'&section='+section,
    beforeSend: function() {
      $('.'+section+'_block_section').append('<div class="progres_bar"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar').remove();
      if (json['error']) {

        if(json['error'] == '1'){
          $('.'+section+'_block_section .title_new_link').css('border-color', 'red');
        }
        if(json['error'] == '2'){
          $('.'+section+'_block_section .new_link').css('border-color', 'red');
        }
      }
      if(json['success']){
        showSuccessMessage(json['success']);
      }

      if(json['form']){
        $('.'+section+'_block_section').html(json['form']);
      }
    }
  });
}

function removeProductsMenu(id_product, section){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_left_menu]').val(),
      controller: 'AdminLeftMenuItem',
      fc: 'module',
      module : 'leftmenu',
      action: 'removeProduct',
      id_left_menu: $('input[name="id_left_menu"]').val(),
      id_lang: $('#idLang').val(),
      id_shop: $('#idShop').val(),
      id_product : id_product,
      section: section,
    },
    success: function(json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else{
        if(json['list']){
          $('.'+section+'_block_section .form-group-products-add-left').html(json['list']);
          showSuccessMessage(json['success']);
        }
      }

    }
  });
}


function addItemMenu(id, type, item, section){

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_left_menu]').val(),
      controller: 'AdminLeftMenuItem',
      fc: 'module',
      module : 'leftmenu',
      action: 'addItemMenu',
      id_left_menu: $('input[name="id_left_menu"]').val(),
      id_lang: $('#idLang').val(),
      id_shop: $('#idShop').val(),
      type :  type,
      id : id,
      section: section,
      item: item,
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


function saveVideoCodeMenu(section){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_left_menu]').val(),
      controller: 'AdminLeftMenuItem',
      fc: 'module',
      module : 'leftmenu',
      action: 'saveVideoCode',
      id_left_menu: $('input[name="id_left_menu"]').val(),
      id_lang: $('#idLang').val(),
      id_shop: $('#idShop').val(),
      code: $('.'+section+'_block_section .videoCodeMenu').val(),
      section: section,
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

function saveDescriptionSectionMenu(section){
  var data = '';
  $('.'+section+'_block_section textarea').each(function(i,elem) {
    var id = $(this).attr('id');
    var description = tinyMCE.get(id).getContent();
    var idLang = id.split('_');
    data += '&description['+idLang[1]+']='+ description;
  });

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: 'ajax=true&token='+$('input[name=token_left_menu]').val()+ '&controller=AdminLeftMenuItem&fc=module&module=leftmenu&action=saveDescription&id_lang='+$('#idLang').val()+'&id_shop='+$('#idShop').val()+'&id_left_menu='+$('input[name="id_left_menu"]').val()+'&section='+section+data,
    beforeSend: function() {
      $('.'+section+'_block_section').append('<div class="progres_bar"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar').remove();
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

function addProductItem(section){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_left_menu]').val(),
      controller: 'AdminLeftMenuItem',
      fc: 'module',
      module : 'leftmenu',
      action: 'addProduct',
      id_left_menu: $('input[name="id_left_menu"]').val(),
      id_lang: $('#idLang').val(),
      id_shop: $('#idShop').val(),
      id_product : $('.'+section+'_block_section #attendee_left').val(),
      section: section,
    },
    success: function(json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else{
        if(json['list']){
          $('.'+section+'_block_section .form-group-products-add-left').html(json['list']);
          showSuccessMessage(json['success']);
        }
      }
    }
  });
}


function getSectionContent(section){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: 'ajax=true&token='+$('input[name=token_left_menu]').val()+ '&controller=AdminLeftMenuItem&fc=module&module=leftmenu&action=showSectionContent&id_lang='+$('#idLang').val()+'&id_shop='+$('#idShop').val()+'&id_left_menu='+$('input[name="id_left_menu"]').val()+'&section='+section,
    beforeSend: function() {
      $('.'+section+'_block_section').append('<div class="progres_bar"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar').remove();
      if(json['type']){
        $('.'+section+'_section_val_form .'+section+'_radio').removeClass('active');
        $('.'+section+'_section_val_form .'+section+'_radio #'+json['type']).parent().parent().addClass('active');
      }
      if(json['form']){
        $('.'+section+'_block_section').html(json['form']);
        select2IncludeLeft('.'+section+'_block_section #attendee_left');

        var el =  $('.'+section+'_block_section').find('.autoload_rte');


        tinymce.init({
          selector: el
        });

      }
    }
  });
}


function showForm(type, section){
  if(typeof $('input[name="id_left_menu"]').val() != 'undefined'){
    $.ajax({
      type: "POST",
      url: 'index.php?rand=' + new Date().getTime(),
      dataType: 'json',
      async: true,
      cache: false,
      data: 'ajax=true&token='+$('input[name=token_left_menu]').val()+ '&controller=AdminLeftMenuItem&fc=module&module=leftmenu&action=showForm&id_lang='+$('#idLang').val()+'&id_shop='+$('#idShop').val()+'&id_left_menu='+$('input[name="id_left_menu"]').val()+'&type='+type+'&section='+section,
      beforeSend: function() {
        $('.'+section+'_block_section').append('<div class="progres_bar"><div class="loading"><div></div></div></div>');
      },
      success: function(json) {
        $('.progres_bar').remove();

        if(json['form']){
          $('.'+section+'_block_section').html(json['form']);
           select2IncludeLeft('.'+section+'_block_section #attendee_left');
        }
      }
    });
  }
}

function showSuccessMessage(msg) {
  $.growl.notice({ title: "", message:msg});
}

function showErrorMessage(msg) {
  $.growl.error({ title: "", message:msg});
}

function showNoticeMessage(msg) {
  $.growl.notice({ title: "", message:msg});
}


function select2IncludeLeft(obj){

  $(obj).select2({
    placeholder: "Search for a repository",
    minimumInputLength: 1,
    width: '345px',
    dropdownCssClass: "bootstrap",
    ajax: {
      url: 'index.php',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          q: params,
          ajax	: true,
          token: $('input[name=token_left_menu]').val(),
          controller: 'AdminLeftMenuItem',
          action: 'searchProduct'
        };
      },
      results: function (data) {
        if( data ){
          return { results: data };
        }
        else{
          return {
            results: []
          }
        }
      }
    },
    formatResult: productFormatResult,
    formatSelection: productFormatSelection,
  })
}

function productFormatResult(item) {
  itemTemplate = "<div class='media'>";
  itemTemplate += "<div class='pull-left'>";
  itemTemplate += "<img class='media-object' width='40' src='" + item.image + "' alt='" + item.name + "'>";
  itemTemplate += "</div>";
  itemTemplate += "<div class='media-body'>";
  itemTemplate += "<h4 class='media-heading'>" + item.name + "</h4>";
  itemTemplate += "<span>REF: " + item.ref + "</span>";
  itemTemplate += "</div>";
  itemTemplate += "</div>";
  return itemTemplate;
}
function productFormatSelection(item) {
  return item.name;
}