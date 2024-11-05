<?php

if (!defined('_PS_VERSION_')){
  exit;
}
require_once(dirname(__FILE__) . '/classes/topMenuClass.php');

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

class Topmenu extends Module implements WidgetInterface {
  private $_menu;
  private $_langId;
  private $_shopId;
  private $templateFile;
  public function __construct()
  {
    $this->_shopId = Context::getContext()->shop->id;
    $this->_langId = Context::getContext()->language->id;
    $this->_menu = new topMenuClass();
    $this->name = 'topmenu';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('Top Menu');
    $this->description = $this->l('Adds a new horizontal menu to the top of your e-commerce website.');
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

    $this->templateFile = 'module:topmenu/views/templates/hook/menuLine.tpl';

  }

  public function install()
  {

    if (!parent::install()
      || !$this->registerHook('header')
      || !$this->registerHook('ActionAdminControllerSetMedia')
    )
      return false;

    $this->_createTab();
    $this->installDb();
    $this->installConfiguration();
    $this->_setDataDb();

    return true;
  }

  public function uninstall(){

    if ( !parent::uninstall() )
      return false;

    $this->_removeTab();
    $this->uninstallDb();
    $this->uninstallConfiguration();

    return true;
  }

  public function installConfiguration()
  {

//    Configuration::updateValue('GOMAKOIL_TOP_MENU_WIDTH', 625);
  }

  public function installDb()
  {
    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'top_menu';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'top_menu(
				id_top_menu int(11) unsigned NOT NULL AUTO_INCREMENT,
				active boolean NOT NULL,
				narrow boolean NOT NULL,
				width  int(11) unsigned NOT NULL,
				height  int(11) unsigned NOT NULL,
				position int(11) unsigned NOT NULL,
				custom boolean NOT NULL,
				link varchar(255) NULL,
				content boolean NOT NULL,
				background varchar(255) NULL,

	      left_width  int(11) unsigned NOT NULL,
				main_width  int(11) unsigned NOT NULL,
				right_width  int(11) unsigned NOT NULL,
				botton_width  int(11) unsigned NOT NULL,
				left_height  int(11) unsigned NOT NULL,
				main_height  int(11) unsigned NOT NULL,
				right_height  int(11) unsigned NOT NULL,
				botton_height  int(11) unsigned NOT NULL,

				left_selection int(11) unsigned NOT NULL,
				main_selection int(11) unsigned NOT NULL,
				right_selection int(11) unsigned NOT NULL,
				botton_selection int(11) unsigned NOT NULL,

				left_selection_val varchar(255) NULL,
				main_selection_val varchar(255) NULL,
				right_selection_val varchar(255) NULL,
				botton_selection_val varchar(255) NULL,
				PRIMARY KEY (`id_top_menu`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'top_menu_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'top_menu_lang(
				id_top_menu int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title varchar(255) NOT NULL,
				title_left_selection varchar(255) NULL,
				title_main_selection varchar(255) NULL,
				title_right_selection varchar(255) NULL,
				title_botton_selection varchar(255) NULL,
				PRIMARY KEY(id_top_menu, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);

    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'top_menu_link';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'top_menu_link(
				id_top_menu_link int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_top_menu int(11) unsigned NOT NULL,
				position varchar(100) NOT NULL,
				link varchar(100) NOT NULL,
				PRIMARY KEY(id_top_menu_link)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);


    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'top_menu_link_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'top_menu_link_lang(
	      id int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_top_menu_link int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title varchar(100) NOT NULL,
				PRIMARY KEY(id, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);


    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'top_menu_content';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'top_menu_content(
				id int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_top_menu int(11) unsigned NOT NULL,
				position varchar(100) NOT NULL,
				type varchar(100) NOT NULL,
				value varchar(512) NOT NULL,
				PRIMARY KEY(id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);


    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'top_menu_description_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'top_menu_description_lang(
				id int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_top_menu int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				position varchar(100) NOT NULL,
				description TEXT NOT NULL,
				PRIMARY KEY(id, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);

  }

  public function uninstallDb()
  {
    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'top_menu';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'top_menu_lang';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'top_menu_link';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'top_menu_link_lang';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'top_menu_content';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'top_menu_content_lang';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'top_menu_description_lang';
    Db::getInstance()->execute($sql);

  }
  public function uninstallConfiguration()
  {
//    Configuration::deleteByName('GOMAKOIL_TOP_MENU_WIDTH');
  }

  private function _createTab()
  {
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = 'AdminTopMenu';
    $tab->name = array();
    foreach (Language::getLanguages(true) as $lang)
      $tab->name[$lang['id_lang']] = 'Top Menu';
    $tab->id_parent = -1;
    $tab->module = $this->name;
    $tab->add();
  }

  private function _removeTab()
  {
    $id_tab = (int)Tab::getIdFromClassName('AdminTopMenu');
    if ($id_tab)
    {
      $tab = new Tab($id_tab);
      $tab->delete();
    }
  }

  private function _setDataDb(){

    $data = array(
      array(
        'title'                => 'Our offers',
        'url'                  => '',
        'key'                  => 1,
        'custom'               => 0,
        'content'              => 1,
        'narrow'               => 1,
        'left_selection'       => 1,
        'main_selection'       => 0,
        'right_selection'      => 0,
        'botton_selection'     => 0,
        'left_selection_val'   => 'products',
        'main_selection_val'   => '',
        'right_selection_val'  => '',
        'botton_selection_val' => '',
        'width'                => 550,
        'height'               => 310,
        'left_width'           => 550,
        'left_height'          => 310,
        'main_width'           => 0,
        'main_height'          => 0,
        'right_width'          => 0,
        'right_height'         => 0,
        'botton_width'         => 0,
        'botton_height'        => 0,

        ),
      array(
        'title'                => 'Brands & Suppliers',
        'url'                  => '',
        'key'                  => 2,
        'custom'               => 0,
        'content'              => 1,
        'narrow'               => 1,
        'left_selection'       => 1,
        'main_selection'       => 1,
        'right_selection'      => 0,
        'botton_selection'     => 0,
        'left_selection_val'   => 'manufacturers',
        'main_selection_val'   => 'suppliers',
        'right_selection_val'  => '',
        'botton_selection_val' => '',
        'width'                => 600,
        'height'               => 150,
        'left_width'           => 300,
        'left_height'          => 150,
        'main_width'           => 300,
        'main_height'          => 150,
        'right_width'          => 0,
        'right_height'         => 0,
        'botton_width'         => 0,
        'botton_height'        => 0,
      ),
      array(
        'title'                => 'CONTACT',
        'url'                  => $this->context->link->getPageLink('contact'),
        'custom'               => 1,
        'key'                  => 3,
        'content'              => 0,
        'narrow'               => 0,
        'left_selection'       => 0,
        'main_selection'       => 0,
        'right_selection'      => 0,
        'botton_selection'     => 0,
        'left_selection_val'   => '',
        'main_selection_val'   => '',
        'right_selection_val'  => '',
        'botton_selection_val' => '',
        'width'                => 200,
        'height'               => 200,
        'left_width'           => 0,
        'left_height'          => 0,
        'main_width'           => 0,
        'main_height'          => 0,
        'right_width'          => 0,
        'right_height'         => 0,
        'botton_width'         => 0,
        'botton_height'        => 0,
      ),
      array(
        'title'                => 'BLOG',
        'url'                  => Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . 'blog/',
        'custom'               => 1,
        'key'                  => 4,
        'content'              => 0,
        'narrow'               => 0,
        'left_selection'       => '0',
        'main_selection'       => 0,
        'right_selection'      => 0,
        'botton_selection'     => 0,
        'left_selection_val'   => '',
        'main_selection_val'   => '',
        'right_selection_val'  => '',
        'botton_selection_val' => '',
        'width'                => 200,
        'height'               => 200,
        'left_width'           => 0,
        'left_height'          => 0,
        'main_width'           => 0,
        'main_height'          => 0,
        'right_width'          => 0,
        'right_height'         => 0,
        'botton_width'         => 0,
        'botton_height'        => 0,
      ),
    );

    foreach($data as $value){
      $this->_setItem($value);
    }
  }

  private function _setItem($value){
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
    $languages = Language::getLanguages(false);
    $obj = new topMenuClass();
    $obj->active = 1;
    $obj->custom = $value['custom'];
    $obj->content = $value['content'];
    $obj->narrow = $value['narrow'];
    $obj->left_selection = $value['left_selection'];
    $obj->main_selection = $value['main_selection'];
    $obj->right_selection = $value['right_selection'];
    $obj->botton_selection = $value['botton_selection'];
    $obj->left_selection_val = $value['left_selection_val'];
    $obj->main_selection_val = $value['main_selection_val'];
    $obj->right_selection_val = $value['right_selection_val'];
    $obj->botton_selection_val = $value['botton_selection_val'];
    $obj->width = $value['width'];
    $obj->height = $value['height'];
    $obj->left_width = $value['left_width'];
    $obj->left_height = $value['left_height'];
    $obj->main_width = $value['main_width'];
    $obj->main_height = $value['main_height'];
    $obj->right_width = $value['right_width'];
    $obj->right_height = $value['right_height'];
    $obj->botton_width = $value['botton_width'];
    $obj->botton_height = $value['botton_height'];
    $obj->link = $value['url'];

    foreach ($languages as $lang){
      $obj->title[$lang['id_lang']] = $value['title'];
    }
    $obj->save();

    if(($value['key'] == 1) && $value['content'] && ($value['left_selection_val'] == 'products')){
      $products = $this->getProductsId();
      foreach ($products as $product ){
        $this->addRow($product['id_product'], $obj->id, 'left', 'products');
      }
    }

    if(($value['key'] == 2) && $value['content'] && ($value['left_selection_val'] == 'manufacturers')){
      $manufacturers = Manufacturer::getManufacturers(false, $default_lang);
      $manufacturers = array_slice($manufacturers,0,3);
      foreach ($manufacturers as $manufacturer ){
        $this->addRow($manufacturer['id_manufacturer'], $obj->id, 'left', 'manufacturer');
      }
    }

    if(($value['key'] == 2) && $value['content'] && ($value['main_selection_val'] == 'suppliers')){
      $suppliers = Supplier::getSuppliers(false, $default_lang);
      $suppliers = array_slice($suppliers,0,3);
      foreach ($suppliers as $supplier ){
        $this->addRow($supplier['id_supplier'], $obj->id, 'main', 'supplier');
      }
    }


    if(($value['key'] == 5) && $value['content'] && ($value['left_selection_val'] == 'video')){
      
    }

  }

  public function addRow($val, $id_top_menu, $section, $type){

    if($type == 'video'){
      $val = $val;
    }
    else{
      $val = (int)$val;
    }

    $base = array(
      'id_top_menu'       => (int)$id_top_menu,
      'position'          =>  pSQL($section),
      'type'              =>  $type,
      'value'             =>  $val,
    );
    Db::getInstance()->insert('top_menu_content', $base);
  }



  public function hookActionAdminControllerSetMedia()
  {

    if(Tools::getValue('controller') == 'AdminTopMenu'){
      $this->context->controller->addCSS($this->_path.'views/css/topmenu.css');
      $this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/plugins/select2/jquery.select2.js');
      $this->context->controller->addJS($this->_path.'views/js/topmenu.js');

      $this->context->controller->addJS(array(
        _PS_JS_DIR_.'tiny_mce/tiny_mce.js',
        _PS_JS_DIR_.'admin/tinymce.inc.js',
      ));
    }


  }

  public function hookHeader() {

    $this->context->controller->registerStylesheet('topmenu', 'modules/topmenu/views/css/topmenu.css', array('media' => 'all', 'priority' => 150));
    $this->context->controller->registerJavascript('topmenu', 'modules/topmenu/views/js/topmenu_front.js', array('media' => 'all', 'position' => 'bottom', 'priority' => 150));

  }

  public function getContent()
  {
    Tools::redirectAdmin($this->context->link->getAdminLink('AdminTopMenu'));
  }


  public function blockContent($id_top_menu, $selection, $selection_val, $id_lang, $id_shop){

    $val = '';

    if( $selection_val == 'links'){
      $val = $this->getLinksBlock($id_top_menu, $selection, $id_lang, $id_shop);
    }
    if( $selection_val == 'text'){
      $val = $this->getDescriptionBlock($id_top_menu, $selection, $id_lang, $id_shop);
    }
    if( $selection_val == 'products'){
      $val = $this->getProductsBlock($id_top_menu, $selection, $id_lang, $id_shop);
    }
    if( $selection_val == 'categories'){
      $val = $this->getCategoriesBlock($id_top_menu, $selection, $id_lang, $id_shop);
    }
    if( $selection_val == 'cms'){
      $val = $this->getCmsBlock($id_top_menu, $selection, $id_lang, $id_shop);
    }
    if( $selection_val == 'video'){
      $val = $this->getVideoBlock($id_top_menu, $selection, $id_lang, $id_shop);
    }
    if( $selection_val == 'manufacturers'){
      $val = $this->getManufacturersBlock($id_top_menu, $selection, $id_lang, $id_shop);
    }
    if( $selection_val == 'suppliers'){
      $val = $this->getSuppliersBlock($id_top_menu, $selection, $id_lang, $id_shop);
    }
    return $val;
  }


  public function getVideoBlock($id_top_menu, $selection, $id_lang, $id_shop){
    $video = $this->getIds($id_top_menu, $selection, "video");
    if(isset($video[0]['id']) && $video[0]['id']){
      $video = $video[0]['id'];
    }

    $this->context->smarty->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'video'   => $video,
      ));
    return $this->display(__FILE__, 'views/templates/hook/videoBlock.tpl');
  }
  public function getSuppliersBlock($id_top_menu, $selection, $id_lang, $id_shop){
    $suppliersIds = $this->getIds($id_top_menu, $selection, "supplier");
    if(isset($suppliersIds[0]['id']) && $suppliersIds[0]['id']){
      $suppliersIds = $suppliersIds[0]['id'];
      $suppliers = $this->getSuppliersByIds($suppliersIds);
      foreach ($suppliers as $key => $val){
        $suppliers[$key]['link'] = Context::getContext()->link->getSupplierLink((int)$val['id_supplier'], null, (int)$id_lang, $id_shop);
        $suppliers[$key]['title'] = $suppliers[$key]['name'];
      }
      $this->context->smarty->assign(
        array(
          'id_shop' => $id_shop,
          'id_lang' => $id_lang,
          'links'   => $suppliers,
        ));
      return $this->display(__FILE__, 'views/templates/hook/linksBlock.tpl');
    }

  }

  public function getManufacturersBlock($id_top_menu, $selection, $id_lang, $id_shop){
    $manufacturersIds = $this->getIds($id_top_menu, $selection, "manufacturer");

    if(isset($manufacturersIds[0]['id']) && $manufacturersIds[0]['id']){
      $manufacturersIds = $manufacturersIds[0]['id'];

      $manufacturers = $this->getManufacturersByIds($manufacturersIds);
      foreach ($manufacturers as $key => $val){
        $manufacturers[$key]['link'] = Context::getContext()->link->getManufacturerLink((int)$val['id_manufacturer'], null, null, (int)$id_lang, $id_shop);
        $manufacturers[$key]['title'] = $manufacturers[$key]['name'];
      }
      $this->context->smarty->assign(
        array(
          'id_shop' => $id_shop,
          'id_lang' => $id_lang,
          'links'   => $manufacturers,
        ));
      return $this->display(__FILE__, 'views/templates/hook/linksBlock.tpl');

    }

  }

  public function getCmsBlock($id_top_menu, $selection, $id_lang, $id_shop){
    $cmsIds = $this->getIds($id_top_menu, $selection, "cms");
    if(isset($cmsIds[0]['id']) && $cmsIds[0]['id']){
      $cmsIds = $cmsIds[0]['id'];
    }
    $cms = $this->getCmsByIds($id_lang, $id_shop, $cmsIds);
    foreach ($cms as $key => $val){
      $cms[$key]['link'] = Context::getContext()->link->getCMSLink((int)$val['id_cms'], null, null, (int)$id_lang, $id_shop);
      $cms[$key]['title'] = $cms[$key]['meta_title'];
    }
    $this->context->smarty->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'links'   => $cms,
      ));
    return $this->display(__FILE__, 'views/templates/hook/linksBlock.tpl');
  }

  public function getCategoriesBlock($id_top_menu, $selection, $id_lang, $id_shop){
    $categoriesIds = $this->getIds($id_top_menu, $selection, "categories");
    if(isset($categoriesIds[0]['id']) && $categoriesIds[0]['id']){
      $categoriesIds = $categoriesIds[0]['id'];
    }
    $category = $this->getCategoriesByIds($id_lang, $id_shop, $categoriesIds);
    foreach ($category as $key => $cat){
      $category[$key]['link'] = Context::getContext()->link->getCategoryLink($cat['id_category'], $cat['link_rewrite'], $id_lang, null, $id_shop);
      $category[$key]['title'] = $category[$key]['name'];
    }
    $this->context->smarty->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'links'   => $category,
      ));
    return $this->display(__FILE__, 'views/templates/hook/linksBlock.tpl');
  }

  public function getProductsBlock($id_top_menu, $selection, $id_lang, $id_shop)
  {
    $obj = new topMenuClass($id_top_menu);
    $val_w = 0;
    $val_h = 0;

    if($selection == 'left'){
      $val_w = $obj->left_width;
      $val_h = $obj->left_height;
    }

    if($selection == 'main'){
      $val_w = $obj->main_width;
      $val_h = $obj->main_height;
    }

    if($selection == 'right'){
      $val_w = $obj->right_width;
      $val_h = $obj->right_height;
    }

    if($selection == 'botton'){
      $val_w = $obj->botton_width;
      $val_h = $obj->botton_height;
    }

    $val_w = $val_w-40;
    $val_h = $val_h-40;

    $productsIds = $this->getIds($id_top_menu, $selection, "products");
    if(isset($productsIds[0]['id']) && $productsIds[0]['id']){
      $productsIds = $productsIds[0]['id'];
    }
    else{
      return false;
    }

    $products = $this->getProductsByIds($id_lang, $id_shop, $productsIds);

    $assembler = new ProductAssembler($this->context);
    $presenterFactory = new ProductPresenterFactory($this->context);
    $presentationSettings = $presenterFactory->getPresentationSettings();
    $presenter = new ProductListingPresenter(
      new ImageRetriever(
        $this->context->link
      ),
      $this->context->link,
      new PriceFormatter(),
      new ProductColorsRetriever(),
      $this->context->getTranslator()
    );

    $array_result = array();
    foreach ($products as $prow) {
      $array_result[] = $presenter->present(
        $presentationSettings,
        $assembler->assembleProduct($prow),
        $this->context->language
      );
    }

    $priceDisplay = Product::getTaxCalculationMethod((int) $this->context->cookie->id_customer);
    $productPriceWithoutReduction = 0;


    $this->context->smarty->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'val_w' => $val_w,
        'val_h' => $val_h,
        'products'   => $array_result,
        'priceDisplay' => $priceDisplay,
        'productPriceWithoutReduction' => $productPriceWithoutReduction,
        'displayUnitPrice' => false,
        'displayPackPrice' => false,
      ));
    return $this->display(__FILE__, 'views/templates/hook/productsBlock.tpl');
  }

  public function getDescriptionBlock($id_top_menu, $selection, $id_lang, $id_shop){
    $description = $this->getDescriptionMenu($id_top_menu, $selection, $id_lang, $id_shop);
    if(isset($description[0]['description']) && $description[0]['description']){
      $description = $description[0]['description'];
    }
    $this->context->smarty->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'description'   => $description,
      ));
    return $this->display(__FILE__, 'views/templates/hook/descriptionBlock.tpl');
  }

  public function getLinksBlock($id_top_menu, $selection, $id_lang, $id_shop){
    $links = $this->getLinksMenu($id_top_menu, $selection, $id_lang, $id_shop);
    $this->context->smarty->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'links'   => $links,
      ));
    return $this->display(__FILE__, 'views/templates/hook/linksBlock.tpl');
  }

  public function getLinksMenu($id_top_menu, $position, $id_lang, $id_shop){
    $sql = '
			SELECT tm.link, tml.title
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

  public function getDescriptionMenu($id_top_menu, $position, $id_lang, $id_shop){
    $sql = '
      SELECT  p.description
        FROM ' . _DB_PREFIX_ . 'top_menu_description_lang p
        WHERE p.id_top_menu = ' . (int)$id_top_menu . '
        AND p.position = "' . pSQL($position) . '"
        AND p.id_shop = '.(int)$id_shop.'
        AND p.id_lang = '.(int)$id_lang.'
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

  public function getSuppliersByIds($ids){
    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'supplier as s
      WHERE s.id_supplier IN ('.pSQL($ids).')

			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getManufacturersByIds($ids){
    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'manufacturer as m
      WHERE m.id_manufacturer IN ('.pSQL($ids).')

			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function getCmsByIds($id_lang, $id_shop, $ids){
    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'cms_lang as pl
      INNER JOIN ' . _DB_PREFIX_ . 'cms as p
      ON p.id_cms = pl.id_cms
      WHERE pl.id_lang = ' . (int)$id_lang . '
      AND pl.id_shop = ' . (int)$id_shop . '
      AND p.id_cms IN ('.pSQL($ids).')

			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getCategoriesByIds($id_lang, $id_shop, $ids){
    $sql = '
			SELECT pl.name, p.*, pl.link_rewrite
      FROM ' . _DB_PREFIX_ . 'category_lang as pl

      INNER JOIN ' . _DB_PREFIX_ . 'category as p
      ON p.id_category = pl.id_category
      WHERE pl.id_lang = ' . (int)$id_lang . '
      AND pl.id_shop = ' . (int)$id_shop . '
      AND p.id_category IN ('.pSQL($ids).')

			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getIds($id_top_menu, $selection, $type){
    $sql = '
			SELECT  GROUP_CONCAT( DISTINCT(p.value) )  as id
        FROM ' . _DB_PREFIX_ . 'top_menu_content p
        WHERE p.id_top_menu = ' . (int)$id_top_menu . '
        AND p.position = "' . pSQL($selection) . '"
        AND p.type = "'.pSQL($type).'"
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function renderWidget($hookName, array $params = array())
  {
    if(!$this->active){
      return false;
    }
    $this->smarty->assign($this->getWidgetVariables($hookName, $params));
    return $this->fetch($this->templateFile);
  }

  public function getWidgetVariables($hookName, array $params = array())
  {


    $menu = $this->_menu->getTopMenu(Context::getContext()->shop->id, Context::getContext()->language->id);
    $imgExists = dirname(__FILE__).'/views/img/';
    $imgDirTopMenu = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/topmenu/views/img/';

    foreach($menu as $key => $value){

      if($value['content']){
        $file_path = $imgExists.$value['id_top_menu'].'.png';
        $isset = file_exists($file_path);
        if($isset){
          $img = getimagesize($file_path);
          $menu[$key]['background'] = array('width' => $img[0], 'height' => $img[1], 'link' => $imgDirTopMenu.$value['id_top_menu'].'.png');
        }

        if($value['left_selection']){
          if($value['left_selection_val']){
            $menu[$key]['left_content'] = $this->blockContent($value['id_top_menu'], 'left', $value['left_selection_val'], Context::getContext()->language->id, Context::getContext()->shop->id);
          }
        }

        if($value['main_selection']){
          if($value['main_selection_val']){
            $menu[$key]['main_content'] = $this->blockContent($value['id_top_menu'], 'main', $value['main_selection_val'], Context::getContext()->language->id, Context::getContext()->shop->id);
          }
        }

        if($value['right_selection']){
          if($value['right_selection_val']){
            $menu[$key]['right_content'] = $this->blockContent($value['id_top_menu'], 'right', $value['right_selection_val'], Context::getContext()->language->id, Context::getContext()->shop->id);
          }
        }

        if($value['botton_selection']){
          if($value['botton_selection_val']){
            $menu[$key]['botton_content'] = $this->blockContent($value['id_top_menu'], 'botton', $value['botton_selection_val'], Context::getContext()->language->id, Context::getContext()->shop->id);
          }
        }


      }

//      var_dump($menu); die;
    }



    return array(
      'id_shop'         => Context::getContext()->shop->id,
      'id_lang'         => Context::getContext()->language->id,
      'menu'            => $menu,
      'imgExists'       => $imgExists,
      'imgDirTopMenu'   => $imgDirTopMenu,

    );

  }

  public function getProductsId(){
    $sql = '
			SELECT p.id_product
      FROM ' . _DB_PREFIX_ . 'product as p
      WHERE active= 1
      ORDER BY p.id_product
      LIMIT 0,5
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

}
