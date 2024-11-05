<?php
if (!defined('_PS_VERSION_'))
  exit;

require_once(dirname(__FILE__) . '/classes/blockFeatured.php');
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;


class blockhomefeatured extends Module implements WidgetInterface
{
  private $_homeFeatured;
  private $_idShop;
  private $_idLang;

  public function __construct()
  {
    $this->name = 'blockhomefeatured';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('Home featured');
    $this->description = $this->l('Home featured');
    $this->_homeFeatured = new blockFeatured();
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
  }

  public function install()
  {
    if (!parent::install()
      || !$this->registerHook('actionAdminControllerSetMedia')
      || !$this->registerHook('header')

    )
      return false;

    $this->_createTab('AdminHomeFeatured', 'Home Featured');
    $this->_installDb();
    $this->_setDataDb();

    return true;
  }
  public function uninstall()
  {
    if (  !parent::uninstall()  )
      return false;

    $this->_removeTab('AdminHomeFeatured');
    $this->_uninstallDb();

    return true;
  }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') != 'AdminHomeFeatured') {
            return false;
        }

        $this->context->controller->addCSS([
            _PS_MODULE_DIR_.'blockhomefeatured/views/css/style.css'
        ]);

        $this->context->controller->addJS([
            _PS_MODULE_DIR_.'blockhomefeatured/views/js/main.js',
            __PS_BASE_URI__.'js/jquery/plugins/select2/jquery.select2.js'
        ]);
    }

  private function _createTab($class_name, $name)
  {
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = $class_name;
    $tab->name = array();
    foreach (Language::getLanguages(true) as $lang)
      $tab->name[$lang['id_lang']] = $name;
    $tab->id_parent = -1;
    $tab->module = $this->name;
    $tab->add();
  }

  private function _removeTab($class_name)
  {
    $id_tab = (int)Tab::getIdFromClassName($class_name);
    if ($id_tab)
    {
      $tab = new Tab($id_tab);
      $tab->delete();
    }
  }

  private function _installDb()
  {
    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'homefeatured';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'homefeatured(
				id_homefeatured int(11) unsigned NOT NULL AUTO_INCREMENT,
				active boolean NOT NULL,
				position int(11) unsigned NOT NULL,
				type varchar(255) NULL,
				ids_categories varchar(255) NULL,
				ids_products varchar(255) NULL,
				date_add datetime NULL,
				PRIMARY KEY (`id_homefeatured`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'homefeatured_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'homefeatured_lang(
				id_homefeatured int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title varchar(255) NULL,
				PRIMARY KEY(id_homefeatured, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);
  }

  private function _setDataDb(){

    $data = array(
      array('type' => 'all', 'title' => 'All'),
      array('type' => 'new', 'title' => 'New products'),
      array('type' => 'last_visited', 'title' => 'Last visited'),
      array('type' => 'selling', 'title' => 'Best selling'),
      array('type' => 'discount', 'title' => 'Products with discount'),
    );

    foreach($data as $value){
      $this->_setItem($value['type'], $value['title']);
    }
  }


  private function _setItem($type, $title){

    $languages = Language::getLanguages(false);
    $obj = new blockFeatured();
    foreach ($languages as $lang){
      $obj->title[$lang['id_lang']] = $title;
    }
    $obj->type = $type;
    $obj->active = 1;
    $obj->save();

  }



  private function _uninstallDb()
  {
    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'homefeatured';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'homefeatured_lang';
    Db::getInstance()->execute($sql);
  }

  public function getContent()
  {
    Tools::redirectAdmin($this->context->link->getAdminLink('AdminHomeFeatured'));
  }

  public function hookDisplayHeader($params)
  {
 
    $this->context->controller->registerJavascript('blockhomefeatured', 'modules/'.$this->name.'/views/js/blockhomefeatured.js',  array('position' => 'bottom', 'priority' => 150));
    $this->context->controller->registerStylesheet('bxslider_home', 'js/jquery/plugins/bxslider/jquery.bxslider.css',  array('media' => 'all', 'priority' => 900));
    $this->context->controller->registerJavascript( 'bxslider_home',  'js/jquery/plugins/bxslider/jquery.bxslider.js', array('position' => 'bottom', 'priority' => 100) );

    if( Context::getContext()->controller->php_self == 'product'){

      $id_product = (int)Tools::getValue('id_product');
      $productsViewed = (isset($params['cookie']->viewed) && !empty($params['cookie']->viewed)) ? array_slice(array_reverse(explode(',', $params['cookie']->viewed)), 0, 20) : array();

      if ($id_product && !in_array($id_product, $productsViewed))
      {
        $product = new Product((int)$id_product);
        if ($product->checkAccess((int)$this->context->customer->id))
        {
          if (isset($params['cookie']->viewed) && !empty($params['cookie']->viewed))
            $params['cookie']->viewed .= ','.(int)$id_product;
          else
            $params['cookie']->viewed = (int)$id_product;
        }
      }
    }


  }



  public function renderWidget($hookName = null, array $configuration = array())
  {

    if(!$this->active){
      return false;
    }

    if( Context::getContext()->controller->php_self !== 'index'){
      return false;
    }

    $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

    return $this->display(__FILE__, 'views/templates/hook/blockhomefeatured.tpl');
  }

  public function getWidgetVariables($hookName = null, array $configuration = array())
  {
    $tabs = $this->_homeFeatured->getHomeFeatured($this->_idLang, $this->_idShop, false, true);

    return array(
      'id_shop'       => $this->_idShop,
      'id_lang'       => $this->_idLang,
      'tabs'          => $tabs,
      'base_url'      => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
    );
  }


}