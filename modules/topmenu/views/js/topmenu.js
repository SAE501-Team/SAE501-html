$(document).ready(function(){



  $(document).on('click', '#save_description', function(){
    saveDescriptionSelection();
  });

  $(document).on('blur', '#width_section', function(){
    saveSizeSection('width', $(this).val());
  });

  $(document).on('blur', '#height_section', function(){
    saveSizeSection('height', $(this).val());
  });


  $(document).on('blur', '.title_selection_block .title', function(){
    saveTitleSelection();
  });

  $(document).on('change', '.checkbox_left_selection', function(){
    if($('.checkbox_left_selection:checked').val() == 1){
      saveSelection('left_selection', 1);
      showSettings('left');
    }
    else{
      saveSelection('left_selection', 0);
    }
  });

  $(document).on('change', '.checkbox_main_selection', function(){
    if($('.checkbox_main_selection:checked').val() == 1){
      saveSelection('main_selection', 1);
      showSettings('main');
    }
    else{
      saveSelection('main_selection', 0);
    }
  });

  $(document).on('change', '.checkbox_right_selection', function(){
    if($('.checkbox_right_selection:checked').val() == 1){
      saveSelection('right_selection', 1);
      showSettings('right');
    }
    else{
      saveSelection('right_selection', 0);
    }
  });

  $(document).on('change', '.checkbox_botton_selection', function(){
    if($('.checkbox_botton_selection:checked').val() == 1){
      saveSelection('botton_selection', 1);
      showSettings('botton');
    }
    else{
      saveSelection('botton_selection', 0);
    }
  });

  $(document).on('click', '#save_video_code', function(){
    saveVideoCode();
  });

  $(document).on('click', '#add_products_item', function(){
    addProducts();
  });

  $(document).on('click', '#save_categories', function(){
    saveCategories();
  });

  $(document).on('click', '.table_list_delete a', function(){
    removeProduct($(this).attr('data-id-product'));
  });

  $(document).on('change', '.cmsCheckBox', function(){
    var type = 'add';
    if($(this).attr('checked') != 'checked'){
      type = 'remove';
    }
    addItems($(this).val(), type, 'addCms');
  });

  $(document).on('change', '.manufacturerCheckBox', function(){
    var type = 'add';
    if($(this).attr('checked') != 'checked'){
      type = 'remove';
    }
    addItems($(this).val(), type, 'addManufacturer');
  });

  $(document).on('change', '.supplierCheckBox', function(){
    var type = 'add';
    if($(this).attr('checked') != 'checked'){
      type = 'remove';
    }
    addItems($(this).val(), type, 'addSupplier');
  });

  $(document).on('click', '.button_back_positions', function(){
    $('.base_content').show();
    $('.select_content').remove();
    $('input[name="section"]').remove();
  });

  $(document).on('click', 'input[name="select_type"]', function(){
    $('.content_bl').hide();
    $(this).parent().next().show();

    saveSelectionValue($(this).val());

  });

  $(document).on('click', 'input[name="narrow"]', function(){
    showHideBlockNarrow();
  });

  $(document).on('click', 'input[name="custom"]', function(){
    showHideBlockCustomLink();
  });

  $(document).on('click', 'input[name="content"]', function(){
    showHideBlockContent();
  });

  $(document).on('click', '#add_links', function(){
    newLinks($(this).attr('data-id-link'));
  });

  $(document).on('click', '.linkEdit a', function(){
    editLink($(this).attr('data-id-link'));
  });

  $(document).on('click', '.block_button_add_new', function(){
    editLink(false);
  });

  $(document).on('click', '.linkRemove a', function(){
    removeLink($(this).attr('data-id-link'));
  })

  $(document).on('click', '.main_section_cont .left_selection a', function(){
    showSettings('left');
  });

  $(document).on('click', '.main_section_cont .main_selection a', function(){
    showSettings('main');
  });

  $(document).on('click', '.main_section_cont .right_selection a', function(){
    showSettings('right');
  });

  $(document).on('click', '.main_section_cont .botton_selection a', function(){
    showSettings('botton');
  });

  select2Include();

});


function saveSizeSection(type, val){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=top_menu_token]').val(),
      controller: 'AdminTopMenu',
      fc: 'module',
      module : 'topmenu',
      action: 'saveSizeSection',
      id_shop: $('.id_shop').val(),
      id_lang: $('.id_lang').val(),
      type: type,
      section: $('input[name="section"]').val(),
      id_top_menu: $('input[name="id_top_menu"]').val(),
      val: val,
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



function saveDescriptionSelection(){
  var data = '';
  $('.textareaBlock textarea').each(function(i,elem) {
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
    data: 'ajax=true&token='+$('input[name=top_menu_token]').val()+ '&controller=AdminTopMenu&fc=module&module=topmenu&action=saveDescription&id_lang='+$('.id_lang').val()+'&id_shop='+$('.id_shop').val()+'&id_top_menu='+$('input[name="id_top_menu"]').val()+'&section='+$('input[name="section"]').val()+data,
    beforeSend: function() {
      $('.textareaBlock').append('<div class="progres_bar"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar').remove();
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else{
        if(json['success']){
          showSuccessMessage(json['success']);
          $('.content_bl.textareaBlock form').replaceWith(json['form'])
        }
      }
    }
  });
}


function saveTitleSelection(){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: $('.title_selection_block form').serialize()+ '&ajax=true&token='+$('input[name=top_menu_token]').val()+ '&controller=AdminTopMenu&fc=module&module=topmenu&action=saveTitleSelection&id_lang='+$('.id_lang').val()+'&id_shop='+$('.id_shop').val()+'&id_top_menu='+$('input[name="id_top_menu"]').val()+'&section='+$('input[name="section"]').val(),
    success: function(json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else{
        if(json['success']){
          showSuccessMessage(json['success']);

          $('.title_selection_block form').replaceWith(json['form_title'])

        }
      }

    }
  });
}


function saveSelectionValue(val){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=top_menu_token]').val(),
      controller: 'AdminTopMenu',
      fc: 'module',
      module : 'topmenu',
      action: 'saveSelectionVal',
      id_top_menu: $('input[name="id_top_menu"]').val(),
      id_lang: $('.id_lang').val(),
      id_shop: $('.id_shop').val(),
      section: $('input[name="section"]').val(),
      val : val,
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

function saveSelection(col, val){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=top_menu_token]').val(),
      controller: 'AdminTopMenu',
      fc: 'module',
      module : 'topmenu',
      action: 'saveSelection',
      id_top_menu: $('input[name="id_top_menu"]').val(),
      col: col,
      val : val,
    },
    success: function(json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
    }
  });
}



function saveVideoCode(){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=top_menu_token]').val(),
      controller: 'AdminTopMenu',
      fc: 'module',
      module : 'topmenu',
      action: 'saveVideoCode',
      id_top_menu: $('input[name="id_top_menu"]').val(),
      id_lang: $('.id_lang').val(),
      id_shop: $('.id_shop').val(),
      code: $('.videoCodeBlock').val(),
      section: $('input[name="section"]').val(),
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

function addItems(id, type, action){

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=top_menu_token]').val(),
      controller: 'AdminTopMenu',
      fc: 'module',
      module : 'topmenu',
      action: action,
      id_top_menu: $('input[name="id_top_menu"]').val(),
      id_lang: $('.id_lang').val(),
      id_shop: $('.id_shop').val(),
      type :  type,
      id : id,
      section: $('input[name="section"]').val(),
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




function saveCategories(){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: $('.content_bl.categoriesBlock form').serialize()+ '&ajax=true&token='+$('input[name=top_menu_token]').val()+ '&controller=AdminTopMenu&fc=module&module=topmenu&action=saveCategories&id_lang='+$('.id_lang').val()+'&id_shop='+$('.id_shop').val()+'&id_top_menu='+$('input[name="id_top_menu"]').val()+'&section='+$('input[name="section"]').val(),
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



function removeProduct(id_product){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=top_menu_token]').val(),
      controller: 'AdminTopMenu',
      fc: 'module',
      module : 'topmenu',
      action: 'removeProduct',
      id_top_menu: $('input[name="id_top_menu"]').val(),
      id_lang: $('.id_lang').val(),
      id_shop: $('.id_shop').val(),
      id_product : id_product,
      section: $('input[name="section"]').val(),
    },
    success: function(json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else{
        if(json['list']){
          //
          $('.add_products_list').html(json['list']);
          showSuccessMessage(json['success']);
        }
      }

    }
  });
}

function addProducts(){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=top_menu_token]').val(),
      controller: 'AdminTopMenu',
      fc: 'module',
      module : 'topmenu',
      action: 'addProduct',
      id_top_menu: $('input[name="id_top_menu"]').val(),
      id_lang: $('.id_lang').val(),
      id_shop: $('.id_shop').val(),
      id_product : $('#attendee').val(),
      section: $('input[name="section"]').val(),
    },
    success: function(json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else{
        if(json['list']){

          $('.add_products_list').html(json['list']);
          showSuccessMessage(json['success']);
        }
      }

    }
  });
}

function removeLink(id){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: $('.content_bl.formLinksBlock form').serialize()+ '&ajax=true&id='+id+'&token='+$('input[name=top_menu_token]').val()+ '&controller=AdminTopMenu&fc=module&module=topmenu&action=removeLink&id_lang='+$('.id_lang').val()+'&id_shop='+$('.id_shop').val()+'&id_top_menu='+$('input[name="id_top_menu"]').val()+'&section='+$('input[name="section"]').val(),
    success: function(json) {
      if (json['error']) {

      }
      if(json['success']){
        showSuccessMessage(json['success']);

      }
      if(json['list']){

        $('.allLinks').replaceWith(json['list']);
      }
      if(json['form']){

        $('.content_bl.formLinksBlock form').replaceWith(json['form']);

      }
    }
  });
}


function editLink(id){
  $('.title_new_link').css('border-color', '#C7D6DB');
  $('.new_link').css('border-color', '#C7D6DB');

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: $('.content_bl.formLinksBlock form').serialize()+ '&ajax=true&id='+id+'&token='+$('input[name=top_menu_token]').val()+ '&controller=AdminTopMenu&fc=module&module=topmenu&action=editLink&id_lang='+$('.id_lang').val()+'&id_shop='+$('.id_shop').val()+'&id_top_menu='+$('input[name="id_top_menu"]').val()+'&section='+$('input[name="section"]').val(),
    success: function(json) {
      if (json['error']) {

        if(json['error'] == '1'){
          $('.title_new_link').css('border-color', 'red');
        }
        if(json['error'] == '2'){
          $('.new_link').css('border-color', 'red');
        }
      }


      if(json['form']){
        $('.content_bl.formLinksBlock form').replaceWith(json['form']);
        $('.title_new_link').focus()
      }
    }
  });
}



function newLinks(id){
  $('.title_new_link').css('border-color', '#C7D6DB');
  $('.new_link').css('border-color', '#C7D6DB');

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: $('.content_bl.formLinksBlock form').serialize()+ '&ajax=true&id='+id+'&token='+$('input[name=top_menu_token]').val()+ '&controller=AdminTopMenu&fc=module&module=topmenu&action=saveNewLink&id_lang='+$('.id_lang').val()+'&id_shop='+$('.id_shop').val()+'&id_top_menu='+$('input[name="id_top_menu"]').val()+'&section='+$('input[name="section"]').val(),
    beforeSend: function() {
      $('.formLinksBlock').append('<div class="progres_bar"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar').remove();
      if (json['error']) {

        if(json['error'] == '1'){
          $('.title_new_link').css('border-color', 'red');
        }
        if(json['error'] == '2'){
          $('.new_link').css('border-color', 'red');
        }
      }
      if(json['success']){
        showSuccessMessage(json['success']);

      }
      if(json['list']){

        $('.allLinks').replaceWith(json['list']);
      }
      if(json['form']){

        $('.content_bl.formLinksBlock form').replaceWith(json['form']);

      }
    }
  });
}

function showSettings(section){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,

    data: {
      ajax	: true,
      token: $('input[name=top_menu_token]').val(),
      controller: 'AdminTopMenu',
      fc: 'module',
      module : 'topmenu',
      action: 'showSettings',
      id_top_menu: $('input[name="id_top_menu"]').val(),
      section: section,
      id_lang: $('.id_lang').val(),
      id_shop: $('.id_shop').val(),
    },
    beforeSend: function() {
      $('.main_section_cont').append('<div class="progres_bar"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.progres_bar').remove();
      if(json['success']){
        $('.base_content').hide();
        $('.base_content').after(json['success']);
        select2Include();
      }
      if(json['error']){
        showErrorMessage(json['error']);
      }
    }
  });
}

function select2Include(){
  $('#attendee').select2({
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
          token: $('input[name=top_menu_token]').val(),
          controller: 'AdminTopMenu',
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


function showHideBlockNarrow(){
  var active = $('input[name="narrow"]:checked').val();
  if(active == '1'){
    $('.widthNarrowForm').show();
  }
  else if(active == '0'){
    $('.widthNarrowForm').hide();
  }
}

function showHideBlockCustomLink(){
  var active = $('input[name="custom"]:checked').val();
  if(active == '1'){
    $('.linkFormGroup').show();
    $('.main_section_block').hide()
    $('#content_off').next().click();
  }
  else if(active == '0'){
    $('.linkFormGroup').hide();
  }
}

function showHideBlockContent(){
  var active = $('input[name="content"]:checked').val();
  if(active == '1'){
    $('.main_section_block').show();
    $('.linkFormGroup').hide()
    $('#custom_off').next().click();
  }
  else if(active == '0'){
    $('.main_section_block').hide()
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


