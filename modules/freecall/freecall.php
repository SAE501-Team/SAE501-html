<?php

if (!defined('_PS_VERSION_')){
  exit;
}

require_once(dirname(__FILE__) . '/classes/FreeCallClass.php');
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class FreeCall extends Module implements WidgetInterface {

  private $_freeCallClass;
  private $_shopId;
  private $_langId;

  public function __construct()
  {
    $this->_shopId = Context::getContext()->shop->id;
    $this->_langId = Context::getContext()->language->id;
    $this->name = 'freecall';
    $this->tab = 'front_office_features';
    $this->version = '3.3.1';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->bootstrap = true;
    $this->module_key = '0920411ab0a254a68630f7a0559d3a82';

    parent::__construct();

    $this->displayName = $this->l('Request a CallBack');
    $this->description = $this->l('Be most accessible to people. Let your customers order a free call back about the products they are interested in.');
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    $this->_freeCallClass = new FreeCallClass();
  }

  public function install()
  {
    if (!parent::install()
        || !$this->registerHook('actionAdminControllerSetMedia')
      || !$this->registerHook('header')
    )
      return false;

    $this->_createTab('AdminFreeCall', 'CallBack');
    $this->installDb();
    $this->setInDb();
    return true;
  }

  public function uninstall(){

    if ( !parent::uninstall() )
      return false;

    $this->_removeTab('AdminFreeCall');
    $this->uninstallDb();

    return true;
  }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') != 'AdminFreeCall') {
            return false;
        }

        $this->context->controller->addCSS([
            _PS_MODULE_DIR_.'freecall/views/css/freecall_admin.css',
        ]);

        $this->context->controller->addjQueryPlugin([
            'select2'
        ]);

        $this->context->controller->addJS([
            _PS_MODULE_DIR_.'freecall/views/js/freecall_admin.js',
        ]);
    }

  public function installDb()
  {
    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'freecall';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'freecall(
				id_freecall int(11) unsigned NOT NULL AUTO_INCREMENT,
				email varchar(255) NULL,
				code_phone varchar(512) NULL,
				position varchar(255) NULL,
				radius int(11) NULL,
				size_icon int(11) NULL,
				size_title int(11) NULL,
	            background_icon varchar(255) NULL,
                background_icon_hover varchar(255) NULL,
                color_icon varchar(255) NULL,
				phone varchar(255) NULL,
				phone2 varchar(255) NULL,
				border_radius int(11) NULL,
				category_page int(11) NULL,
				product_page int(11) NULL,

				color_form varchar(255) NULL,
				background_form varchar(255) NULL,
                opacity_form float NULL,
                background_overlay varchar(255) NULL,
                opacity_overlay float NULL,
                background_button varchar(255) NULL,
                background_button_hover varchar(255) NULL,
                color_button varchar(255) NULL,
                color_button_hover varchar(255) NULL,
                show_email int(11) NULL,
                show_comment int(11) NULL,
                show_captcha int(11) NULL,
                time_show_question int(11) NULL,
                show_question int(11) NULL,
                date_add datetime NULL,

				PRIMARY KEY (`id_freecall`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'freecall_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'freecall_lang(
				id_freecall int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title_icon varchar(512) NULL,
				title_form varchar(512) NULL,
	            description varchar(2000) NULL,
                title_success varchar(512) NULL,
	            description_success varchar(2000) NULL,
	            title_question varchar(512) NULL,
	            description_question varchar(2000) NULL,

				PRIMARY KEY(id_freecall, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);
  }

  public function setInDb(){

    $languages = Language::getLanguages(false);
    $obj = new FreeCallClass();

    foreach ($languages as $lang){
      $obj->title_icon[$lang['id_lang']] = 'Request a CallBack';
      $obj->title_form[$lang['id_lang']] = 'Did not you find what you were looking for?';
      $obj->description[$lang['id_lang']] = 'We will call you back and answer all your questions.';
      $obj->title_success[$lang['id_lang']] = 'Thank for your appeal';
      $obj->description_success[$lang['id_lang']] = 'Our manager will contact you';
      $obj->title_question[$lang['id_lang']] = 'Did not you find what you were looking for?';
      $obj->description_question[$lang['id_lang']] = 'We will call you back and answer all your questions. Do you agree?';
    }

    $obj->email = 'demo@demo.com';
    $obj->code_phone = '+33';
    $obj->position = 'bottom_right';
    $obj->radius = '35';
    $obj->size_icon = '35';
    $obj->size_title = '17';
    $obj->background_icon = '#68cafa';
    $obj->background_icon_hover = '#b7de69';
    $obj->color_icon = '#ffffff';
    $obj->border_radius = '10';
    $obj->color_form = '#ffffff';
    $obj->background_form = '#000000';
    $obj->opacity_form = '0.8';
    $obj->background_overlay = '#000000';
    $obj->opacity_overlay = '0.3';
    $obj->background_button = '#199c0c';
    $obj->background_button_hover = '#17800D';
    $obj->color_button = '#ffffff';
    $obj->color_button_hover = '#ffffff';
    $obj->show_email = 0;
    $obj->time_show_question = 6000;
    $obj->show_question = 1;
    $obj->phone = '+33 1 23 45 67 89';
    $obj->phone2 = '+33 1 23 45 67 89';
    $obj->category_page = 0;
    $obj->product_page = 0;

    $obj->save();
  }

  public function uninstallDb()
  {
    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'freecall';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'freecall_lang';
    Db::getInstance()->execute($sql);
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

  public function upgradeFaqs_3_3_0(){
      $sql = "
   SELECT NULL
            FROM INFORMATION_SCHEMA.COLUMNS
           WHERE table_name = '"._DB_PREFIX_."freecall'
             AND table_schema = '"._DB_NAME_."'
             AND column_name = 'show_captcha'
    ";

      $check = Db::getInstance()->executeS($sql);
      if( !$check ){
          $sql = '
      ALTER TABLE '._DB_PREFIX_.'freecall
      ADD COLUMN `show_captcha` INT(1) NOT NULL DEFAULT "1" AFTER `date_add`;

    ';
          Db::getInstance()->execute($sql);
      }


      return true;
  }










  public function getContent()
  {

	  
	  
    $settings = $this->_freeCallClass->getFreeCall(Context::getContext()->language->id, Context::getContext()->shop->id);
    if(isset($settings[0]) && $settings[0]){
      $settings = $settings[0];
    }

    if(!$settings){
      Tools::redirectAdmin($this->context->link->getAdminLink('AdminFreeCall').'&addfreecall');
    }
    else{
      Tools::redirectAdmin($this->context->link->getAdminLink('AdminFreeCall').'&updatefreecall&id_freecall='.$settings['id_freecall']);
    }
  }



  public function hookHeader() {
      $this->context->controller->registerJavascript('freecall', 'modules/freecall/views/js/freecall.js', array('media' => 'all', 'position' => 'bottom', 'priority' => 150));
  }



  public function renderWidget($hookName = null, array $configuration = array())
  {
    if(!$this->active){
      return false;
    }
    $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

    return $this->display(__FILE__, 'views/templates/hook/buttonFreeCall.tpl');
  }

  public function getWidgetVariables($hookName = null, array $configuration = array())
  {
    $show_question = false;
    $time_show_question = false;
    $settings = $this->_freeCallClass->getFreeCall(Context::getContext()->language->id, Context::getContext()->shop->id);


    if(isset($settings[0]) && $settings[0]){
      $settings = $settings[0];
    }
    else{
      return false;
    }

    if($settings['show_question'] && $settings['time_show_question'] && !Context::getContext()->cookie->show_question ){
      $show_question = true;
      $time_show_question = $settings['time_show_question'];
      Context::getContext()->cookie->show_question = true;
    }

    return array(
      'id_shop'            => Context::getContext()->shop->id,
      'id_lang'            => Context::getContext()->language->id,
      'settings'           => $settings,
      'show_question'      => $show_question,
      'base_url'           => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
      'time_show_question' => $time_show_question
    );
  }




}
