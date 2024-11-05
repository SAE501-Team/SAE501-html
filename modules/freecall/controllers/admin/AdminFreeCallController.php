<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/FreeCallClass.php');

class AdminFreeCallController extends ModuleAdminController
{

  private $_idShop;
  private $_idLang;
  private $_freeCallClass;
  protected $position_identifier = 'id_freecall';

  public function __construct()
  {
    $this->className = 'FreeCallClass';
    $this->table = 'freecall';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_freecall';
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
    $this->_freeCallClass = new FreeCallClass();

    $this->bulk_actions = array(
      'delete' => array(
        'text' => $this->l('Delete selected'),
        'icon' => 'icon-trash',
        'confirm' => $this->l('Delete selected items?')
      )
    );

    $this->fields_list = array(
      'id_freecall' => array(
        'title' => $this->l('ID'),
        'search' => true,
        'onclick' => false,
        'filter_key' => 'a!id_freecall',
        'width' => 20
      ),
      'date_add' => array(
        'title' => $this->l('Creation date'),
        'maxlength' => 190,
        'width' =>100,
        'align' => 'left',
      ),

    );
  }

  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewfreecall') === false)
      $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;

    if( Tools::getValue('price_value') != '' && !Tools::getValue('selection_type_price') ){
      $this->errors[] = Tools::displayError('Please select sign inequality');
    }

    if( Tools::getValue('price_value') != '' && !Validate::isFloat( Tools::getValue('price_value')) ){
      $this->errors[] = Tools::displayError('Please enter valid price value');
    }

    if( Tools::getValue('quantity_value') != '' && !Tools::getValue('selection_type_quantity') ){
      $this->errors[] = Tools::displayError('Please select sign inequality');
    }

    if( Tools::getValue('quantity_value') != '' && !Validate::isInt( Tools::getValue('quantity_value')) ){
      $this->errors[] = Tools::displayError('Please enter valid quantity value');
    }


    if($this->errors){
      return false;
    }

  }

  public function initProcess(){
    parent::initProcess();
  }

  public function initContent()
  {
    parent::initContent();
  }

  public function renderList()
  {
    $this->addRowAction('edit');
    $this->addRowAction('delete');
    return parent::renderList();
  }

  public function postProcess()
  {
    return parent::postProcess();
  }

  public function renderForm()
  {
    $position = array(
      array(
        'id' => 'top_left',
        'name' => $this->l('Top left corner')
      ),
      array(
        'id' => 'top_right',
        'name' => $this->l('Top right corner')
      ),
      array(
        'id' => 'bottom_right',
        'name' => $this->l('Bottom right corner')
      ),
      array(
        'id' => 'bottom_left',
        'name' => $this->l('Bottom left corner')
      ),
    );






    $id = Tools::getValue('id_freecall');
    $obj = new FreeCallClass( $id );


    $array = array();
    $array_selected = array();


    $this->fields_form = array(
      'legend' => array(
        'title' => $this->l('Settings'),
        'icon' => 'icon-list-ul'
      ),
        'tabs' => array(
            'setting_general' => $this->l('General settings'),
            'button_tab' => $this->l('Call Button Settings'),
            'form_tab' => $this->l('Form Settings'),
            'form_delay' => $this->l('Callback form with delay'),
            'form_message' => $this->l('Message After Form Submit'),
        ),
      'input' => array(

        array(
          'type' => 'textarea',
          'label' => $this->l('Send notification for'),
          'name' => 'email',
          'autoload_rte' => false,
          'rows' => 3,
          'cols' => 20,
          'tab' => 'setting_general',
          'required' => true,
          'form_group_class' => 'field_width_50',
          'desc' => $this->l('Each email must be separated by a comma'),
        ),


        array(
          'type' => 'html',
          'name' => $this->l(''),
            'tab' => 'setting_general',
          'form_group_class' => 'title_form_group_class_freecall title_form_position',
          'html_content' => $this->l('Phone')
        ),

        array(
          'type' => 'text',
          'label' => $this->l('Phone in header'),
          'name' => 'phone',
          'tab' => 'setting_general',
        ),

        array(
          'type' => 'text',
          'label' => $this->l('Phone in header 2'),
          'name' => 'phone2',
          'tab' => 'setting_general',
        ),


        array(
          'type' => 'text',
          'label' => $this->l('Title'),
          'name' => 'title_form',
            'tab' => 'form_tab',
          'lang' => true,
          'required' => true,
        ),
        array(
          'type' => 'textarea',
          'label' => $this->l('Description'),
          'name' => 'description',
          'autoload_rte' => false,
            'tab' => 'form_tab',
          'rows' => 5,
          'cols' => 20,
          'lang' => true,
          'required' => true,
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Form border radius'),
          'name' => 'border_radius',
            'tab' => 'form_tab',
          'form_group_class' => 'field_width_200',
          'required' => true,
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Font color'),
          'name' => 'color_form',
            'tab' => 'form_tab',
          'required' => true,
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Background color'),
          'name' => 'background_form',
            'tab' => 'form_tab',
          'required' => true,
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Form opacity'),
          'name' => 'opacity_form',
            'tab' => 'form_tab',
          'form_group_class' => 'field_width_200',
          'required' => true,
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Background overlay'),
          'name' => 'background_overlay',
            'tab' => 'form_tab',
          'required' => true,
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Opacity overlay'),
          'name' => 'opacity_overlay',
            'tab' => 'form_tab',
          'form_group_class' => 'field_width_200',
          'required' => true,
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Button background color'),
          'name' => 'background_button',
            'tab' => 'form_tab',
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").'),
          'required' => true,
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Button background color on hover'),
          'name' => 'background_button_hover',
            'tab' => 'form_tab',
          'required' => true,
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Button font color'),
          'name' => 'color_button',
            'tab' => 'form_tab',
          'required' => true,
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Button font color on hover'),
          'name' => 'color_button_hover',
            'tab' => 'form_tab',
          'required' => true,
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'html',
          'name' => $this->l(''),
            'tab' => 'form_tab',
          'form_group_class' => 'title_form_group_class_freecall',
          'html_content' => $this->l('Additional Fields In Form')
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Email address'),
          'name' => 'show_email',
          'is_bool' => true,
            'tab' => 'form_tab',
          'values' => array(
            array(
              'id' => 'show_email_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'show_email_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Message'),
          'name' => 'show_comment',
            'tab' => 'form_tab',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'show_comment_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'show_comment_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
          array(
              'type' => 'switch',
              'label' => $this->l('Captcha'),
              'name' => 'show_captcha',
              'tab' => 'form_tab',
              'is_bool' => true,
              'values' => array(
                  array(
                      'id' => 'show_captcha_on',
                      'value' => 1,
                      'label' => $this->l('Yes')),
                  array(
                      'id' => 'show_captcha_off',
                      'value' => 0,
                      'label' => $this->l('No')),
              ),
          ),
        array(
          'type' => 'switch',
          'label' => $this->l('Active'),
          'name' => 'show_question',
            'tab' => 'form_delay',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'show_question_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'show_question_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Delay time'),
          'name' => 'time_show_question',
            'tab' => 'form_delay',
          'form_group_class' => 'field_width_200',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Title'),
          'name' => 'title_question',
            'tab' => 'form_delay',
          'lang' => true,
        ),
        array(
          'type' => 'textarea',
          'label' => $this->l('Description'),
          'name' => 'description_question',
          'autoload_rte' => false,
            'tab' => 'form_delay',
          'rows' => 5,
          'cols' => 20,
          'lang' => true,
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Title'),
          'name' => 'title_success',
            'tab' => 'form_message',
          'lang' => true,
          'required' => true,
        ),
        array(
          'type' => 'textarea',
          'label' => $this->l('Description'),
          'name' => 'description_success',
            'tab' => 'form_message',
          'autoload_rte' => false,
          'rows' => 3,
          'cols' => 20,
          'lang' => true,
          'required' => true,
        ),
        array(
          'type' => 'hidden',
          'name' => 'token_freecall',
        ),
        array(
          'type' => 'hidden',
          'name' => 'idLang',
        ),
        array(
          'type' => 'hidden',
          'name' => 'idShop',
        ),
      ),
      'buttons' => array(
        'save-and-stay' => array(
          'title' => $this->l('Save'),
          'name' => 'submitAdd'.$this->table.'AndStay',
          'type' => 'submit',
          'class' => 'btn btn-default pull-right',
          'icon' => 'process-icon-save'
        ),
      ),
    );

        $this->fields_value = $array_selected;
    $this->fields_value['token_freecall'] = Tools::getAdminTokenLite('AdminFreeCall');
    $this->fields_value['idLang'] =  Context::getContext()->language->id;
    $this->fields_value['idShop'] = Context::getContext()->shop->id;

    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
    return parent::renderForm();
  }


  public function getBlockSearchProduct($selected_prod){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'freecall/views/templates/hook/block-search.tpl');
    $ids = array();
    if($selected_prod){
      $ids = implode(",", $selected_prod);
    }




    $list = $this->getProductList($ids, Context::getContext()->language->id, Context::getContext()->shop->id);

    $data->assign(
      array(
        'ids' => $ids,
        'list' => $list,
      )
    );

    return $data->fetch();
  }


  public function ajaxProcessSearchProduct(){
    $search = Tools::getValue('q');
    $limit = 50;
    $idLang = $this->context->language->id;
    $idShop = $this->context->shop->id;
    $where = "";
    $limit_p = '';
    if( $search ){
      $where = " AND (pl.name LIKE '%$search%' OR pl.id_product LIKE '%$search%')";
    }
    if($limit){
      $limit_p = ' LIMIT '.(int)$limit;
    }
    $sql = '
			SELECT pl.name, pl.id_product as id, i.id_image, pl.link_rewrite, p.reference as ref
      FROM ' . _DB_PREFIX_ . 'product_lang as pl
      LEFT JOIN ' . _DB_PREFIX_ . 'image as i
      ON i.id_product = pl.id_product AND i.cover=1
      INNER JOIN ' . _DB_PREFIX_ . 'product as p
      ON p.id_product = pl.id_product
      WHERE pl.id_lang = ' . (int)$idLang . '
      AND pl.id_shop = ' . (int)$idShop . '
      ' . $where . $limit_p. '
			';

    $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    foreach($items as $key => $item){
      $items[$key]['image'] = str_replace('http://', Tools::getShopProtocol(), $this->context->link->getImageLink($item['link_rewrite'], $item['id_image'], ''));
    }

    die(json_encode($items));
  }


  public function displayAjax()
  {
    $json = array();
    try{


      if (Tools::getValue('action') == 'addProduct') {
        $products = Tools::getValue('products');

        if($products){
          $products = implode(",", $products);
        }

        $list = $this->getProductList($products, Tools::getValue('idLang'), Tools::getValue('idShop'));

        if(!$list){
          $json['list'] = ' ';
          $json['products'] = ' ';
        }
        else{
          $json['list'] = $list;
          $json['products'] = $products;
        }

      }

      die( json_encode($json) );
    }
    catch(Exception $e){
      $json['error'] = $e->getMessage();
      if( $e->getCode() == 10 ){
        $json['error_message'] = $e->getMessage();
      }
    }
    die( json_encode($json) );
  }

  public function getProductsByIds($id_lang, $id_shop, $productsIds){
    $sql = '
			SELECT pl.name, p.*, i.id_image, pl.link_rewrite, p.reference
      FROM ' . _DB_PREFIX_ . 'product_lang as pl
      LEFT JOIN ' . _DB_PREFIX_ . 'image as i
      ON i.id_product = pl.id_product AND i.cover=1
      INNER JOIN ' . _DB_PREFIX_ . 'product as p
      ON p.id_product = pl.id_product
      WHERE pl.id_lang = ' . (int)$id_lang . '
      AND pl.id_shop = ' . (int)$id_shop . '
      AND p.id_product IN ('.pSQL($productsIds).')
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function priceSelection($priceSettings){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'freecall/views/templates/hook/blockSelectionPrice.tpl');

    $data->assign(
      array(
        'priceSettings'   => $priceSettings,
      )
    );
    return $data->fetch();
  }


  public function quantitySelection($quantitySettings){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'freecall/views/templates/hook/blockSelectionQuantity.tpl');

    $data->assign(
      array(
        'quantitySettings'   => $quantitySettings,
      )
    );
    return $data->fetch();
  }

  public function getProductList($ids, $idLang, $idShop){

    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'freecall/views/templates/hook/productList.tpl');

    if($ids){
      $items = $this->getProductsByIds($idLang, $idShop, $ids);
      $type_img = ImageType::getImagesTypes('products');
      foreach( $type_img as $key => $val){
        $pos = strpos($val['name'], 'cart_def');
        if($pos !== false){
          $type_i = $val['name'];
        }
      }
      foreach($items as $key => $item){
        $items[$key]['image'] = str_replace('http://', Tools::getShopProtocol(), Context::getContext()->link->getImageLink($item['link_rewrite'], $item['id_image'], $type_i));
      }
    }
    else{
      $items = false;
    }

    $data->assign(
      array(
        'id_shop' => $idShop,
        'id_lang' => $idLang,
        'items'   => $items,
      )
    );

    return $data->fetch();
  }
}