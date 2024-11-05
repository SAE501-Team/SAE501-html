<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/topMenuClass.php');

class AdminTopMenuController extends ModuleAdminController
{
  private $_imgDir;

  public function __construct()
  {
    $this->className = 'topMenuClass';
    $this->table = 'top_menu';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_top_menu';
    $this->_defaultOrderBy = 'a!position';
    $this->orderBy = 'position';
    $this->_imgDir = _PS_MODULE_DIR_ . 'topmenu/views/img/';


    $this->bulk_actions = array(
      'delete' => array(
        'text' => $this->l('Delete selected'),
        'icon' => 'icon-trash',
        'confirm' => $this->l('Delete selected items?')
      )
    );

    $this->fields_list = array(
      'id_top_menu' => array(
        'title' => $this->l('ID'),
        'search' => false,
        'filter_key' => 'a!id_top_menu',
        'width' => 20
      ),
      'title' => array(
        'title' => $this->l('Label'),
        'search' => false,
        'width' =>100,
        'orderby' => true
      ),

      'active' => array(
        'title' => $this->l('Displayed'),
        'search' => false,
        'active' => 'status',
        'type' => 'bool',
        'width' => 70,
        'orderby' => true
      ),
      'position' => array(
        'title' => $this->l('Position'),
        'width' => 40,
        'search' => false,
        'filter_key' => 'a!position',
        'align' => 'left',
        'position' => 'position'
      ),
    );
  }

  public function init()
  {

    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewtop_menu') === false)
      $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
  }

  public function initProcess(){
    parent::initProcess();
  }

  public function initContent()
  {

    $this->tpl_list_vars['id_lang'] = Context::getContext()->language->id;
    $this->tpl_list_vars['id_shop'] = Context::getContext()->shop->id;
    $this->tpl_list_vars['top_menu'] = true;

    $this->tpl_list_vars['token_cont'] = Tools::getAdminTokenLite('AdminTopMenu');;
    parent::initContent();
  }

  public function postProcess()
  {
    if( Tools::getValue('deleteImage') ){

      if (Validate::isLoadedObject($object = $this->loadObject())){
        $this->deleteImage($this->_imgDir, $object->id);
      }
    }
    return parent::postProcess();
  }


  protected function uploadImage($id, $name, $dir, $ext = false, $width = null, $height = null)
  {


    if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name']))
    {
      // Delete old image
      if (Validate::isLoadedObject($object = $this->loadObject()))
        $object->deleteImage();
      else
        return false;

      // Check image validity
      $max_size = isset($this->maxImageSize) ? $this->maxImageSize : 0;
      if ($error = ImageManager::validateUpload($_FILES[$name], Tools::getMaxUploadSize($max_size)))
        $this->_errors[] = $error;
      elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES[$name]['tmp_name'], $tmpName))
        return false;
      else
      {

        $_FILES[$name]['tmp_name'] = $tmpName;
        // Copy new image
        if (!ImageManager::resize($tmpName, $dir.$id.'.png', (int)$width, (int)$height, ($ext ? $ext : 'png')))
          $this->_errors[] = Tools::displayError('An error occurred while uploading image.');

        if ($this->afterImageUpload())
        {
          unlink($tmpName);
          return true;
        }
        return false;
      }
    }
    return true;
  }

  protected function postImage($id)
  {
    $ret = $this->uploadImage($id, 'image', $this->_imgDir);
    return $ret;
  }

  public function displayAjax()
  {
    $json = array();
    try{

      if (Tools::getValue('action') == 'saveSizeSection') {
        $this->saveSizeSection(Tools::getValue('section'),Tools::getValue('id_top_menu'), Tools::getValue('type'), Tools::getValue('val'));
        $json['success'] = Module::getInstanceByName('topmenu')->l("Successfully saved!") ;
      }




      if (Tools::getValue('action') == 'saveDescription') {
        $this->saveDescription();
        $json['form'] = $this->renderTextareaBlock(Tools::getValue('id_shop'), Tools::getValue('id_top_menu'), Tools::getValue('section'));
        $json['success'] = Module::getInstanceByName('topmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'saveTitleSelection') {
        $this->saveTitleSelection(Tools::getValue('section'),Tools::getValue('id_top_menu'));
        $json['form_title'] = $this->renderTitleSelection(Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_top_menu'), Tools::getValue('section'), true);
        $json['success'] = Module::getInstanceByName('topmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'saveSelectionVal') {
        $this->saveSelectionVal(Tools::getValue('val'), Tools::getValue('section'),Tools::getValue('id_top_menu'));
        $json['success'] = Module::getInstanceByName('topmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'saveSelection') {
        $this->saveSelection(Tools::getValue('col'), Tools::getValue('val'),Tools::getValue('id_top_menu'));
      }

      if (Tools::getValue('action') == 'saveVideoCode') {
        $this->saveVideoCode(Tools::getValue('code'),Tools::getValue('id_top_menu'), Tools::getValue('section'));
        $json['success'] = Module::getInstanceByName('topmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'addCms') {
        if(Tools::getValue('type') == 'add'){
          $this->addRow(Tools::getValue('id'),Tools::getValue('id_top_menu'), Tools::getValue('section'), 'cms');
        }
        if(Tools::getValue('type') == 'remove'){
          $this->deleteRow(Tools::getValue('id'),Tools::getValue('id_top_menu'), Tools::getValue('section'), 'cms');
        }
        $json['success'] = Module::getInstanceByName('topmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'addManufacturer') {
        if(Tools::getValue('type') == 'add'){
          $this->addRow(Tools::getValue('id'),Tools::getValue('id_top_menu'), Tools::getValue('section'), 'manufacturer');
        }
        if(Tools::getValue('type') == 'remove'){
          $this->deleteRow(Tools::getValue('id'),Tools::getValue('id_top_menu'), Tools::getValue('section'), 'manufacturer');
        }
        $json['success'] = Module::getInstanceByName('topmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'addSupplier') {
        if(Tools::getValue('type') == 'add'){
          $this->addRow(Tools::getValue('id'),Tools::getValue('id_top_menu'), Tools::getValue('section'), 'supplier');
        }
        if(Tools::getValue('type') == 'remove'){
          $this->deleteRow(Tools::getValue('id'),Tools::getValue('id_top_menu'), Tools::getValue('section'), 'supplier');
        }
        $json['success'] = Module::getInstanceByName('topmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'removeLink'){
        $id_link = Tools::getValue('id');
        $this->removeLink($id_link);
        $json['success'] = Module::getInstanceByName('topmenu')->l("Successfully saved!") ;
        $json['list'] = $this->getLinksList(Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_top_menu'), Tools::getValue('section'), false);
        $json['form'] = $this->renderLinksBlock(Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_top_menu'), Tools::getValue('section'), false);
      }

      if (Tools::getValue('action') == 'saveNewLink'){
        $id_link = Tools::getValue('id');
        if(isset($id_link) && $id_link && ($id_link != 'undefined ')){
          $id = $id_link;
          $this->updateLink($id);
        }
        else{
          $id = $this->saveNewLink(Tools::getValue('id_shop'), Tools::getValue('id_lang'), false);
        }

        $json['success'] = Module::getInstanceByName('topmenu')->l("Successfully saved!") ;
        $json['list'] = $this->getLinksList(Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_top_menu'), Tools::getValue('section'), $id);
        $json['form'] = $this->renderLinksBlock(Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_top_menu'), Tools::getValue('section'), $id);
      }

      if (Tools::getValue('action') == 'editLink'){
        if(Tools::getValue('id') && Tools::getValue('id') != 'false'){
          $id = Tools::getValue('id');
        }
        else{
          $id = false;
        }
        $json['form'] = $this->renderLinksBlock(Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_top_menu'), Tools::getValue('section'), $id);
      }

      if (Tools::getValue('action') == 'saveCategories') {
        $this->saveCategories(Tools::getValue('categoryBox'),Tools::getValue('id_top_menu'), Tools::getValue('section'));
        $json['success'] = Module::getInstanceByName('topmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'addProduct') {
        $this->addProduct( Tools::getValue('id_product'),Tools::getValue('id_top_menu'), Tools::getValue('section'));
        $json['list'] = $this->getProductList(Tools::getValue('id_shop'), Tools::getValue('id_lang'),Tools::getValue('id_top_menu'), Tools::getValue('section'));
        $json['success'] = Module::getInstanceByName('topmenu')->l("Product successfully added!") ;
      }

      if (Tools::getValue('action') == 'removeProduct') {
        $id_top_menu = Tools::getValue('id_top_menu');
        $section = Tools::getValue('section');
        $id_product = Tools::getValue('id_product');
        Db::getInstance()->delete('top_menu_content', 'id_top_menu='.(int)$id_top_menu.' AND position="'.pSQL($section).'" AND type = "products" AND value='.(int)$id_product);
        $list = $this->getProductList(Tools::getValue('id_shop'), Tools::getValue('id_lang'),Tools::getValue('id_top_menu'), Tools::getValue('section'));
        if(!$list){
          $json['list'] = ' ';
        }
        else{
          $json['list'] = $list;
        }
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


  public function saveDescription(){
   $description = Tools::getValue('description');


    if( !trim($description[1]) ){
      throw new Exception( Module::getInstanceByName('topmenu')->l("Enter description!"));
    }

    foreach($description as $key => $desc){
      $descriptionMenu = $this->getDescription(Tools::getValue('id_top_menu'), Tools::getValue('section'), Tools::getValue('id_shop'), $key);
      if(!$descriptionMenu){
        if(!$desc){
          $desc = $description[1];
        }
        $base = array(
          'id_top_menu'     => (int)Tools::getValue('id_top_menu'),
          'id_shop'         => (int)Tools::getValue('id_shop'),
          'id_lang'         => (int)$key,
          'position'         => pSQL(Tools::getValue('section')),
          'description'     =>  pSQL($desc, true),
        );
        Db::getInstance()->insert('top_menu_description_lang', $base);
      }
      else{
        if(!$desc){
          $desc = $description[1];
        }
        $base = array(
          'description'     =>  pSQL($desc, true),
        );
        Db::getInstance()->update('top_menu_description_lang', $base, 'id_top_menu='.(int)Tools::getValue('id_top_menu').' AND id_shop='.(int)Tools::getValue('id_shop').' AND id_lang='.(int)$key.' AND position="'.pSQL(Tools::getValue('section')).'"');
      }

    }

  }

  public function getDescription($id_top_menu, $position, $id_shop, $id_lang = false){

    $where = ' ';

    if($id_lang){
      $where = ' AND p.id_lang='.(int)$id_lang;
    }

    $sql = '
      SELECT  *
        FROM ' . _DB_PREFIX_ . 'top_menu_description_lang p
        WHERE p.id_top_menu = ' . (int)$id_top_menu . '
        AND p.position = "' . pSQL($position) . '"
        AND p.id_shop = '.(int)$id_shop.'
        '.$where.'
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function saveTitleSelection($section, $id){

    $field = 'title_left_selection';
    if($section == 'left'){
      $field = 'title_left_selection';
    }
    if($section == 'main'){
      $field = 'title_main_selection';
    }
    if($section == 'right'){
      $field = 'title_right_selection';
    }
    if($section == 'botton'){
      $field = 'title_botton_selection';
    }

    foreach(Language::getLanguages() as $language){
      $val = Tools::getValue('title_selection_'.$language['id_lang']);
      if(!$val && Tools::getValue('title_selection_1')){
        $val = Tools::getValue('title_selection_1');
      }
      $base = array(
        'id_lang'              => (int)$language['id_lang'],
         $field                =>  pSQL($val),
      );
      Db::getInstance()->update('top_menu_lang', $base, 'id_top_menu='.(int)$id .' AND id_lang='.$language['id_lang']);
    }
  }



  public function saveSizeSection($section, $id, $type, $val){

    if(!Validate::isInt($val)){
      throw new Exception ( Module::getInstanceByName('topmenu')->l("Error size!"));
    }

    $field = $section.'_'.$type;
    $base = array(
      $field                =>  pSQL($val),
    );
    Db::getInstance()->update('top_menu', $base, 'id_top_menu='.(int)$id);

  }

  public function saveSelectionVal($val, $section, $id) {
    $save = array(
      $section.'_selection_val'           => pSQL($val),
    );
    Db::getInstance()->update('top_menu', $save, 'id_top_menu='.(int)$id);
  }


  public function saveSelection($col, $val, $id){
    $save = array(
      $col           => (int)$val,
    );
    Db::getInstance()->update('top_menu', $save, 'id_top_menu='.(int)$id);
  }



  public function saveVideoCode($code, $id_top_menu, $section)
  {
    if( !$code ){
      throw new Exception ( Module::getInstanceByName('topmenu')->l("Enter the video code!"));
    }

    $video = $this->issetVideoCode($id_top_menu, $section, 'video');

    if(isset($video[0]['id']) && $video[0]['id']){
      $base = array( 'value' => ($code));
      Db::getInstance()->update('top_menu_content', $base, 'id='.$video[0]['id']);

    }
    else{
      $base = array(
        'id_top_menu'       => (int)$id_top_menu,
        'position'          =>  pSQL($section),
        'type'              => 'video',
        'value'             =>  pSQL($code, true),
      );
      Db::getInstance()->insert('top_menu_content', $base);
    }
  }

  public function removeLink($id_link){
    Db::getInstance()->delete('top_menu_link', 'id_top_menu_link='.(int)$id_link);
    Db::getInstance()->delete('top_menu_link_lang', 'id_top_menu_link='.(int)$id_link);
  }


  public function addRow($val, $id_top_menu, $section, $type){
    $base = array(
      'id_top_menu'       => (int)$id_top_menu,
      'position'          =>  pSQL($section),
      'type'              =>  $type,
      'value'             => (int)$val,
    );
    Db::getInstance()->insert('top_menu_content', $base);
  }

  public function deleteRow($val, $id_top_menu, $section, $type){
    Db::getInstance()->delete('top_menu_content', 'id_top_menu='.(int)$id_top_menu.' AND position="'.pSQL($section).'" AND type="'.$type.'" AND value='.$val);
  }

  public function saveCategories($categories, $id_top_menu, $section)
  {
    if( !$categories ){
      throw new Exception ( Module::getInstanceByName('topmenu')->l("Select categories!"));
    }
    Db::getInstance()->delete('top_menu_content', 'id_top_menu='.(int)$id_top_menu.' AND position="'.pSQL($section).'" AND type = "categories"');
    foreach( $categories as $val){
      if($val){
        $base = array(
          'id_top_menu'       => (int)$id_top_menu,
          'position'          =>  pSQL($section),
          'type'              => 'categories',
          'value'             => (int)$val,
        );
        Db::getInstance()->insert('top_menu_content', $base);
      }
    }
  }

  public function getLinksList($id_shop, $id_lang, $id_top_menu, $section, $id = false)
  {
    $allLinks = $this->getLinks($id_shop, $id_lang, $id_top_menu, $section);
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'topmenu/views/templates/hook/linksList.tpl');
    $data->assign(
      array(
        'id_shop'     => $id_shop,
        'id_lang'     => $id_lang,
        'allLinks'    => $allLinks,
      )
    );
    return $data->fetch();
  }

  public function getProductList($id_shop, $id_lang, $id_top_menu, $section)
  {
    $products = $this->getSelectedIdProduct($id_top_menu, $section);
    if(isset($products[0]['id']) && $products[0]['id']){
      $ids = $products[0]['id'];
    }
    else{
      return false;
    }


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
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'topmenu/views/templates/hook/productList.tpl');
    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'items'   => $items,
      )
    );
    return $data->fetch();
  }

  public function addProduct( $id_product, $id_top_menu, $section)
  {
    if( !$id_product ){
      throw new Exception ( Module::getInstanceByName('topmenu')->l("Select product!"));
    }
    $isset_product_db = $this->issetProduct($id_product, $id_top_menu, $section);
    if($isset_product_db){
      throw new Exception ( Module::getInstanceByName('topmenu')->l("Product already added!"));
    }
    $base = array(
      'id_top_menu'       => (int)$id_top_menu,
      'position'          =>  pSQL($section),
      'type'              => 'products',
      'value'             => (int)$id_product,
    );
    Db::getInstance()->insert('top_menu_content', $base);
  }


  public function issetProduct($id_product, $id_top_menu, $section){
    $sql = '
      SELECT  *
        FROM ' . _DB_PREFIX_ . 'top_menu_content p
        WHERE p.id_top_menu = ' . (int)$id_top_menu . '
        AND p.position = "' . pSQL($section) . '"
        AND p.type = "products"
        AND p.value = '.$id_product.'
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getSelectedIdProduct($id_top_menu, $section){
    $sql = '
			SELECT  GROUP_CONCAT(DISTINCT(p.value) )  as id
        FROM ' . _DB_PREFIX_ . 'top_menu_content p
        WHERE p.id_top_menu = ' . (int)$id_top_menu . '
        AND p.position = "' . pSQL($section) . '"
        AND p.type = "products"
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getSelectedCategories($id_top_menu, $section){
    $sql = '
			SELECT  p.value
        FROM ' . _DB_PREFIX_ . 'top_menu_content p
        WHERE p.id_top_menu = ' . (int)$id_top_menu . '
        AND p.position = "' . pSQL($section) . '"
        AND p.type = "categories"
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function getProductsByIds( $id_lang = false, $id_shop = false, $products = false)
  {
    $sql = '
			SELECT pl.name, pl.id_product as id, i.id_image, pl.link_rewrite
      FROM ' . _DB_PREFIX_ . 'product_lang as pl
      LEFT JOIN ' . _DB_PREFIX_ . 'image as i
      ON i.id_product = pl.id_product AND i.cover=1
      WHERE pl.id_product IN ('.pSQL($products).')
      AND pl.id_lang = ' . (int)$id_lang . '
      AND pl.id_shop = ' . (int)$id_shop . '
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function updateLink($id){

    if( !trim(Tools::getValue('title_new_link_1')) ){
      throw new Exception('1');
    }
    if( !trim(Tools::getValue('new_link')) ){
      throw new Exception('2');
    }

    $new_link = array(
      'link'           => pSQL(Tools::getValue('new_link')),
    );

    Db::getInstance()->update('top_menu_link', $new_link, 'id_top_menu_link='.(int)$id);

    foreach(Language::getLanguages() as $language){

      $val = Tools::getValue('title_new_link_'.$language['id_lang']);
      if(!$val){
        $val = Tools::getValue('title_new_link_1');
      }
      $base = array(
        'id_lang'              => (int)$language['id_lang'],
        'title'                =>  pSQL($val),
      );
      Db::getInstance()->update('top_menu_link_lang', $base, 'id_top_menu_link='.(int)$id .' AND id_lang='.$language['id_lang']);
    }
  }

  public function saveNewLink($id_shop, $id_lang)
  {

    if( !trim(Tools::getValue('title_new_link_1')) ){
      throw new Exception('1');
    }
    if( !trim(Tools::getValue('new_link')) ){
      throw new Exception('2');
    }

    $new_link = array(
      'link'           => pSQL(Tools::getValue('new_link')),
      'id_top_menu'    => (int)Tools::getValue('id_top_menu'),
      'position'       => pSQL(Tools::getValue('section')),
    );
    Db::getInstance()->insert('top_menu_link', $new_link);
    $id = (int)Db::getInstance()->Insert_ID();


    foreach(Language::getLanguages() as $language){

        $val = trim(Tools::getValue('title_new_link_'.$language['id_lang']));
        if(!$val){
          $val = trim(Tools::getValue('title_new_link_1'));
        }
        $base = array(
          'id_top_menu_link'     => (int)$id,
          'id_shop'              => (int)$id_shop,
          'id_lang'              => (int)$language['id_lang'],
          'title'                =>  pSQL($val),
        );
        Db::getInstance()->insert('top_menu_link_lang', $base);
    }

    return $id;
  }


  public function getLinks($id_shop, $id_lang, $id_top_menu, $position){

    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'top_menu_link as tm
      LEFT JOIN ' . _DB_PREFIX_ . 'top_menu_link_lang as tml
      ON tm.id_top_menu_link = tml.id_top_menu_link
      WHERE tml.id_lang = ' . (int)$id_lang . '
      AND tml.id_shop = ' . (int)$id_shop . '
      AND tm.id_top_menu = '. (int)$id_top_menu .'
      AND tm.position = "'. pSQL($position) .'"

			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getLink($id_shop, $id_lang, $id_top_menu, $position, $id){

    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'top_menu_link as tm
      LEFT JOIN ' . _DB_PREFIX_ . 'top_menu_link_lang as tml
      ON tm.id_top_menu_link = tml.id_top_menu_link
      WHERE tml.id_shop = ' . (int)$id_shop . '
      AND tm.id_top_menu = '. (int)$id_top_menu .'
      AND tm.position = "'. pSQL($position) .'"
      AND tm.id_top_menu_link = '. (int)$id .'

			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function renderForm()
  {
    $class_cont = ' ';
    $class_custom = ' ';
    $res = $this->getSelection(Tools::getValue('id_top_menu'));

    if(isset($res[0]) && $res[0]){
      $res = $res[0];
    }


    if(isset($res['content']) && $res['content']){
      $class_cont = ' active_block';
    }

    if(isset($res['custom']) && $res['custom']){
      $class_custom = ' active_block';
    }


    $obj = $this->loadObject(true);
    $image = $this->_imgDir.$obj->id.'.png';

    $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$obj->id.'.png', 350, 'png', true, true);

    $image_size = file_exists($image) ? filesize($image) / 1000 : false;

    $this->fields_form = array(
      'legend' => array(
        'title' => $this->l('Top menu'),
        'icon' => 'icon-plus-sign-alt'
      ),
      'input' => array(
        array(
          'type' => 'switch',
          'label' => $this->l('Active'),
          'name' => 'active',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'display_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Narrow pull-down menu'),
          'name' => 'narrow',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'narrow_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'narrow_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Width pull-down menu'),
          'name' => 'width',
          'required' => true,
          'form_group_class'=> 'widthForm',
          'class' => 'widthNarrow',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Height pull-down menu'),
          'name' => 'height',
          'required' => true,
          'form_group_class'=> 'heightForm',
          'class' => 'heightNarrow',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Label'),
          'name' => 'title',
          'lang' => true,
          'required' => true,
          'class' => 'title',
        ),
        array(
          'type'              => 'file',
          'label'             => $this->l('Background'),
          'form_group_class'  => 'uploadImagesFormGroup',
          'image'             => $image_url ? $image_url : false,
          'name'              => 'image',
          'size'              => $image_size,
          'delete_url'        => self::$currentIndex.'&'.$this->identifier.'='.(int)$obj->id.'&token='.$this->token.'&deleteImage=1',
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Custom link'),
          'name' => 'custom',
          'form_group_class'=> 'custom-link title-form',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'custom_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'custom_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),

        array(
          'type' => 'text',
          'label' => $this->l('Link'),
          'name' => 'link',
          'form_group_class'=> 'linkFormGroup'.$class_custom,
          'required' => true,
          'class' => 'link',
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Content'),
          'name' => 'content',
          'form_group_class'=> 'content-link title-form',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'content_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'content_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'main_section_block'.$class_cont,
          'html_content' => '<div class="main_section_cont">'. $this->_positionBlock($res) .'</div>',
        ),
      ),
      'buttons' => array(
        'save-and-stay' => array(
          'title' => $this->l('Save and stay'),
          'name' => 'submitAdd'.$this->table.'AndStay',
          'type' => 'submit',
          'class' => 'btn btn-default pull-right',
          'icon' => 'process-icon-save'
        )
      ),
      'submit' => array(
        'title' => $this->l('Save')
      )
    );


    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');

    return parent::renderForm();
  }


  public function renderList()
  {
    $this->addRowAction('edit');
    $this->addRowAction('delete');

    return parent::renderList();
  }


  public function ajaxProcessUpdatePositions()
  {
    $top_menu = Tools::getValue('top_menu');
    foreach($top_menu as $key => $value){
      $value = explode('_', $value);
      Db::getInstance()->update('top_menu', array('position' => (int)$key), 'id_top_menu='.(int)$value[2]);
    }
  }


  public function renderTitleSelection($id_shop, $id_lang, $id_top_menu, $section, $save = false)
  {
    $obj = new topMenuClass($id_top_menu);
    $val = array();
    $val_w = 0;
    $val_h = 0;
    if($save){

      $field = 'title_left_selection';

      if($section == 'left'){
        $field = 'title_left_selection';
        $val_w = $obj->left_width;
        $val_h = $obj->left_height;
      }

      if($section == 'main'){
        $field = 'title_main_selection';
        $val_w = $obj->main_width;
        $val_h = $obj->main_height;
      }

      if($section == 'right'){
        $field = 'title_right_selection';
        $val_w = $obj->right_width;
        $val_h = $obj->right_height;
      }

      if($section == 'botton'){
        $field = 'title_botton_selection';
        $val_w = $obj->botton_width;
        $val_h = $obj->botton_height;
      }

      $saved = $this->getTitleSection($id_shop, $id_top_menu);


      if($saved){
        foreach($saved as $sav){
          $val[$sav['id_lang']] = $sav[$field];
        }
      }
      else{
        foreach(Language::getLanguages() as $sav){
          $val[$sav['id_lang']] = ' ';
        }
      }



    }


    $this->fields_form = array(
      'input' => array(
        array(
          'type' => 'text',
          'label' => $this->l('Title Section'),
          'name' => 'title_selection',
          'lang' => true,
          'class' => 'title',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Width Section'),
          'name' => 'width_section',
          'class' => 'input_width_160',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Height Section'),
          'name' => 'height_section',
          'class' => 'input_width_160',
        ),
      ),
    );
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
    $this->fields_value['title_selection'] = $val;
    $this->fields_value['width_section'] = $val_w;
    $this->fields_value['height_section'] = $val_h;
    return parent::renderForm();
  }





  public function renderTextareaBlock($id_shop, $id_top_menu, $section)
  {
    $val = array();
    $saved = $this->getDescription($id_top_menu, $section, $id_shop, false);
    if($saved){
      foreach($saved as $sav){
        $val[$sav['id_lang']] = $sav['description'];
      }
    }
    else{
      foreach(Language::getLanguages() as $sav){
        $val[$sav['id_lang']] = ' ';
      }
    }

    $this->fields_value['text'] = $val;

    $this->fields_form = array(
      'tinymce' => true,
      'legend' => array(
        'title' => $this->l('ADD DESCRIPTION'),
        'icon' => 'icon-plus-sign-alt'
      ),
      'input' => array(
        array(
          'type' => 'textarea',

          'name' => 'text',
          'lang' => true,
          'class' => 'text_cont',
          'autoload_rte' => true,
          'rows' => 5,
          'cols' => 40,
          'hint' => $this->l('Invalid characters:').' <>;=#{}'
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_button_description',
          'html_content' => '<button type="button" id="save_description" class="btn btn-default">'.$this->l('Save').'</button>',
        ),
      ),
    );
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
    return parent::renderForm();
  }

  public function renderCategoriesBlock($id_top_menu, $section)
  {
    $categories_home = new HelperTreeCategoriesCore('associated-categories-home-tree', $this->l('Select categories'));
    $categories_home->setUseCheckBox(1)->setUseSearch(1);

    $cat = array();
    $selected = array();
    $cat = $this->getSelectedCategories($id_top_menu, $section);

    foreach($cat as $v){
      if($v['value']){
        $selected[] = $v['value'];
      }
    }

    $categories_home->setSelectedCategories($selected?$selected:array());
    $this->fields_form = array(
      'legend' => array(
        'title' => $this->l('ADD CATEGORIES'),
        'icon' => 'icon-plus-sign-alt'
    ),
      'id_form' => 'formCategoriesBlock',
      'input' => array(
        array(
          'type' => 'html',
          'name' => 'html_data',
          'tab' => 'homepage',
          'html_content' => '<div class="categories_homepage ">'. $categories_home->render() .'</div>',
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_button_cat',
          'html_content' => '<button type="button" id="save_categories" class="btn btn-default">'.$this->l('Save').'</button>',
        ),
      ),
    );
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
    return parent::renderForm();
  }


  public function renderLinksBlock($id_shop, $id_lang, $id_top_menu, $section, $id = false)
  {
    $ids = array();
    if($id){

      $links = $this->getLink($id_shop, $id_lang, $id_top_menu, $section, $id);
      if($links){

        foreach($links as $link){
          $ids[$link['id_lang']] = $link['title'];
        }
        $this->fields_value['title_new_link'] = $ids;
        $this->fields_value['new_link'] = $links[0]['link'];
      }

    }
    else{
      foreach(Language::getLanguages() as $language){
        $ids[$language['id_lang']] = ' ';
      }
      $this->fields_value['title_new_link'] = $ids;
      $this->fields_value['new_link'] = ' ';
    }

    $this->fields_form = array(
      'legend' => array(
        'title' => $this->l('ADD LINKS'),
        'icon' => 'icon-plus-sign-alt'
      ),
      'id_form' => 'formLinksBlock',
      'input' => array(
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_button_add_new',
          'html_content' => '<a><i class="process-icon-new"></i>'.$this->l('add new link').'</a>',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Title link'),
          'name' => 'title_new_link',
          'lang' => true,
          'class' => 'title_new_link',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Link'),
          'name' => 'new_link',
          'class' => 'new_link',
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_button',
          'html_content' => '<button type="button" data-id-link="'.$id.'" id="add_links" class="btn btn-default">'.$this->l('Save').'</button>',
        ),
      ),

    );
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
    return parent::renderForm();
  }

  private function _positionBlock($res)
  {
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'topmenu/views/templates/hook/contentSelection.tpl');

    $data->assign(
      array(
        'id_shop' => Context::getContext()->shop->id,
        'id_lang' => Context::getContext()->language->id,
        'token'   => Tools::getAdminTokenLite('AdminTopMenu'),
        'config'  => $res,

      )
    );
    return $data->fetch();
  }


  public function getSelection($id_top_menu){
    $sql = '
			SELECT *
        FROM ' . _DB_PREFIX_ . 'top_menu tm
        WHERE tm.id_top_menu = ' . (int)$id_top_menu . '
        AND tm.active = 1

			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
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


  public function ajaxProcessShowSettings(){
    $json = array();
    try{
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'topmenu/views/templates/hook/contentSettings.tpl');
    $section = Tools::getValue('section');
    $id_shop = Tools::getValue('id_shop');
    $id_lang = Tools::getValue('id_lang');
    $id_top_menu = Tools::getValue('id_top_menu');

    if(!$id_top_menu){
      throw new Exception ( Module::getInstanceByName('topmenu')->l("Before proceeding, you must save item!"));
    }

    $list = false;
    if($section != 'botton'){
      $list = $this->getProductList($id_shop, $id_lang, $id_top_menu, $section);
    }


    $linkList = $this->getLinksList($id_shop, $id_lang, $id_top_menu, $section);

    $selected_c = array();
    $cms_selected = $this->getSelectedId($id_top_menu, $section, 'cms');

    $selected_m = array();
    $manufacturer_selected = $this->getSelectedId($id_top_menu, $section, 'manufacturer');

    $selected_s = array();
    $supplier_selected = $this->getSelectedId($id_top_menu, $section, 'supplier');

    foreach($cms_selected as $v){
      if($v['id']){
        $selected_c[] = $v['id'];
      }
    }

    foreach($manufacturer_selected as $v){
      if($v['id']){
        $selected_m[] = $v['id'];
      }
    }

    foreach($supplier_selected as $v){
      if($v['id']){
        $selected_s[] = $v['id'];
      }
    }

    $cms_all =  CMS::getCMSPages($this->context->language->id, 0, true, $this->context->shop->id);
    foreach($cms_all as $key => $value){
      if(in_array($value['id_cms'], $selected_c)){
        $cms_all[$key]['is_selected'] = true;
      }
      else{
        $cms_all[$key]['is_selected'] = false;
      }
    }

    $manufacturer_all =  Manufacturer::getManufacturers(false, Context::getContext()->language->id, true, false, false, false, true );
    foreach($manufacturer_all as $key => $value){
      if(in_array($value['id_manufacturer'], $selected_m)){
        $manufacturer_all[$key]['is_selected'] = true;
      }
      else{
        $manufacturer_all[$key]['is_selected'] = false;
      }
    }

    $supplier_all =  Supplier::getSuppliers(false, Context::getContext()->language->id);
    foreach($supplier_all as $key => $value){
      if(in_array($value['id_supplier'], $selected_s)){
        $supplier_all[$key]['is_selected'] = true;
      }
      else{
        $supplier_all[$key]['is_selected'] = false;
      }
    }

    $video = $this->getVideoCode($id_top_menu, $section, 'video');
    if(isset($video[0]['value']) && $video[0]['value']){
      $video_code = $video[0]['value'];
    }
    else{
      $video_code = false;
    }


    $res = $this->getSelection($id_top_menu);

    if(isset($res[0]) && $res[0]){
      $res = $res[0];
    }

    if($section == 'left'){
      $config = array('active' => $res['left_selection'], 'val' => $res['left_selection_val']);
    }

    if($section == 'main'){
      $config = array('active' => $res['main_selection'], 'val' => $res['main_selection_val']);
    }

    if($section == 'right'){
      $config = array('active' => $res['right_selection'], 'val' => $res['right_selection_val']);
    }

    if($section == 'botton'){
      $config = array('active' => $res['botton_selection'], 'val' => $res['botton_selection_val']);
    }

    $data->assign(
      array(
        'section' => $section,
        'selected_c' => $selected_c,
        'list' => $list,
        'linkList' => $linkList,
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'token' => Tools::getAdminTokenLite('AdminTopMenu'),
        'titleSelection'    => $this->renderTitleSelection($id_shop, $id_lang, $id_top_menu, $section, true),
        'textareaBlock'    => $this->renderTextareaBlock($id_shop, $id_top_menu, $section),
        'categoriesBlock'    => $this->renderCategoriesBlock($id_top_menu, $section),
        'renderLinksBlock'    => $this->renderLinksBlock($id_shop, $id_lang, $id_top_menu, $section, false),
        'manufacturers'    => $manufacturer_all,
        'suppliers'    =>   $supplier_all,
        'cms_all'    =>  $cms_all,
        'video_code' => $video_code,
        'config'     => $config,
      )
    );
      $json['success'] = $data->fetch();

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


  public function getSelectedId($id_top_menu, $section, $type){
    $sql = '
			SELECT  p.value as id
        FROM ' . _DB_PREFIX_ . 'top_menu_content p
        WHERE p.id_top_menu = ' . (int)$id_top_menu . '
        AND p.position = "' . pSQL($section) . '"
        AND p.type = "'.pSQL($type).'"
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function getVideoCode($id_top_menu, $section, $type){
    $sql = '
			SELECT  p.value
        FROM ' . _DB_PREFIX_ . 'top_menu_content p
        WHERE p.id_top_menu = ' . (int)$id_top_menu . '
        AND p.position = "' . pSQL($section) . '"
        AND p.type = "'.pSQL($type).'"
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function issetVideoCode($id_top_menu, $section, $type){
    $sql = '
			SELECT  p.id
        FROM ' . _DB_PREFIX_ . 'top_menu_content p
        WHERE p.id_top_menu = ' . (int)$id_top_menu . '
        AND p.position = "' . pSQL($section) . '"
        AND p.type = "'.pSQL($type).'"
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function getTitleSection($id_shop, $id_top_menu){
    $sql = '
			SELECT  *
        FROM ' . _DB_PREFIX_ . 'top_menu_lang p
        WHERE p.id_top_menu = ' . (int)$id_top_menu . '
        AND p.id_shop = "' . (int)$id_shop . '"
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }
  protected function deleteImage($image, $id)
  {
    $file_name = $image.$id.'.png';
    if (realpath(dirname($file_name)) != realpath($image))
      Tools::dieOrLog(sprintf('Could not find upload directory'));

    if ($image != '' && is_file($file_name)){
      unlink($file_name);
    }
  }

}