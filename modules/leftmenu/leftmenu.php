<?php

if (!defined('_PS_VERSION_')){
  exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

class Leftmenu extends Module implements WidgetInterface {

    private $_shopId;
    private $_langId;

  public function __construct()
  {
    $this->_shopId = Context::getContext()->shop->id;
    $this->_langId = Context::getContext()->language->id;
    $this->name = 'leftmenu';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->bootstrap = true;
	  $this->module_key = "b5ca86924a86ce63c950edc73d088861";

    parent::__construct();

    $this->displayName = $this->l('Vertical drop-down menu');
    $this->description = $this->l('Adds a new vertical menu  of your e-commerce website.');
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

  }

  public function install()
  {
    if (!parent::install()
      || !$this->registerHook('header')
      || !$this->registerHook('footer')
      || !$this->registerHook('displayLeftColumn')
      || !$this->registerHook('ActionAdminControllerSetMedia')
      || !$this->registerHook('displayLeftMenuProductPage')
      || !$this->registerHook('categoryAddition')
      || !$this->registerHook('categoryUpdate')
      || !$this->registerHook('categoryDeletion')
      || !Configuration::updateValue('LEFT_MENU_MAX_DEPTH', 4)
    )
      return false;

    $this->_createTab('AdminLeftMenu', 'Left Menu');
    $this->_createTab('AdminLeftMenuItem', 'Left MenuItem ');
    $this->installDb();

    return true;
  }

  public function uninstall(){

    if ( !parent::uninstall() )
      return false;

    $this->_removeTab('AdminLeftMenu');
    $this->_removeTab('AdminLeftMenuItem');
    $this->uninstallDb();
    return true;
  }

  public function installDb()
  {
    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'left_menu';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'left_menu(
				id_left_menu int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_category int(11) unsigned NOT NULL,
				width int(11) NULL,
				font_size int(11) NULL,
        right_section_width int(11) NULL,
				right_section boolean NOT NULL,
				bottom_section boolean NOT NULL,
				right_section_val varchar(255) NULL,
				bottom_section_val varchar(255) NULL,
				PRIMARY KEY (`id_left_menu`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'left_menu_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'left_menu_lang(
				id_left_menu int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title_right_section varchar(255) NULL,
				title_bottom_section varchar(255) NULL,
				PRIMARY KEY(id_left_menu, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);

    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'left_menu_link';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'left_menu_link(
				id_left_menu_link int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_left_menu int(11) unsigned NOT NULL,
				position varchar(100) NOT NULL,
				link varchar(100) NOT NULL,
				PRIMARY KEY(id_left_menu_link)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);


    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'left_menu_link_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'left_menu_link_lang(
	      id int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_left_menu_link int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title varchar(100) NOT NULL,
				PRIMARY KEY(id, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);


    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'left_menu_content';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'left_menu_content(
				id int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_left_menu int(11) unsigned NOT NULL,
				position varchar(100) NOT NULL,
				type varchar(100) NOT NULL,
				value varchar(512) NOT NULL,
				PRIMARY KEY(id)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);


    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'left_menu_description_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'left_menu_description_lang(
				id int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_left_menu int(11) unsigned NOT NULL,
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
    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'left_menu';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'left_menu_lang';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'left_menu_link';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'left_menu_link_lang';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'left_menu_content';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'left_menu_content_lang';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'left_menu_description_lang';
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

  public function hookActionAdminControllerSetMedia()
  {


    if(Tools::getValue('controller') == 'AdminLeftMenuItem' || Tools::getValue('controller') == 'AdminLeftMenu'){
      $this->context->controller->addCSS($this->_path.'views/css/leftmenu.css');
      $this->context->controller->addJS(__PS_BASE_URI__.'js/jquery/plugins/select2/jquery.select2.js');
      $this->context->controller->addJS($this->_path.'views/js/leftmenu.js');

      $this->context->controller->addJS(array(
        _PS_JS_DIR_.'tiny_mce/tiny_mce.js',
        _PS_JS_DIR_.'admin/tinymce.inc.js',
      ));
    }

  }

  public function hookHeader() {

    $this->context->controller->registerStylesheet('leftmenu', 'modules/leftmenu/views/css/leftmenu_front.css', array('media' => 'all', 'priority' => 150));
    $this->context->controller->registerJavascript('leftmenu', 'modules/leftmenu/views/js/leftmenu_front.js', array('media' => 'all', 'position' => 'bottom', 'priority' => 150));

  }

  public function getContent()
  {
    Tools::redirectAdmin($this->context->link->getAdminLink('AdminLeftMenu'));
  }

  public function hookDisplayLeftMenuProductPage($params){

    if(Context::getContext()->controller->php_self !== 'product'){
      return false;
    }
    return $this->hookLeftColumn($params);

  }

  public function hookLeftColumn($params)
  {
    $category = new Category((int)Configuration::get('PS_HOME_CATEGORY'), Context::getContext()->language->id);
    $self = '';

    if(Context::getContext()->controller->php_self == 'product'){
      $self = '_product';
    }

    $cacheId = $this->name.$self.'_'.Context::getContext()->language->id;

    if (!$this->isCached('blockMenu.tpl', $cacheId))
    {
      $resultIds = array();
      $resultParents = array();
      $maxdepth = Configuration::get('LEFT_MENU_MAX_DEPTH');
      if (Validate::isLoadedObject($category))
      {
        if ($maxdepth > 0)
          $maxdepth += $category->level_depth;
      }
      $result = $this->getCategoriesLeftMenu($category, $maxdepth);



      foreach ($result as &$row)
      {
        $resultParents[$row['id_parent']][] = &$row;
        $resultIds[$row['id_category']] = &$row;
      }
      $blockCategTree = $this->getTree($resultParents, $resultIds, $maxdepth, ($category ? $category->id : null));
      $this->smarty->assign('blockCategTree', $blockCategTree);
      $this->smarty->assign('tpl_path', _PS_MODULE_DIR_.'leftmenu/views/templates/hook/categoryTree.tpl');
      $this->smarty->assign('page_name', Context::getContext()->controller->php_self);
    }
    return $this->display(__FILE__, 'blockMenu.tpl', $cacheId);
  }


  public function renderWidget($hookName = null, array $configuration = array())
  {
    if(!$this->active){
      return false;
    }
  }

  public function getWidgetVariables($hookName = null, array $configuration = array())
  {

  }


  private function _clearCacheLeftMenu()
  {
    $this->_clearCache('blockMenu.tpl');
    $this->_clearCache('blockCategoriesFooter.tpl');
  }

  public function hookCategoryAddition($params)
  {
    $this->_clearCacheLeftMenu();
  }

  public function hookCategoryUpdate($params)
  {
    $this->_clearCacheLeftMenu();
  }

  public function hookCategoryDeletion($params)
  {
    $this->_clearCacheLeftMenu();
  }

  public function getTree($resultParents, $resultIds, $maxDepth, $id_category = null, $currentDepth = 0)
  {
    $width = false;

    $font_size = false;
    $right_section_width = false;
    $right_section = false;
    $bottom_section = false;
    $right_section_val = false;
    $bottom_section_val = false;
    $title_right_section = false;
    $title_bottom_section = false;
    $background = false;
    $tpl_right = false;
    $tpl_bottom = false;

    if (is_null($id_category))
      $id_category = $this->context->shop->getCategory();
    $children = array();
    if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)){
      foreach ($resultParents[$id_category] as $subcat){
        $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);
      }
    }

    if (isset($resultIds[$id_category]))
    {
      $link = $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
      $name = $resultIds[$id_category]['name'];
      $desc = $resultIds[$id_category]['description'];
      if(isset($resultIds[$id_category]['level_depth'])){
        $level_depth = $resultIds[$id_category]['level_depth'];
      }
      else{
        $level_depth = '';
      }

      $icon = '';

      if($level_depth == 2){
        if(file_exists(_PS_MODULE_DIR_.'/leftmenu/views/img/background/'.$resultIds[$id_category]['id_left_menu'].'.png')){
          $img = getimagesize(_PS_MODULE_DIR_.'/leftmenu/views/img/background/'.$resultIds[$id_category]['id_left_menu'].'.png');
          $background = array('width' => $img[0], 'height' => $img[1], 'link' => _MODULE_DIR_.'/leftmenu/views/img/background/'.$resultIds[$id_category]['id_left_menu'].'.png');
        }
        $icon = (file_exists(_PS_MODULE_DIR_.'/leftmenu/views/img/icon/'.$resultIds[$id_category]['id_left_menu'].'.png')) ? (_MODULE_DIR_.'/leftmenu/views/img/icon/'.$resultIds[$id_category]['id_left_menu'].'.png') : (_MODULE_DIR_.'/leftmenu/views/img/icon/undefined.png');
        $width = $resultIds[$id_category]['width'];
        $font_size  = $resultIds[$id_category]['font_size'];
        $right_section_width = $resultIds[$id_category]['right_section_width'];
        $right_section = $resultIds[$id_category]['right_section'];
        $bottom_section = $resultIds[$id_category]['bottom_section'];
        $right_section_val = $resultIds[$id_category]['right_section_val'];
        $bottom_section_val = $resultIds[$id_category]['bottom_section_val'];
        $title_right_section = $resultIds[$id_category]['title_right_section'];
        $title_bottom_section = $resultIds[$id_category]['title_bottom_section'];
        if($right_section){
          if($right_section_val == 'links'){
            $tpl_right = $this->getLinksBlock($resultIds[$id_category]['id_left_menu'], 'right', $this->context->language->id, $this->context->shop->id);
          }
          if($right_section_val == 'description'){
            $tpl_right = $this->getDescription($resultIds[$id_category]['id_left_menu'], 'right', $this->context->language->id, $this->context->shop->id);
          }
          if($right_section_val == 'cms'){
            $tpl_right = $this->getCmsBlock($resultIds[$id_category]['id_left_menu'], 'right', $this->context->language->id, $this->context->shop->id);
          }
          if($right_section_val == 'video'){
            $tpl_right = $this->getVideoBlock($resultIds[$id_category]['id_left_menu'], 'right', $this->context->language->id, $this->context->shop->id);
          }
          if($right_section_val == 'manufacturers'){
            $tpl_right = $this->getManufacturersBlock($resultIds[$id_category]['id_left_menu'], 'right', $this->context->language->id, $this->context->shop->id);
          }
          if($right_section_val == 'suppliers'){
            $tpl_right = $this->getSuppliersBlock($resultIds[$id_category]['id_left_menu'], 'right', $this->context->language->id, $this->context->shop->id);
          }
          if($right_section_val == 'products'){
            $tpl_right = $this->getProductsBlock($resultIds[$id_category]['id_left_menu'], 'right', $this->context->language->id, $this->context->shop->id);
          }
        }
        if($bottom_section){
          if($bottom_section_val == 'links'){
            $tpl_bottom = $this->getLinksBlock($resultIds[$id_category]['id_left_menu'], 'bottom', $this->context->language->id, $this->context->shop->id);
          }
          if($bottom_section_val == 'description'){
            $tpl_bottom = $this->getDescription($resultIds[$id_category]['id_left_menu'], 'bottom', $this->context->language->id, $this->context->shop->id);
          }
          if($bottom_section_val == 'cms'){
            $tpl_bottom = $this->getCmsBlock($resultIds[$id_category]['id_left_menu'], 'bottom', $this->context->language->id, $this->context->shop->id);
          }
          if($bottom_section_val == 'video'){
            $tpl_bottom = $this->getVideoBlock($resultIds[$id_category]['id_left_menu'], 'bottom', $this->context->language->id, $this->context->shop->id);
          }
          if($bottom_section_val == 'manufacturers'){
            $tpl_bottom = $this->getManufacturersBlockBottom($resultIds[$id_category]['id_left_menu'], 'bottom', $this->context->language->id, $this->context->shop->id);
          }
          if($bottom_section_val == 'suppliers'){
            $tpl_bottom = $this->getSuppliersBlockBottom($resultIds[$id_category]['id_left_menu'], 'bottom', $this->context->language->id, $this->context->shop->id);
          }
          if($bottom_section_val == 'products'){
            $tpl_bottom = $this->getProductsBlock($resultIds[$id_category]['id_left_menu'], 'bottom', $this->context->language->id, $this->context->shop->id);
          }
        }

      }
    }
    else{
      $link = $name = $desc = $level_depth = $icon = '';
    }

    $return = array(
      'id' => $id_category,
      'link' => $link,
      'icon' => $icon,
      'name' => $name,
      'desc'=> $desc,
      'children' => $children,
      'level_depth'=> $level_depth,
      'width' => $width,
      'font_size' => $font_size,
      'right_section_width' => $right_section_width,
      'right_section' => $right_section,
      'bottom_section'=> $bottom_section,
      'right_section_val' => $right_section_val,
      'bottom_section_val'=> $bottom_section_val,
      'title_right_section' => $title_right_section,
      'title_bottom_section'=> $title_bottom_section,
      'background'=> $background,
      'tpl_right'=> $tpl_right,
      'tpl_bottom'=> $tpl_bottom,
    );
    return $return;
  }

  public function hookFooter($params)
  {
    $cacheId = $this->name.'_footer_'.Context::getContext()->language->id;
    if (!$this->isCached('blockCategoriesFooter.tpl', $cacheId))
    {
      $maxdepth = (int)Configuration::get('PS_HOME_CATEGORY');
      $groups = implode(', ', Customer::getGroupsStatic((int)$this->context->customer->id));
      if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
				SELECT DISTINCT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
				FROM `'._DB_PREFIX_.'category` c
				'.Shop::addSqlAssociation('category', 'c').'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('cl').')
				LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cg.`id_category` = c.`id_category`)
				WHERE (c.`active` = 1 OR c.`id_category` = '.(int)Configuration::get('PS_ROOT_CATEGORY').')
				'.((int)($maxdepth) != 0 ? ' AND `level_depth` <= '.(int)($maxdepth) : '').'
				AND cg.`id_group` IN ('.pSQL($groups).')
				ORDER BY `level_depth` ASC, category_shop.`position` ASC '))
        return;
      $resultParents = array();
      $resultIds = array();

      foreach ($result as &$row)
      {
        $resultParents[$row['id_parent']][] = &$row;
        $resultIds[$row['id_category']] = &$row;
      }

      $blockCategTree = $this->getTree($resultParents, $resultIds, Configuration::get('LEFT_MENU_MAX_DEPTH'));
      unset($resultParents, $resultIds);

      $this->smarty->assign('blockCategTree', $blockCategTree);
      $this->smarty->assign('branche_tpl_path', _PS_MODULE_DIR_.'leftmenu/views/templates/hook/categoryTree.tpl');
    }
    $display = $this->display(__FILE__, 'blockCategoriesFooter.tpl', $cacheId);

    return $display;
  }

  public function getProductsBlock($id_left_menu, $section, $id_lang, $id_shop)
  {
    $productsIds = $this->getIds($id_left_menu, $section, "products");
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
        'id_shop'     => $id_shop,
        'id_lang'     => $id_lang,
        'products'    => $array_result,
        'priceDisplay' => $priceDisplay,
        'productPriceWithoutReduction' => $productPriceWithoutReduction,
        'displayUnitPrice' => false,
        'displayPackPrice' => false,
      ));

    if($section == 'right'){
      return $this->display(__FILE__, 'views/templates/hook/productsBlockFront.tpl');
    }

    if($section == 'bottom'){
      return $this->display(__FILE__, 'views/templates/hook/productsBlockFrontBotton.tpl');
    }
  }

  public function getManufacturersBlockBottom($id_left_menu, $section, $id_lang, $id_shop){

    $manufacturersIds = $this->getIds($id_left_menu, $section, "manufacturers");

    if(isset($manufacturersIds[0]['id']) && $manufacturersIds[0]['id']){
      $manufacturersIds = $manufacturersIds[0]['id'];
    }
    $manufacturers = $this->getManufacturersByIds($manufacturersIds);
    foreach ($manufacturers as $key => $val){
      $manufacturers[$key]['link'] = Context::getContext()->link->getManufacturerLink((int)$val['id_manufacturer'], null, null, (int)$id_lang, $id_shop);
      $manufacturers[$key]['title'] = $manufacturers[$key]['name'];
      $manufacturers[$key]['image'] = (!file_exists(_PS_MANU_IMG_DIR_.$val['id_manufacturer'].'-'.ImageTypeCore::getFormattedName('small').'.jpg')) ? (_THEME_MANU_DIR_.$this->context->language->iso_code.'-default'.'-'.ImageType::getFormattedName('small').'.jpg') : (_THEME_MANU_DIR_.$val['id_manufacturer'].'-'.ImageType::getFormattedName('small').'.jpg');
    }
    $this->context->smarty->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'links'   => $manufacturers,
      ));
    return $this->display(__FILE__, 'views/templates/hook/manufacturersSuppliersBlock.tpl');
  }

  public function getSuppliersBlockBottom($id_left_menu, $section, $id_lang, $id_shop){
    $suppliersIds = $this->getIds($id_left_menu, $section, "suppliers");
    if(isset($suppliersIds[0]['id']) && $suppliersIds[0]['id']){
      $suppliersIds = $suppliersIds[0]['id'];
    }
    $suppliers = $this->getSuppliersByIds($suppliersIds);
    foreach ($suppliers as $key => $val){
      $suppliers[$key]['link'] = Context::getContext()->link->getSupplierLink((int)$val['id_supplier'], null, (int)$id_lang, $id_shop);
      $suppliers[$key]['title'] = $suppliers[$key]['name'];
      $suppliers[$key]['image'] = (!file_exists(_PS_SUPP_IMG_DIR_.'/'.$val['id_supplier'].'-'.ImageType::getFormattedName('small').'.jpg')) ? (_THEME_SUP_DIR_.$this->context->language->iso_code.'-default'.'-'.ImageType::getFormattedName('small').'.jpg') : (_THEME_SUP_DIR_.$val['id_supplier'].'-'.ImageType::getFormattedName('small').'.jpg');
    }
    $this->context->smarty->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'links'   => $suppliers,
      ));
    return $this->display(__FILE__, 'views/templates/hook/manufacturersSuppliersBlock.tpl');
  }

  public function getSuppliersBlock($id_left_menu, $section, $id_lang, $id_shop){
    $suppliersIds = $this->getIds($id_left_menu, $section, "suppliers");
    if(isset($suppliersIds[0]['id']) && $suppliersIds[0]['id']){
      $suppliersIds = $suppliersIds[0]['id'];
    }
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

  public function getManufacturersBlock($id_left_menu, $section, $id_lang, $id_shop){

    $manufacturersIds = $this->getIds($id_left_menu, $section, "manufacturers");
    if(isset($manufacturersIds[0]['id']) && $manufacturersIds[0]['id']){
      $manufacturersIds = $manufacturersIds[0]['id'];
    }
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

  public function getVideoBlock($id_left_menu, $section, $id_lang, $id_shop){
    $video = $this->getIds($id_left_menu, $section, "video");
    if(isset($video[0]['id']) && $video[0]['id']){
      $video = $video[0]['id'];
    }
    $this->context->smarty->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'video'   => $video,
      ));
    return $this->display(__FILE__, 'views/templates/hook/videoBlockFront.tpl');
  }

  public function getCmsBlock($id_left_menu, $section, $id_lang, $id_shop){
    $cmsIds = $this->getIds($id_left_menu, $section, "cms");
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

  public function getDescription($id_left_menu, $section, $id_lang, $id_shop){
    $description = $this->getDescriptionMenu($id_left_menu, $section, $id_lang, $id_shop);
    if(isset($description[0]['description']) && $description[0]['description']){
      $description = $description[0]['description'];
    }
    $this->context->smarty->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'description'   => $description,
      ));
    return $this->display(__FILE__, 'views/templates/hook/description.tpl');
  }

  protected function getCacheId($name = null)
  {
    $cache_id = parent::getCacheId();

    if ($name !== null)
      $cache_id .= '|'.$name;

    if ((Tools::getValue('id_product') || Tools::getValue('id_category')) && isset($this->context->cookie->last_visited_category) && $this->context->cookie->last_visited_category)
      $cache_id .= '|'.(int)$this->context->cookie->last_visited_category;

    return $cache_id.'|'.implode('-', Customer::getGroupsStatic($this->context->customer->id));
  }

  public function getCategoriesLeftMenu($category, $maxdepth){

    $range = '';
    if (Validate::isLoadedObject($category))
    {
      $range = 'AND nleft >= '.(int)$category->nleft.' AND nright <= '.(int)$category->nright;
    }

    $sql = '
			SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite, c.level_depth,
			 lm.id_left_menu, lm.width, lm.right_section_width,
			 lm.right_section, lm.bottom_section, lm.right_section_val, lm.bottom_section_val,
			 lml.title_right_section, lml.title_bottom_section, lm.font_size
			FROM `'._DB_PREFIX_.'category` c
			INNER JOIN `'._DB_PREFIX_.'category_lang` cl
			ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('cl').')
			INNER JOIN `'._DB_PREFIX_.'category_shop` cs
			ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = '.(int)$this->context->shop->id.')
			LEFT JOIN `'._DB_PREFIX_.'left_menu` lm
			ON (lm.`id_category` = c.`id_category`)
			LEFT JOIN `'._DB_PREFIX_.'left_menu_lang` lml
			ON (lm.`id_left_menu` = lml.`id_left_menu` AND lml.`id_shop` = '.(int)$this->context->shop->id.' AND lml.`id_lang` = '.(int)$this->context->language->id.')
			WHERE (c.`active` = 1 OR c.`id_category` = '.(int)Configuration::get('PS_HOME_CATEGORY').')
			AND c.`id_category` != '.(int)Configuration::get('PS_ROOT_CATEGORY').'
      '.((int)$maxdepth != 0 ? ' AND `level_depth` <= '.(int)$maxdepth : '').'
			'.$range.'

			AND c.id_category IN (
				SELECT id_category
				FROM `'._DB_PREFIX_.'category_group`
				WHERE `id_group` IN ('.pSQL(implode(', ', Customer::getGroupsStatic((int)$this->context->customer->id))).')
			)
			ORDER BY `level_depth` ASC, cs.`position` ASC';


    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function getIds($id_left_menu, $selection, $type){
    $sql = '
			SELECT  GROUP_CONCAT( DISTINCT(p.value) )  as id
        FROM ' . _DB_PREFIX_ . 'left_menu_content p
        WHERE p.id_left_menu = ' . (int)$id_left_menu . '
        AND p.position = "' . pSQL($selection) . '"
        AND p.type = "'.pSQL($type).'"
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getLinksBlock($id_left_menu, $section, $id_lang, $id_shop){
    $links = $this->getLinksMenu($id_left_menu, $section, $id_lang, $id_shop);

    $this->context->smarty->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'links'   => $links,
      ));
    return $this->display(__FILE__, 'views/templates/hook/linksBlock.tpl');
  }

  public function getLinksMenu($id_left_menu, $position, $id_lang, $id_shop){
    $sql = '
			SELECT tm.link, tml.title
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

  public function getDescriptionMenu($id_left_menu, $position, $id_lang, $id_shop){
    $sql = '
      SELECT  p.description
        FROM ' . _DB_PREFIX_ . 'left_menu_description_lang p
        WHERE p.id_left_menu = ' . (int)$id_left_menu . '
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




}
