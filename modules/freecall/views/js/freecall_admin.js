$(document).ready(function() {


  $('input[name=quantity_value]').keyup(function(){
    if($(this).val() != ''){
      $('#all_page_off').attr('checked', true);
      $('#all_page_on').attr('checked', false);

      if($("input[name=category_page]:checked").val() == 1  || $("input[name=product_page]:checked").val() == 1){
        $('.block_more_settings').removeClass('hide_block');
        $('.block_more_settings').addClass('show_block');
      }

    }
    else{
      if( $('input[name=price_value]').val() == ''  && $("input[name=category_page]:checked").val() != 1  && $("input[name=product_page]:checked").val() != 1){
        $('.block_more_settings').addClass('hide_block');
        $('.block_more_settings').removeClass('show_block');
      }
    }
  });

  $('input[name=price_value]').keyup(function(){
    if($(this).val() != ''){
      $('#all_page_off').attr('checked', true);
      $('#all_page_on').attr('checked', false);

      if($("input[name=category_page]:checked").val() == 1  || $("input[name=product_page]:checked").val() == 1){
        $('.block_more_settings').removeClass('hide_block');
        $('.block_more_settings').addClass('show_block');
      }

    }
    else{
      if( $('input[name=price_value]').val() == ''  && $("input[name=category_page]:checked").val() != 1  && $("input[name=product_page]:checked").val() != 1){
        $('.block_more_settings').addClass('hide_block');
        $('.block_more_settings').removeClass('show_block');
      }
    }
  });


  $(document).on('change', 'input[name=selection_type_quantity]', function(){
    $('.quantity .label_selection_type').removeClass('active');
    $(this).prev().addClass('active');
  });

  $(document).on('change', 'input[name=selection_type_price]', function(){
    $('.price .label_selection_type').removeClass('active');
    $(this).prev().addClass('active');
  });

  $("input[name='all_page']").live('change', function(){
    var checked = $("input[name='all_page']:checked").val();
    if(checked == 1){
      $('.block_more_settings').removeClass('show_block');
      $('.block_more_settings').addClass('hide_block');
      $('.block_settings_cms').addClass('hide_block_cms');
      $('.block_settings_cms').removeClass('show_block_cms');
      $('input[name=price_value]').val('')
      $('input[name=quantity_value]').val('')
    }

    if($('#all_page_on:checked').val()){
      switchChange();
    }
  });


  $("input[name='cms_page']").live('change', function(){
    var checked = $("input[name='cms_page']:checked").val();
    if(checked == 1){
      $('.block_settings_cms').removeClass('hide_block_cms');
      $('.block_settings_cms').addClass('show_block_cms');
    }
    else{
      //if( $('input[name=quantity_value]').val() == '' && $('input[name=price_value]').val() == '' ){
        $('.block_settings_cms').addClass('hide_block_cms');
        $('.block_settings_cms').removeClass('show_block_cms');
      //}
    }
    switchChangeAll();
  });

  $("input[name='category_page']").live('change', function(){
    var checked = $("input[name='category_page']:checked").val();
    if(checked == 1){
      $('.block_more_settings').removeClass('hide_block');
      $('.block_more_settings').addClass('show_block');
    }
    else{
      if( $("input[name='product_page']:checked").val() != 1 ){
        $('.block_more_settings').addClass('hide_block');
        $('.block_more_settings').removeClass('show_block');
      }
    }

    switchChangeAll();
  });

  $("input[name='product_page']").live('change', function(){
    var checked = $("input[name='product_page']:checked").val();
    if(checked == 1){
      $('.block_more_settings').removeClass('hide_block');
      $('.block_more_settings').addClass('show_block');
    }
    else{
      if( $("input[name='category_page']:checked").val() != 1 ){
        $('.block_more_settings').addClass('hide_block');
        $('.block_more_settings').removeClass('show_block');
      }
    }
    switchChangeAll();
  });


  if ($('#productIds').length > 0) {
    select2Include();
  }

  $('#add_products_item').live('click', function(){
    addProductItem();
  });

  $('.table_list_delete a').live('click', function(){
    removeProductItem($(this).attr('data-id-product'));
  });


});

function switchChangeAll(){
  $('#all_page_on').prop('checked', false);
  $('#all_page_off').prop('checked', true);
}

function switchChange(){
  $('#cms_page_off').prop('checked', true);
  $('#cms_page_on').prop('checked', false);

  $('#category_page_off').prop('checked', true);
  $('#category_page_on').prop('checked', false);

  $('#product_page_off').prop('checked', true);
  $('#product_page_on').prop('checked', false);
}

function select2Include(){
  $('.attendee').select2({
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
          token: $('input[name=token_freecall]').val(),
          controller: 'AdminFreeCall',
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



function removeProductItem(id){


  var products = $('#productIds').val();
  if(products){
    var new_products = products.split(',');
    var index = $.inArray(id, new_products);
    new_products.splice(index, 1);

    $('#productIds').val(new_products);
    $('.row_'+id).remove();
  }


}

function addProductItem(){

  var id = $('#attendee').val();
  var products = $('#productIds').val();

  if(!products){
    var new_products = [id];
  }
  else{
    var new_products = products.split(',');
    var index = $.inArray(id, new_products);
    if(index<0){
      new_products.push(id);
    }

  }

  $.ajax({
    type: "POST",
    url: 'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: $('input[name=token_freecall]').val(),
      controller: 'AdminFreeCall',
      fc: 'module',
      module : 'freecall',
      action: 'addProduct',
      idLang: $("input[name='idLang']").val(),
      idShop: $("input[name='idShop']").val(),
      products : new_products,

    },
    success: function(json) {

      if(json['list']){

        $('#productIds').val(json['products']);
        $('.form-group-products-add-left').replaceWith(json['list']);
      }


    }
  });
}