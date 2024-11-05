<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/leftMenuItem.php');

class AdminLeftMenuItemController extends ModuleAdminController
{
  private $_imgDir;
  private $_imgDirIcon;
  private $_idShop;
  private $_idLang;

  public function __construct()
  {
    $this->className = 'leftMenuItem';
    $this->table = 'left_menu';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();

    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_left_menu';
    $this->_imgDir = _PS_MODULE_DIR_ . 'leftmenu/views/img/background/';
    $this->_imgDirIcon = _PS_MODULE_DIR_ . 'leftmenu/views/img/icon/';
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;

    $this->bulk_actions = array(
      'delete' => array(
        'text' => $this->l('Delete selected'),
        'icon' => 'icon-trash',
        'confirm' => $this->l('Delete selected items?')
      )
    );

    $this->fields_list = array(
      'id_left_menu' => array(
        'title' => $this->l('ID'),
        'search' => false,
        'filter_key' => 'a!id_left_menu',
        'width' => 20
      ),
      'title' => array(
        'title' => $this->l('Label'),
        'search' => false,
        'width' =>100,
        'orderby' => true
      ),
    );
  }

  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewleft_menu') === false)
      $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
  }

  public function initProcess(){
    parent::initProcess();
  }

  public function postProcess()
  {
    if( Tools::getValue('deleteImage') ){
      $id_category = Tools::getValue('id_category');
      if (Validate::isLoadedObject($object = $this->loadObject())){
        $this->deleteImage($this->_imgDir, $object->id);
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminLeftMenuItem').'&updateleft_menu&id_category='.$id_category.'&id_left_menu='.$object->id);
      }
    }
    if( Tools::getValue('deleteImageIcon') ){
     $id_category = Tools::getValue('id_category');
      if (Validate::isLoadedObject($object = $this->loadObject())){
        $this->deleteImage($this->_imgDirIcon, $object->id);
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminLeftMenuItem').'&updateleft_menu&id_category='.$id_category.'&id_left_menu='.$object->id);
      }
    }
    return parent::postProcess();
  }

  public function displayAjax()
  {
    $json = array();
    try{
      $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockMenu.tpl', 'leftmenu_');
      $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockMenu.tpl', 'leftmenu_product_');
      $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockCategoriesFooter.tpl', 'leftmenu_footer_');

      if (Tools::getValue('action') == 'removeLink'){
        $id_link = Tools::getValue('id');
        $obj = new leftMenuItem(Tools::getValue('id_left_menu'), Tools::getValue('id_lang'), Tools::getValue('id_shop'));
        $this->removeLink($id_link);
        $json['success'] = Module::getInstanceByName('leftmenu')->l("Successfully saved!") ;
        $json['form'] = $this->getLinksBlock($obj, Tools::getValue('section'), Tools::getValue('id_lang'), Tools::getValue('id_shop'));
      }

      if (Tools::getValue('action') == 'editLink'){
        if(Tools::getValue('id') && Tools::getValue('id') != 'false'){
          $id = Tools::getValue('id');
        }
        else{
          $id = false;
        }
        $obj = new leftMenuItem(Tools::getValue('id_left_menu'), Tools::getValue('id_lang'), Tools::getValue('id_shop'));
        $json['form'] = $this->getLinksBlock($obj, Tools::getValue('section'), Tools::getValue('id_lang'), Tools::getValue('id_shop'), $id);
      }

      if (Tools::getValue('action') == 'saveNewLink'){
        $id_link = Tools::getValue('id');
        $obj = new leftMenuItem(Tools::getValue('id_left_menu'), Tools::getValue('id_lang'), Tools::getValue('id_shop'));
        if(isset($id_link) && $id_link && ($id_link != 'undefined ')){
          $id = $id_link;
          $this->updateLink($id);
        }
        else{
          $id = $this->saveNewLink(Tools::getValue('id_shop'), Tools::getValue('id_lang'), false);
        }

        $json['success'] = Module::getInstanceByName('leftmenu')->l("Successfully saved!") ;
        $json['form'] = $this->getLinksBlock($obj, Tools::getValue('section'), Tools::getValue('id_lang'), Tools::getValue('id_shop'), $id);
      }


      if (Tools::getValue('action') == 'removeProduct') {
        $id_left_menu = Tools::getValue('id_left_menu');
        $section = Tools::getValue('section');
        $id_product = Tools::getValue('id_product');
        $obj = new leftMenuItem($id_left_menu, Tools::getValue('id_lang'), Tools::getValue('id_shop'));
        Db::getInstance()->delete('left_menu_content', 'id_left_menu='.(int)$id_left_menu.' AND position="'.pSQL($section).'" AND type = "products" AND value='.(int)$id_product);

        $list = $this->getProductList( $obj, $section, Tools::getValue('id_lang'), Tools::getValue('id_shop'), true);

        if(!$list){
          $json['list'] = ' ';
        }
        else{
          $json['list'] = $list;
        }
        $json['success'] = Module::getInstanceByName('leftmenu')->l("Product successfully removed!") ;
      }

      if (Tools::getValue('action') == 'addItemMenu') {
        if(Tools::getValue('type') == 'add'){
          $this->addRow(Tools::getValue('id'),Tools::getValue('id_left_menu'), Tools::getValue('section'), Tools::getValue('item'));
        }
        if(Tools::getValue('type') == 'remove'){
          $this->deleteRow(Tools::getValue('id'),Tools::getValue('id_left_menu'), Tools::getValue('section'), Tools::getValue('item'));
        }
        $json['success'] = Module::getInstanceByName('leftmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'saveVideoCode') {
        $this->saveVideoCode(Tools::getValue('code'),Tools::getValue('id_left_menu'), Tools::getValue('section'));
        $json['success'] = Module::getInstanceByName('leftmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'saveDescription') {
        $this->saveDescription();
        $json['success'] = Module::getInstanceByName('leftmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'addProduct') {
        $obj = new leftMenuItem(Tools::getValue('id_left_menu'), Tools::getValue('id_lang'), Tools::getValue('id_shop'));
        $this->addProduct(Tools::getValue('id_product'),Tools::getValue('id_left_menu'), Tools::getValue('section'));
        $json['list'] = $this->getProductList( $obj, Tools::getValue('section'), Tools::getValue('id_lang'), Tools::getValue('id_shop'), true);
        $json['success'] = Module::getInstanceByName('leftmenu')->l("Product successfully added!") ;
      }

      if (Tools::getValue('action') == 'showForm') {
        $id_shop = Tools::getValue('id_shop');
        $id_lang = Tools::getValue('id_lang');
        $type = Tools::getValue('type');
        $section = Tools::getValue('section');

        if (Validate::isLoadedObject($this->loadObject())){
          $obj = $this->loadObject();
        }
        if($section == 'right'){
          $json['form'] = $this->getRightSection($obj, $type, $id_lang, $id_shop);
        }

        if($section == 'bottom'){
          $json['form'] = $this->getBottomSection($obj, $type, $id_lang, $id_shop);
        }
      }

      if (Tools::getValue('action') == 'showSectionContent') {
        $id_shop = Tools::getValue('id_shop');
        $id_lang = Tools::getValue('id_lang');
        $section = Tools::getValue('section');
        if (Validate::isLoadedObject($this->loadObject())){
          $obj = $this->loadObject();
          if($obj->id){
            if($section == 'right'){
              $json['form'] = $this->getRightSection($obj, $obj->right_section_val, $id_lang, $id_shop);
              $json['type'] = $obj->right_section_val;
            }
            if($section == 'bottom'){
              $json['form'] = $this->getBottomSection($obj, $obj->bottom_section_val, $id_lang, $id_shop);
              $json['type'] = $obj->bottom_section_val;
            }
          }
          else{
            $json['form'] = ' ';
          }
        }
        else{
          $json['form'] = $this->alertWarningMessage($this->l('You must save this settings before'));
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
    die(json_encode($json) );
  }

  public function removeLink($id_link){
    Db::getInstance()->delete('left_menu_link', 'id_left_menu_link='.(int)$id_link);
    Db::getInstance()->delete('left_menu_link_lang', 'id_left_menu_link='.(int)$id_link);
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
      'id_left_menu'    => (int)Tools::getValue('id_left_menu'),
      'position'       => pSQL(Tools::getValue('section')),
    );
    Db::getInstance()->insert('left_menu_link', $new_link);
    $id = (int)Db::getInstance()->Insert_ID();

    foreach(Language::getLanguages() as $language){
      $val = trim(Tools::getValue('title_new_link_'.$language['id_lang']));
      if(!$val){
        $val = trim(Tools::getValue('title_new_link_1'));
      }
      $base = array(
        'id_left_menu_link'     => (int)$id,
        'id_shop'              => (int)$id_shop,
        'id_lang'              => (int)$language['id_lang'],
        'title'                =>  pSQL($val),
      );
      Db::getInstance()->insert('left_menu_link_lang', $base);
    }

    return $id;
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
    Db::getInstance()->update('left_menu_link', $new_link, 'id_left_menu_link='.(int)$id);

    foreach(Language::getLanguages() as $language){

      $val = Tools::getValue('title_new_link_'.$language['id_lang']);
      if(!$val){
        $val = Tools::getValue('title_new_link_1');
      }
      $base = array(
        'id_lang'              => (int)$language['id_lang'],
        'title'                =>  pSQL($val),
      );
      Db::getInstance()->update('left_menu_link_lang', $base, 'id_left_menu_link='.(int)$id .' AND id_lang='.$language['id_lang']);
    }
  }

  public function addRow($val, $id_left_menu, $section, $type){
    $base = array(
      'id_left_menu'       => (int)$id_left_menu,
      'position'          =>  pSQL($section),
      'type'              =>  $type,
      'value'             => (int)$val,
    );
    Db::getInstance()->insert('left_menu_content', $base);
  }

  public function deleteRow($val, $id_left_menu, $section, $type){
    Db::getInstance()->delete('left_menu_content', 'id_left_menu='.(int)$id_left_menu.' AND position="'.pSQL($section).'" AND type="'.$type.'" AND value='.$val);
  }

  public function saveVideoCode($code, $id_left_menu, $section)
  {
    if( !$code ){
      throw new Exception ( Module::getInstanceByName('leftmenu')->l("Enter the video code!"));
    }

    $video = $this->issetVideoCode($id_left_menu, $section, 'video');

    if(isset($video[0]['id']) && $video[0]['id']){
      $base = array( 'value' => ($code));
      Db::getInstance()->update('left_menu_content', $base, 'id='.$video[0]['id']);
    }
    else{
      $base = array(
        'id_left_menu'       => (int)$id_left_menu,
        'position'          =>  pSQL($section),
        'type'              => 'video',
        'value'             =>  pSQL($code, true),
      );
      Db::getInstance()->insert('left_menu_content', $base);
    }
  }

  public function saveDescription(){
    $description = Tools::getValue('description');

    if( !trim($description[1]) ){
      throw new Exception( Module::getInstanceByName('leftmenu')->l("Enter description!"));
    }

    foreach($description as $key => $desc){
      $descriptionMenu = $this->getDescription(Tools::getValue('id_left_menu'), Tools::getValue('section'), Tools::getValue('id_shop'), $key);
      if(!$descriptionMenu){
        if(!$desc){
          $desc = $description[1];
        }
        $base = array(
          'id_left_menu'     => (int)Tools::getValue('id_left_menu'),
          'id_shop'         => (int)Tools::getValue('id_shop'),
          'id_lang'         => (int)$key,
          'position'         => pSQL(Tools::getValue('section')),
          'description'     =>  pSQL($desc, true),
        );
        Db::getInstance()->insert('left_menu_description_lang', $base);
      }
      else{
        if(!$desc){
          $desc = $description[1];
        }
        $base = array(
          'description'     =>  pSQL($desc, true),
        );
        Db::getInstance()->update('left_menu_description_lang', $base, 'id_left_menu='.(int)Tools::getValue('id_left_menu').' AND id_shop='.(int)Tools::getValue('id_shop').' AND id_lang='.(int)$key.' AND position="'.pSQL(Tools::getValue('section')).'"');
      }
    }
  }

  public function addProduct($id_product, $id_left_menu, $section)
  {
    if( !$id_product ){
      throw new Exception ( Module::getInstanceByName('leftmenu')->l("Select product!"));
    }
    $isset_product_db = $this->issetProduct($id_product, $id_left_menu, $section);
    if($isset_product_db){
      throw new Exception ( Module::getInstanceByName('leftmenu')->l("Product already added!"));
    }
    $base = array(
      'id_left_menu'       => (int)$id_left_menu,
      'position'          =>  pSQL($section),
      'type'              => 'products',
      'value'             => (int)$id_product,
    );
    Db::getInstance()->insert('left_menu_content', $base);
  }

  public function issetProduct($id_product, $id_left_menu, $section){
    $sql = '
      SELECT  *
        FROM ' . _DB_PREFIX_ . 'left_menu_content p
        WHERE p.id_left_menu = ' . (int)$id_left_menu . '
        AND p.position = "' . pSQL($section) . '"
        AND p.type = "products"
        AND p.value = '.$id_product.'
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function renderForm()
  {
    $id_category = Tools::getValue('id_category');
    $obj = $this->loadObject(true);
    $image_icon = $this->_imgDirIcon.$obj->id.'.png';
    $image_url_icon = ImageManager::thumbnail($image_icon, $this->table.'_'.(int)$obj->id.'.png', 350, 'png', true, true);
    $image_size_icon = file_exists($image_icon) ? filesize($image_icon) / 1000 : false;
    $image = $this->_imgDir.$obj->id.'.png';
    $image_url = ImageManager::thumbnail($image, $this->table.'_icon_'.(int)$obj->id.'.png', 350, 'png', true, true);
    $image_size = file_exists($image) ? filesize($image) / 1000 : false;

    $this->fields_form = array(
      'legend' => array(
        'title' => $this->l('left menu'),
        'icon' => 'icon-plus-sign-alt'
      ),
      'input' => array(
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'html_data_settings',
          'html_content' => $this->l('General settings'),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Width drop-down menu'),
          'name' => 'width',
          'required' => true,
          'form_group_class'=> 'widthNarrowFormL',
          'class' => 'widthNarrowL',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Font size drop-down menu'),
          'name' => 'font_size',
          'form_group_class'=> 'widthNarrowFormL',
          'class' => 'widthNarrowL',
        ),
        array(
          'type'              => 'file',
          'label'             => $this->l('Icon'),
          'form_group_class'  => 'uploadImagesFormGroup',
          'image'             => $image_url_icon ? $image_url_icon : false,
          'name'              => 'image_icon',
          'size'              => $image_size_icon,
          'delete_url'        => self::$currentIndex.'&'.$this->identifier.'='.(int)$obj->id.'&token='.$this->token.'&deleteImageIcon=1',
        ),
        array(
          'type'              => 'file',
          'label'             => $this->l('Background'),
          'form_group_class'  => 'uploadImagesFormGroup',
          'image'             => $image_url ? $image_url : false,
          'name'              => 'image',
          'size'              => $image_size,
          'delete_url'        => self::$currentIndex.'&'.$this->identifier.'='.(int)$obj->id.'&token='.$this->token.'&updateleft_menu&id_category='.$id_category.'&deleteImage=1',
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'html_data_settings',
          'html_content' => $this->l('Right section'),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Active'),
          'name' => 'right_section',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'right_section_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'right_section_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Width section'),
          'name' => 'right_section_width',
          'required' => true,
          'form_group_class'=> 'widthNarrowFormL',
          'class' => 'rightSectionWidth',
        ),

        array(
          'type' => 'text',
          'label' => $this->l('Title'),
          'lang' => true,
          'name' => 'title_right_section',
          'form_group_class'=> 'title_right_section_form',
          'class' => 'title_right_section',
        ),
        array(
          'type' => 'radio',
          'label' => $this->l(''),
          'name' => 'right_section_val',
          'form_group_class'=> 'right_section_val_form',
          'required' => false,
          'class' => 't right_radio',
          'values' => array(
            array(
              'id' => 'links',
              'value' => 'links',
              'label' => $this->l('Links')
            ),
            array(
              'id' => 'description',
              'value' => 'description',
              'label' => $this->l('Description')
            ),
            array(
              'id' => 'products',
              'value' => 'products',
              'label' => $this->l('Products')
            ),
            array(
              'id' => 'cms',
              'value' => 'cms',
              'label' => $this->l('Cms Page')
            ),
            array(
              'id' => 'video',
              'value' => 'video',
              'label' => $this->l('Video')
            ),
            array(
              'id' => 'manufacturers',
              'value' => 'manufacturers',
              'label' => $this->l('Manufacturers')
            ),
            array(
              'id' => 'suppliers',
              'value' => 'suppliers',
              'label' => $this->l('Suppliers')
            ),
          )
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_section_setting right',
          'html_content' => '<div class="right_block_section"></div>',
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'html_data_settings',
          'html_content' => $this->l('Bottom section'),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Active'),
          'name' => 'bottom_section',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'bottom_section_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'bottom_section_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Title'),
          'lang' => true,
          'name' => 'title_bottom_section',
          'form_group_class'=> 'title_bottom_section_form',
          'class' => 'title_bottom_section',
        ),
        array(
          'type' => 'radio',
          'label' => $this->l(''),
          'name' => 'bottom_section_val',
          'form_group_class'=> 'bottom_section_val_form',
          'required' => false,
          'class' => 't bottom_radio',
          'values' => array(
            array(
              'id' => 'links',
              'value' => 'links',
              'label' => $this->l('Links')
            ),
            array(
              'id' => 'description',
              'value' => 'description',
              'label' => $this->l('Description')
            ),

            array(
              'id' => 'products',
              'value' => 'products',
              'label' => $this->l('Products')
            ),
            array(
              'id' => 'cms',
              'value' => 'cms',
              'label' => $this->l('Cms Page')
            ),
            array(
              'id' => 'video',
              'value' => 'video',
              'label' => $this->l('Video')
            ),
            array(
              'id' => 'manufacturers',
              'value' => 'manufacturers',
              'label' => $this->l('Manufacturers')
            ),
            array(
              'id' => 'suppliers',
              'value' => 'suppliers',
              'label' => $this->l('Suppliers')
            ),
          )
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_section_setting bottom',
          'html_content' => '<div class="bottom_block_section">'.$this->getBottomSection($obj, $obj->bottom_section_val, $this->_idLang, $this->_idShop).'</div>',
        ),
        array(
          'type' => 'hidden',
          'name' => 'id_category',
        ),
        array(
          'type' => 'hidden',
          'name' => 'idLang',
        ),
        array(
          'type' => 'hidden',
          'name' => 'token_left_menu',
        ),
        array(
          'type' => 'hidden',
          'name' => 'idShop',
        ),
      ),
      'buttons' => array(
        'save-and-stay' => array(
          'title' => $this->l('Save and stay'),
          'name' => 'submitAdd'.$this->table.'AndStay',
          'type' => 'submit',
          'class' => 'btn btn-default pull-right',
          'icon' => 'process-icon-save'
        ),
        'cancel' => array(
          'title' => $this->l('Categories'),
          'name' => 'back',
          'type' => 'submit',
          'href' => $this->context->link->getAdminLink('AdminLeftMenu'),
          'class' => 'btn btn-default pull-right btn-default-back-categories',
          'icon' => 'process-icon-back'
        ),
      ),
    );

    $this->fields_value['idLang'] = $this->_idLang;
    $this->fields_value['idShop'] = $this->_idShop;
    $this->fields_value['token_left_menu'] = Tools::getAdminTokenLite('AdminLeftMenuItem');
    $this->fields_value['id_category'] = $id_category;
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');

    return parent::renderForm();
  }

  public function getBottomSection( $obj, $type, $id_lang, $id_shop ){
    if(!$obj->id){
      return  $this->alertWarningMessage($this->l('You must save this settings before'));
    }

    if($obj->id && !$type){
      return  $this->alertWarningMessage($this->l('Choose type content'));
    }
    $tpl = '';
    if( $type == 'links'){
      $tpl = $this->getLinksBlock($obj, 'bottom', $id_lang, $id_shop);
    }
    if( $type == 'description'){
      $tpl = $this->renderTextareaBlock($obj->id, 'bottom', $id_lang, $id_shop);
    }
    if( $type == 'products'){
      $tpl = $this->getProductList($obj, 'bottom', $id_lang, $id_shop);
    }
    if( $type == 'cms'){
      $tpl = $this->renderCmsBlock($obj, 'bottom', $id_lang, $id_shop);
    }
    if( $type == 'video'){
      $tpl = $this->getVideoBlock($obj, 'bottom', $id_lang, $id_shop);
    }
    if( $type == 'manufacturers'){
      $tpl = $this->renderManufacturersBlock($obj, 'bottom', $id_lang, $id_shop);
    }
    if( $type == 'suppliers'){
      $tpl = $this->renderSuppliersBlock($obj, 'bottom', $id_lang, $id_shop);
    }
    return $tpl;
  }

  public function getRightSection( $obj, $type, $id_lang, $id_shop ){

    if(!$obj->id){
      return  $this->alertWarningMessage($this->l('You must save this settings before'));
    }
    if($obj->id && !$type){
      return  $this->alertWarningMessage($this->l('Choose type content'));
    }
    $tpl = '';
    if( $type == 'links'){
      $tpl = $this->getLinksBlock($obj, 'right', $id_lang, $id_shop);
    }
    if( $type == 'description'){
      $tpl = $this->renderTextareaBlock($obj->id, 'right', $id_lang, $id_shop);
    }
    if( $type == 'products'){
      $tpl = $this->getProductList($obj, 'right', $id_lang, $id_shop);
    }
    if( $type == 'cms'){
      $tpl = $this->renderCmsBlock($obj, 'right', $id_lang, $id_shop);
    }
    if( $type == 'video'){
      $tpl = $this->getVideoBlock($obj, 'right', $id_lang, $id_shop);
    }
    if( $type == 'manufacturers'){
      $tpl = $this->renderManufacturersBlock($obj, 'right', $id_lang, $id_shop);
    }
    if( $type == 'suppliers'){
      $tpl = $this->renderSuppliersBlock($obj, 'right', $id_lang, $id_shop);
    }
    return $tpl;
  }


  public function getVideoBlock($obj, $section, $id_lang, $id_shop){

    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'leftmenu/views/templates/hook/videoBlock.tpl');
    $video = $this->getSelectedId($obj->id, $section, 'video');
    if(isset($video[0]['id']) && $video[0]['id']){
      $video_code = $video[0]['id'];
    }
    else{
      $video_code = false;
    }
    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'video_code'   => $video_code
      )
    );
    return $data->fetch();
  }

  public function renderSuppliersBlock($obj, $section, $id_lang, $id_shop)
  {
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'leftmenu/views/templates/hook/tableLinksBlock.tpl');
    $selected_s = array();
    $supplier_selected = $this->getSelectedId($obj->id, $section, 'suppliers');
    $supplier_all =  Supplier::getSuppliers(false, $id_lang);

    foreach($supplier_selected as $v){
      if($v['id']){
        $selected_s[] = $v['id'];
      }
    }

    foreach($supplier_all as $key => $value){
      $supplier_all[$key]['id'] = $value['id_supplier'];
      if(in_array($value['id_supplier'], $selected_s)){
        $supplier_all[$key]['is_selected'] = true;
      }
      else{
        $supplier_all[$key]['is_selected'] = false;
      }
    }

    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'value'   => $supplier_all,
        'title'   => $this->l('ADD SUPPLIER'),
        'type'   => 'suppliers_block'
      )
    );
    return $data->fetch();
  }

  public function renderManufacturersBlock($obj, $section, $id_lang, $id_shop)
  {
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'leftmenu/views/templates/hook/tableLinksBlock.tpl');
    $selected_m = array();
    $manufacturer_selected = $this->getSelectedId($obj->id, $section, 'manufacturers');
    $manufacturer_all =  ManufacturerCore::getManufacturers(false, $id_lang, true, false, false, false, true );

    foreach($manufacturer_selected as $v){
      if($v['id']){
        $selected_m[] = $v['id'];
      }
    }

    foreach($manufacturer_all as $key => $value){
      $manufacturer_all[$key]['id'] = $value['id_manufacturer'];
      if(in_array($value['id_manufacturer'], $selected_m)){
        $manufacturer_all[$key]['is_selected'] = true;
      }
      else{
        $manufacturer_all[$key]['is_selected'] = false;
      }
    }

    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'value'   => $manufacturer_all,
        'title'   => $this->l('ADD MANUFACTURERS'),
        'type'   => 'manufacturers_block'
      )
    );
    return $data->fetch();
  }

  public function renderCmsBlock($obj, $section, $id_lang, $id_shop)
  {
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'leftmenu/views/templates/hook/tableLinksBlock.tpl');
    $selected_c = array();
    $cms_selected = $this->getSelectedId($obj->id, $section, 'cms');
    $cms_all =  CMSCore::getCMSPages($id_lang, null, true, $id_shop);

    foreach($cms_selected as $v){
      if($v['id']){
        $selected_c[] = $v['id'];
      }
    }

    foreach($cms_all as $key => $value){
      $cms_all[$key]['id'] = $value['id_cms'];
      $cms_all[$key]['name'] = $value['meta_title'];
      if(in_array($value['id_cms'], $selected_c)){
        $cms_all[$key]['is_selected'] = true;
      }
      else{
        $cms_all[$key]['is_selected'] = false;
      }
    }
    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'value'   => $cms_all,
        'title'   => $this->l('ADD CMS'),
        'type'   => 'cms_block'
      )
    );
    return $data->fetch();
  }

  public function getProductList($obj, $section, $id_lang, $id_shop, $input= false )
  {
    $form = ' ';
    $products = $this->getSelectedIdProduct($obj->id, $section);
    if(isset($products[0]['id']) && $products[0]['id']){
      $ids = $products[0]['id'];
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
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'leftmenu/views/templates/hook/productList.tpl');
    if(!$input){
      $form = $this->renderProductsBlock($obj, $section, $id_lang, $id_shop);
    }

    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'items'   => $items,
      )
    );
    return $form.$data->fetch();
  }

  public function renderProductsBlock($obj, $section, $id_lang, $id_shop)
  {
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'leftmenu/views/templates/hook/productsBlock.tpl');
    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
      )
    );
    return $data->fetch();
  }

  public function renderTextareaBlock($id, $section, $id_lang, $id_shop)
  {
    $val = array();
    $saved = $this->getDescription($id, $section, $id_shop);

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

    $this->fields_form = array(
      'tinymce' => true,
      'legend' => array(
        'title' => $this->l('ADD DESCRIPTION'),
        'icon' => 'icon-plus-sign-alt'
      ),
      'input' => array(
        array(
          'type' => 'textarea',

          'name' => 'description',
          'lang' => true,
          'class' => 'text_cont',
          'form_group_class'=> 'block_description_l',
          'autoload_rte' => true,
          'rows' => 5,
          'cols' => 40,
          'hint' => $this->l('Invalid characters:').' <>;=#{}'
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_button_description',
          'html_content' => '<button type="button" id="save_description_left" class="btn btn-default">'.$this->l('Save').'</button>',
        ),
      ),
    );
    $this->fields_value['description'] = $val;
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
    return parent::renderForm();
  }

  public function getLinksBlock($obj, $section, $id_lang, $id_shop, $id = false){
    $form = '';
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'leftmenu/views/templates/hook/linksBlockLeftMenu.tpl');
    $links = $this->getLinksMenu($obj->id, $section, $id_lang, $id_shop);
    $form = $this->renderLinksBlock($id_shop, $id_lang, $obj->id, $section, $id);

    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'allLinks'   => $links,
      ));
    return $form.$data->fetch();
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

  public function renderLinksBlock($id_shop, $id_lang, $id_left_menu, $section, $id = false)
  {
    $ids = array();
    if($id){
      $links = $this->getLink($id_shop, $id_lang, $id_left_menu, $section, $id);
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
      'id_form' => 'formLinksBlockLeftMenu',
      'input' => array(
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'button_add_new_link',
          'html_content' => '<a><i class="process-icon-new"></i>'.$this->l('add new link').'</a>',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Title link'),
          'form_group_class'=> 'link_block_form',
          'name' => 'title_new_link',
          'lang' => true,
          'class' => 'title_new_link',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Link'),
          'name' => 'new_link',
          'form_group_class'=> 'link_block_form',
          'class' => 'new_link',
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_button',
          'html_content' => '<button type="button" data-id="'.$id_left_menu.'" data-id-link="'.$id.'" id="add_links_menu" class="btn btn-default">'.$this->l('Save').'</button>',
        ),
      ),

    );
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
    return parent::renderForm();
  }

  public function alertWarningMessage($message){
    return '<div class="alert alert-warning">'.$message.'</div>';
  }

  public function renderList()
  {
    $this->addRowAction('edit');
    $this->addRowAction('delete');
    return parent::renderList();
  }

  protected function postImage($id)
  {
    $ret = $this->uploadImage($id, 'image', $this->_imgDir, 'png');
    $ret = $this->uploadImage($id, 'image_icon', $this->_imgDirIcon, 'png');
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockMenu.tpl', 'leftmenu_');
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockMenu.tpl', 'leftmenu_product_');
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockCategoriesFooter.tpl', 'leftmenu_footer_');

    return $ret;
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
        if (!ImageManagerCore::resize($tmpName, $dir.$id.'.'.$ext, (int)$width, (int)$height, ($ext ? $ext : $this->imageType)))
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

  protected function deleteImage($image, $id)
  {
    $file_name = $image.$id.'.png';
    if (realpath(dirname($file_name)) != realpath($image))
      Tools::dieOrLog(sprintf('Could not find upload directory'));

    if ($image != '' && is_file($file_name)){
     unlink($file_name);
    }
  }

  public function getLinksMenu($id_left_menu, $position, $id_lang, $id_shop){
    $sql = '
			SELECT tm.link, tml.title, tm.id_left_menu_link
      FROM ' . _DB_PREFIX_ . 'left_menu_link as tm
      LEFT JOIN ' . _DB_PREFIX_ . 'left_menu_link_lang as tml
      ON tm.id_left_menu_link = tml.id_left_menu_link
      WHERE tml.id_lang = ' . (int)$id_lang . '
      AND tml.id_shop = ' . (int)$id_shop . '
      AND tm.id_left_menu = '. (int)$id_left_menu .'
      AND tm.position = "'. pSQL($position) .'"

			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getLink($id_shop, $id_lang, $id_left_menu, $position, $id){

    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'left_menu_link as tm
      LEFT JOIN ' . _DB_PREFIX_ . 'left_menu_link_lang as tml
      ON tm.id_left_menu_link = tml.id_left_menu_link
      WHERE tml.id_shop = ' . (int)$id_shop . '
      AND tm.id_left_menu = '. (int)$id_left_menu .'
      AND tm.position = "'. pSQL($position) .'"
      AND tm.id_left_menu_link = '. (int)$id .'

			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getDescription($id_left_menu, $position, $id_shop, $id_lang = false){

    $where = ' ';

    if($id_lang){
      $where = ' AND p.id_lang='.(int)$id_lang;
    }

    $sql = '
      SELECT  *
        FROM ' . _DB_PREFIX_ . 'left_menu_description_lang p
        WHERE p.id_left_menu = ' . (int)$id_left_menu . '
        AND p.position = "' . pSQL($position) . '"
        AND p.id_shop = '.(int)$id_shop.'
        '.$where.'
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getIds($id_left_menu, $section, $type){
    $sql = '
			SELECT  GROUP_CONCAT( DISTINCT(p.value) )  as id
        FROM ' . _DB_PREFIX_ . 'left_menu_content p
        WHERE p.id_left_menu = ' . (int)$id_left_menu . '
        AND p.position = "' . pSQL($section) . '"
        AND p.type = "'.pSQL($type).'"
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
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

  public function getSelectedIdProduct($id_left_menu, $section){
    $sql = '
			SELECT  GROUP_CONCAT(DISTINCT(p.value) )  as id
        FROM ' . _DB_PREFIX_ . 'left_menu_content p
        WHERE p.id_left_menu = ' . (int)$id_left_menu . '
        AND p.position = "' . pSQL($section) . '"
        AND p.type = "products"
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function getSelectedId($id_left_menu, $section, $type){
    $sql = '
			SELECT  p.value as id
        FROM ' . _DB_PREFIX_ . 'left_menu_content p
        WHERE p.id_left_menu = ' . (int)$id_left_menu . '
        AND p.position = "' . pSQL($section) . '"
        AND p.type = "'.pSQL($type).'"
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function issetVideoCode($id_left_menu, $section, $type){
    $sql = '
			SELECT  p.id
        FROM ' . _DB_PREFIX_ . 'left_menu_content p
        WHERE p.id_left_menu = ' . (int)$id_left_menu . '
        AND p.position = "' . pSQL($section) . '"
        AND p.type = "'.pSQL($type).'"
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }
  public function clearSmartyCache($tpl, $id)
  {
    $languages = Language::getLanguages(false);
    foreach ($languages as $language)
    {
      Tools::enableCache();
      Tools::clearCache(null, $tpl, $id.$language['id_lang']);
      Tools::restoreCacheSettings();
    }
  }
}