<?php
if (!defined('_PS_VERSION_'))
  exit;

require_once(dirname(__FILE__) . '/classes/bannerBlock.php');
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class bannerhomepage extends Module implements WidgetInterface
{

  private $_bannerBlock;

  public function __construct()
  {
    $this->name = 'bannerhomepage';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->bootstrap = true;
    $this->module_key = "34f24ecd51a391dc9bcfce884c0fa21b";

    parent::__construct();

    $this->displayName = $this->l('Block banner');
    $this->description = $this->l('Block banner.');
    $this->_bannerBlock = new bannerBlock();
  }

  public function install()
  {
    if (!parent::install()
        || !$this->registerHook('actionAdminControllerSetMedia')
      || !$this->registerHook('header')

      || !Configuration::updateValue('GOMAKOIL_BLOCK_BANNER', '')
    )
      return false;

    $this->_createTab('AdminBlockBanner', 'Banner');
    $this->_installDb();
    $this->_installConfiguration();
    $this->_setDataDb();

    return true;
  }
  public function uninstall()
  {
    if (!parent::uninstall()
      || !Configuration::deleteByName('GOMAKOIL_BLOCK_BANNER'))
      return false;

    $this->_removeTab('AdminBlockBanner');
    $this->_uninstallDb();

    return true;
  }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') != 'AdminBlockBanner') {
            return false;
        }

        $this->context->controller->addCSS([
            _PS_MODULE_DIR_.'bannerhomepage/views/css/style.css'
        ]);

        $this->context->controller->addJS([
            _PS_JS_DIR_.'tiny_mce/tiny_mce.js',
            _PS_JS_DIR_.'admin/tinymce.inc.js',
            _PS_MODULE_DIR_.'bannerhomepage/views/js/main.js'
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
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'banner';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'banner(
				id_banner int(11) unsigned NOT NULL AUTO_INCREMENT,
				active boolean NOT NULL,
				position int(11) unsigned NOT NULL,
				date_add datetime NULL,
				PRIMARY KEY (`id_banner`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'banner_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'banner_lang(
				id_banner int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				image varchar(255) NULL,
				title varchar(255) NULL,
				url varchar(255) NULL,
        description TEXT  NOT NULL,
				PRIMARY KEY(id_banner, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);
  }

  private function _uninstallDb()
  {
    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'banner';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'banner_lang';
    Db::getInstance()->execute($sql);
  }

  private function _installConfiguration(){
    $config = array(
      'show_description'    => 1,
      'show_on_all_pages'   => 0,

    );
    $config = serialize($config);
    Configuration::updateValue('GOMAKOIL_BLOCK_BANNER', $config);
  }


  private function _setDataDb(){

    $data = array(
      array('key' => '1', 'title' => 'Party Dress Up to 60% Off', 'description' => 'Experience the excitement of shopping! With a dazzling selectio of womens apparel'),
      array('key' => '2', 'title' => 'New Clothing', 'description' => 'Experience the excitement of shopping! With a dazzling selectio of womens apparel'),
      array('key' => '3', 'title' => 'Womens Clothing', 'description' => 'Experience the excitement of shopping! With a dazzling selectio of womens apparel'),
      array('key' => '4', 'title' => 'Get exotic look with westrn wear', 'description' => 'Experience the excitement of shopping! With a dazzling selectio of womens apparel'),
      array('key' => '5', 'title' => 'Mini dress', 'description' => 'Experience the excitement of shopping! With a dazzling selectio of womens apparel'),
      array('key' => '6', 'title' => 'Work it out', 'description' => 'Experience the excitement of shopping! With a dazzling selectio of womens apparel'),
    );

    foreach($data as $value){
      $this->_setItem($value);
    }


  }

  private function _setItem($value){

    $lang_def = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    $languages = Language::getLanguages(false);
    $obj = new bannerBlock();

    foreach ($languages as $lang){
      $obj->title[$lang['id_lang']] = $value['title'];
      $obj->image[$lang['id_lang']] =  $value['key'].'_'.$lang_def->id.'.jpg';
      $obj->description[$lang['id_lang']] =  $value['description'];
      $obj->url[$lang['id_lang']] =  '#';
    }

    $obj->active = 1;
    $obj->save();

  }



  public function getContent()
  {
    Tools::redirectAdmin($this->context->link->getAdminLink('AdminBlockBanner'));
  }

  public function hookHeader() {

    $this->context->controller->registerStylesheet('bxslider_ban', 'js/jquery/plugins/bxslider/jquery.bxslider.css', array('media' => 'all', 'priority' => 900));
    $this->context->controller->registerJavascript('bannerhomepage', 'modules/'.$this->name.'/views/js/bannerhomepage.js', array('position' => 'bottom', 'priority' => 150));
    $this->context->controller->registerJavascript( 'bxslider_ban',  'js/jquery/plugins/bxslider/jquery.bxslider.js', array('position' => 'bottom', 'priority' => 100) );

  }

  public function renderWidget($hookName = null,  array $configuration = array())
  {

    if(!$this->active){
      return false;
    }

    $settings = Tools::unserialize(Configuration::get('GOMAKOIL_BLOCK_BANNER'));
    if (!$settings['show_on_all_pages'] && Context::getContext()->controller->php_self !== 'index') {
      return false;
    }

    if (Context::getContext()->controller->php_self !== 'index' && Context::getContext()->controller->php_self !== 'product' && Context::getContext()->controller->php_self !== 'category') {
      return false;
    }

    $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

    return $this->display(__FILE__, 'views/templates/hook/banner-list.tpl');
  }

  public function getWidgetVariables($hookName = null, array $configuration = array())
  {
    $settings = Tools::unserialize(Configuration::get('GOMAKOIL_BLOCK_BANNER'));
    $img_dir = _MODULE_DIR_ . 'bannerhomepage/views/img/banner/';
    $dir = _PS_MODULE_DIR_ . 'bannerhomepage/views/img/banner/';
    $banners = $this->_bannerBlock->getAllBanners(Context::getContext()->language->id, Context::getContext()->shop->id);

    foreach($banners as $key => $item){
      $banners[$key]['img'] = ($item['image'] && file_exists($dir.$item['image'])) ? $img_dir.$item['image'] : false;
    }

    return array(
      'id_shop'         => Context::getContext()->shop->id,
      'id_lang'         => Context::getContext()->language->id,
      'banners'         => $banners,
      'description'     => $settings['show_description'],

    );
  }


}
