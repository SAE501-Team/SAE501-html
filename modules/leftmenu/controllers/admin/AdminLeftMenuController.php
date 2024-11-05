<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */

class AdminLeftMenuController extends ModuleAdminController
{
  private $_imgDir;

  public function __construct()
  {
    $this->className = 'Category';
    $this->table = 'category';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_category';
    $this->_imgDir = _PS_MODULE_DIR_ . 'leftmenu/views/img/';
    $this->addRowAction('view');

    $this->fields_list = array(
      'id_category' => array(
        'title' => $this->l('ID'),
        'search' => false,
        'onclick' => false,
        'filter_key' => 'a!id_category',
        'width' => 20
      ),
      'name' => array(
        'title' => $this->l('Name'),
        'search' => false,
        'onclick' => false,
        'filter_key' => 'b!name',
        'width' => 20
      ),
    );
  }

  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewleft_menu') === false)
      $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
    $this->_where = ' AND a.`id_parent` = '.Configuration::get('PS_HOME_CATEGORY');
  }

  public function initProcess(){
    parent::initProcess();
  }

  public function initContent()
  {
    $maxdepth = Configuration::get('LEFT_MENU_MAX_DEPTH');
    $this->tpl_list_vars['max_depth'] = 'max_depth';
    $this->tpl_list_vars['max_depth_val'] = $maxdepth;
    $this->tpl_list_vars['token_cont'] = Tools::getAdminTokenLite('AdminLeftMenu');;
    parent::initContent();
  }

  public function renderView()
  {
    if(Tools::getValue('viewcategory') !== false){

      $id_category = Tools::getValue('id_category');
      $issetSetting = $this->issetSetting($id_category);

      if(isset($issetSetting[0]['id_left_menu']) && $issetSetting[0]['id_left_menu']){
        $id = $issetSetting[0]['id_left_menu'];
        $where = '&updateleft_menu&id_left_menu='.$id;
      }
      else{
        $where = '&addleft_menu';
      }
      Tools::redirectAdmin($this->context->link->getAdminLink('AdminLeftMenuItem').'&id_category='.$id_category.$where);
    }
    else{
      Tools::displayError('You do not have permission to view this.');
    }
  }

  public function issetSetting($id_category){
    $sql = '
			SELECT  p.id_left_menu
        FROM ' . _DB_PREFIX_ . 'left_menu as p
        WHERE p.id_category = ' . (int)$id_category ;

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function displayAjax()
  {
    $json = array();
    try{
      if (Tools::getValue('action') == 'saveMaxDepth'){
        $maxDepth = Tools::getValue('max_depth');
        Configuration::updateValue('LEFT_MENU_MAX_DEPTH', (int)$maxDepth);
        $json['success'] = Module::getInstanceByName('leftmenu')->l("Successfully saved!") ;
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

}