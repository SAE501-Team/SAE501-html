$(document).ready(function(){

  if($('.productsBlockFeatured').length>0){
    select2IncludeAdmin();
  }

  $('#add_products_item_featured').live('click', function(){
    addItem();
  });

  $('.table_delete a').live('click', function(){
    removeItem($(this).attr('data-id-product'));
  });

  $('#categories-tree-home input[type=checkbox]').live('change', function(){
    if (this.checked) {
      changeCategories(this.value, 1);
    }
    else{
      changeCategories(this.value, 0);
    }
  });

  $('#type.type_content').live('change', function(){
    if (this.value == 'products') {
      showFormFeatured(this.value);
    }
    else if(this.value == 'category'){
      showFormFeatured(this.value);
    }
    else{
      $('.form-group.html_content .col-lg-9').html(' ');
    }
  });

});

function showFormFeatured(type){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_featured]').val(),
      controller: 'AdminHomeFeatured',
      fc: 'module',
      module : 'blockhomefeatured',
      action: 'showForm',
      id_homefeatured: $('input[name="id_homefeatured"]').val(),
      id_lang: $('#idLang').val(),
      id_shop: $('#idShop').val(),
      type : type,
    },
    success: function(json) {
      if (json['error']) { }
      else{
        if(json['list']){
          $('.form-group.html_content .col-lg-8').html(json['list']);
          if (type == 'products') {
            select2IncludeAdmin()
          }
        }
      }
    }
  });
}

function changeCategories(id, type){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_featured]').val(),
      controller: 'AdminHomeFeatured',
      fc: 'module',
      module : 'blockhomefeatured',
      action: 'changeCategories',
      id_homefeatured: $('input[name="id_homefeatured"]').val(),
      id_lang: $('#idLang').val(),
      id_shop: $('#idShop').val(),
      id_category : id,
      type : type,
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

function removeItem(id_product){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_featured]').val(),
      controller: 'AdminHomeFeatured',
      fc: 'module',
      module : 'blockhomefeatured',
      action: 'removeProduct',
      id_homefeatured: $('input[name="id_homefeatured"]').val(),
      id_lang: $('#idLang').val(),
      id_shop: $('#idShop').val(),
      id_product : id_product,
    },
    success: function(json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else{
        if(json['list']){
          $('.added_products_list .form-wrapper').html(json['list']);
          showSuccessMessage(json['success']);
        }
      }
    }
  });
}

function addItem(){
  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_featured]').val(),
      controller: 'AdminHomeFeatured',
      fc: 'module',
      module : 'blockhomefeatured',
      action: 'addProduct',
      id_homefeatured: $('input[name="id_homefeatured"]').val(),
      id_lang: $('#idLang').val(),
      id_shop: $('#idShop').val(),
      id_product : $('#attendee_home').val(),
    },
    success: function(json) {
      if (json['error']) {
        showErrorMessage(json['error']);
      }
      else{
        if(json['list']){

          $('.added_products_list .form-wrapper').html(json['list']);
          showSuccessMessage(json['success']);
        }
      }
    }
  });
}

function select2IncludeAdmin(){
  $('#attendee_home').select2({
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
          token: $('input[name=token_featured]').val(),
          controller: 'AdminHomeFeatured',
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

function showSuccessMessage(msg) {
  $.growl.notice({ title: "", message:msg});
}

function showErrorMessage(msg) {
  $.growl.error({ title: "", message:msg});
}
