<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/blockFeatured.php');

class AdminHomeFeaturedController extends ModuleAdminController
{
  private $_idShop;
  private $_idLang;
  private $_homeFeatured;
  protected $position_identifier = 'id_homefeatured';

  public function __construct()
  {
    $this->className = 'blockFeatured';
    $this->table = 'homefeatured';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_homefeatured';
    $this->_defaultOrderBy = 'a!position';
    $this->orderBy = 'position';

    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
    $this->_homeFeatured = new blockFeatured();

    $this->bulk_actions = array(
      'delete' => array(
        'text' => $this->l('Delete selected'),
        'icon' => 'icon-trash',
        'confirm' => $this->l('Delete selected items?')
      )
    );

    $this->fields_list = array(
      'id_homefeatured' => array(
        'title' => $this->l('ID'),
        'search' => true,
        'onclick' => false,
        'filter_key' => 'a!id_homefeatured',
        'width' => 20
      ),
      'title' => array(
        'title' => $this->l('Title'),
        'filter_key' => 'b!title',
        'search' => true,
        'width' =>100,
        'align' => 'left',
      ),
      'type' => array(
        'title' => $this->l('Type'),
        'align' => 'center',
        'orderby' => false,
        'filter' => false,
        'search' => false,
        'align' => 'left',
        'callback' => 'getTypeName',
      ),
      'active' => array(
        'title' => $this->l('Displayed'),
        'search' => true,
        'active' => 'status',
        'type' => 'bool',
        'width' => 20,
      ),
      'position' => array(
        'title' => $this->l('Position'),
        'width' => 40,
        'search' => false,
        'filter_key' => 'a!position',
        'align' => 'left',
        'position' => 'position'
      ),
      'date_add' => array(
        'title' => $this->l('Date add'),
        'maxlength' => 190,
        'width' =>100,
        'align' => 'left',
      )
    );
  }

  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewhomefeatured') === false)
      $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
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

  public function renderForm(){

    $class = 'html_content';
    $content = '';
    $obj = $this->loadObject(true);

    if($obj->type == 'products'){
      $content = $this->getProductBlock($this->_idLang, $this->_idShop, $obj->ids_products, $obj->id_homefeatured);
      $class .= ' html_content_products';
    }

    if($obj->type == 'category'){
      $content = $this->getCategoryBlock($this->_idLang, $this->_idShop, $obj->ids_categories);
      $class .= ' html_content_categories';
    }

    $show = array(
      array(
        'id' => 'all',
        'val' => 'all',
        'name' => $this->l('All')
      ),
      array(
        'id' => 'category',
        'val' => 'category',
        'name' => $this->l('Select categories')
      ),
      array(
        'id' => 'products',
        'val' => 'products',
        'name' => $this->l('Select products')
      ),
      array(
        'id' => 'last_visited',
        'val' => 'last_visited',
        'name' => $this->l('Last visited products')
      ),
      array(
        'id' => 'discount',
        'val' => 'discount',
        'name' => $this->l('Products with discount')
      ),
      array(
        'id' => 'selling',
        'val' => 'selling',
        'name' => $this->l('Best selling')
      ),
      array(
        'id' => 'new',
        'val' => 'new',
        'name' => $this->l('New products')
      ),

    );

    $this->fields_form = array(
      'legend' => array(
        'title' => $this->l('Settings'),
        'icon' => 'icon-cogs'
      ),
      'input' => array(
        array(
          'type' => 'switch',
          'label' => $this->l('Active'),
          'name' => 'active',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'active_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'active_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Title'),
          'name' => 'title',
          'required' => true,
          'lang' => true,
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Show'),
          'name' => 'type',
          'class' => 'type_content',
          'options' => array(
            'query' => $show,
            'id' => 'id',
            'name' => 'name'
          )
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> $class,
          'html_content' => $content,
        ),
        array(
          'type' => 'hidden',
          'name' => 'idLang',
        ),
        array(
          'type' => 'hidden',
          'name' => 'token_featured',
        ),
        array(
          'type' => 'hidden',
          'name' => 'idShop',
        ),
      ),
      'submit' => array(
        'title' => $this->l('Save'),
      ),
      'buttons' => array(
        'save-and-stay' => array(
          'title' => $this->l('Save and stay'),
          'name' => 'submitAdd'.$this->table.'AndStay',
          'type' => 'submit',
          'class' => 'btn btn-default pull-right',
          'icon' => 'process-icon-save'
        ),
      ),
    );

    $this->fields_value['idLang'] = $this->_idLang;
    $this->fields_value['idShop'] = $this->_idShop;
    $this->fields_value['token_featured'] = Tools::getAdminTokenLite('AdminHomeFeatured');
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');

    return parent::renderForm();
  }

  public function ajaxProcessUpdatePositions()
  {
    $homefeatured = Tools::getValue('homefeatured');
    foreach($homefeatured as $key => $value){
      $value = explode('_', $value);
      Db::getInstance()->update('homefeatured', array('position' => (int)$key), 'id_homefeatured='.(int)$value[2]);
    }
  }

  public function getProductBlock($id_lang, $id_shop, $value = false, $id_homefeatured){

    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'blockhomefeatured/views/templates/hook/productBlock.tpl');
    $content = $this->getProductList($id_homefeatured, $id_lang, $id_shop);
    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'content' => $content,
      ));
    return $data->fetch();
  }

  public function getCategoryBlock($id_lang, $id_shop, $value = false){

    $selected_cat = array();
    if($value){
      $selected_cat = explode(',',$value);
    }
    $this->fields_form['input'][] = array(
      'type' => 'categories',
      'name' => 'categoryBox',
      'form_group_class'=> 'categoryBoxFearured',
      'label' => '',
      'tree' => array(
        'id' => 'categories-tree-home',
        'selected_categories' => $selected_cat,
        'root_category' => 2,
        'use_search' => false,
        'use_checkbox' => true
      ),
    );
    return parent::renderForm();
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
      if (Tools::getValue('action') == 'showForm') {
        $id_homefeatured = Tools::getValue('id_homefeatured');
        $id_lang = Tools::getValue('id_lang');
        $id_shop = Tools::getValue('id_shop');
        $type = Tools::getValue('type');
        $obj = new blockFeatured($id_homefeatured);
        $content = '';

        if($type == 'products' ){
          $content =  $this->getProductBlock($id_lang, $id_shop, $obj->ids_products, $obj->id_homefeatured);
        }

        if($type == 'category' ){
          $content = $this->getCategoryBlock($id_lang, $id_shop, $obj->ids_categories);
        }

        $json['list'] = $content;
      }

      if (Tools::getValue('action') == 'changeCategories') {
        $id_homefeatured = Tools::getValue('id_homefeatured');
        $id_category = Tools::getValue('id_category');
        $type = Tools::getValue('type');
        $res = $this->changeCategories($id_homefeatured, $id_category, $type);
        if($type){
          $json['success'] = Module::getInstanceByName('blockhomefeatured')->l("Category successfully added!") ;
        }
        else{
          $json['success'] = Module::getInstanceByName('blockhomefeatured')->l("Category successfully removed!") ;
        }
      }

      if (Tools::getValue('action') == 'addProduct') {
        $id_homefeatured = Tools::getValue('id_homefeatured');
        if(!$id_homefeatured){
          throw new Exception ( Module::getInstanceByName('blockhomefeatured')->l("Before adding products you must save item!"));
        }
        $id_product = Tools::getValue('id_product');
        $id_lang = Tools::getValue('id_lang');
        $id_shop = Tools::getValue('id_shop');
        $res = $this->addProduct($id_homefeatured, $id_product, $id_lang, $id_shop);
        if($res){
          $json['list'] = $this->getProductList($id_homefeatured, $id_lang, $id_shop);
          $json['success'] = Module::getInstanceByName('blockhomefeatured')->l("Product successfully added!") ;
        }
      }

      if (Tools::getValue('action') == 'removeProduct') {
        $id_homefeatured = Tools::getValue('id_homefeatured');
        $id_product = Tools::getValue('id_product');
        $id_lang = Tools::getValue('id_lang');
        $id_shop = Tools::getValue('id_shop');
        $this->removeProduct($id_homefeatured, $id_product);
        $json['list'] = $this->getProductList($id_homefeatured, $id_lang, $id_shop);
        $json['success'] = Module::getInstanceByName('topmenu')->l("Product successfully removed!") ;
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

  public function changeCategories($id_homefeatured, $id_category, $type){

    if(!$id_homefeatured){
      throw new Exception ( Module::getInstanceByName('blockhomefeatured')->l("Before adding categories you must save item!"));
    }
    $obj = new blockFeatured($id_homefeatured);
    if($obj->ids_categories){
      $val = explode(',', $obj->ids_categories);
      if($type){
        $val[] = $id_category;
        $obj->ids_categories = implode(',', $val);
        $obj->update();
      }
      else{
        $key = array_search($id_category, $val);
        unset ($val[$key]);
        $obj->ids_categories = implode(',', $val);
        $obj->update();
      }
    }
    else{
      $obj->ids_categories = (int)$id_category;
      $obj->update();
    }
    return true;
  }

  public function addProduct($id_homefeatured, $id_product, $id_lang, $id_shop)
  {
    if( !$id_product ){
      throw new Exception ( Module::getInstanceByName('blockhomefeatured')->l("Select product!"));
    }
    $obj = new blockFeatured($id_homefeatured);
    if($obj->ids_products){
      $val = explode(',', $obj->ids_products);
      if (in_array($id_product, $val)) {
        throw new Exception ( Module::getInstanceByName('blockhomefeatured')->l("Product already added!"));
      }
      else{
        $val[] = $id_product;
        $obj->ids_products = implode(',', $val);
        $obj->update();
      }
    }
    else{
      $obj->ids_products = (int)$id_product;
      $obj->update();
    }
    return true;
  }

  public function getProductList($id_homefeatured, $id_lang, $id_shop)
  {
    $obj = new blockFeatured($id_homefeatured);
    $form = ' ';
    if($obj->ids_products){
      $ids = $obj->ids_products;
      $items = $this->getProductsByIds($id_lang, $id_shop, $ids);
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
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'blockhomefeatured/views/templates/hook/productList.tpl');
    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'items'   => $items,
      )
    );
    return $form.$data->fetch();
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

  public function removeProduct($id_homefeatured, $id_product){
    $obj = new blockFeatured($id_homefeatured);
    if($obj->ids_products){
      $val = explode(',', $obj->ids_products);
      $key = array_search($id_product, $val);
      unset ($val[$key]);
      $obj->ids_products = implode(',', $val);
      $obj->update();
    }
    return true;
  }

  public function getTypeName($type){

    $type_name = '';

    if($type == 'all'){
      $type_name = $this->l('All products');
    }

    if($type == 'category'){
      $type_name = $this->l('Selected category');
    }

    if($type == 'products'){
      $type_name = $this->l('Selected products');
    }

    if($type == 'last_visited'){
      $type_name = $this->l('Last visited products');
    }

    if($type == 'discount'){
      $type_name = $this->l('Products with discount');
    }

    if($type == 'selling'){
      $type_name = $this->l('Best selling');
    }

    if($type == 'new'){
      $type_name = $this->l('New products');
    }


    return $type_name;
  }

}