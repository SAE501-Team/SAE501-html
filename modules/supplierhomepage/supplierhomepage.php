<?php

if (!defined('_PS_VERSION_')){
  exit;
}
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class Supplierhomepage extends Module implements WidgetInterface  {

  private $_shopId;
  private $_langId;

  public function __construct()
  {
    $this->_shopId = Context::getContext()->shop->id;
    $this->_langId = Context::getContext()->language->id;
    $this->name = 'supplierhomepage';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('Suppliers block');
    $this->description = $this->l('Suppliers block.');
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
  }

  public function install()
  {
    if (!parent::install()
      || !$this->registerHook('header')
      || !$this->registerHook('ActionAdminControllerSetMedia')
      || !$this->registerHook('displayBeforeFooter')
      || !$this->registerHook('displayLeftColumn')
      || !$this->registerHook('actionObjectSupplierDeleteAfter')
      || !$this->registerHook('actionObjectSupplierAddAfter')
      || !$this->registerHook('actionObjectSupplierUpdateAfter')
      || !Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_TITLE', 1)
      || !Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_ALL_PAGE', 0)
    )
      return false;

    Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT', true);
    Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_INDEX_PAGE', false);
    Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_LEFT', true);
    Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_CENTER', true);
    Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_NB', 5);
    Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_FORM', false);

    $isset = $this->getImagesTypeManSup();

    if( !isset($isset[0]['id_image_type']) || !$isset[0]['id_image_type'] ){
      $this->generateImages();
    }

    return true;
  }

  public function uninstall(){

    if ( !parent::uninstall()
      || !Configuration::deleteByName('GOMAKOIL_SUPPLIER_DISPLAY_TITLE')
      || !Configuration::deleteByName('GOMAKOIL_SUPPLIER_DISPLAY_ALL_PAGE')
    )
      return false;

    return true;
  }

  public function generateImages(){

    $obj = new ImageType();
    $obj->name = 'man_sup_default';
    $obj->width = '166';
    $obj->height = '100';
    $obj->products = 0;
    $obj->categories = 0;
    $obj->scenes = 0;
    $obj->stores = 0;
    $obj->suppliers = 1;
    $obj->manufacturers = 1;
    $obj->save();

    return true;
  }

  public function getImagesTypeManSup(){
    $sql = "
      SELECT it.id_image_type
      FROM "._DB_PREFIX_."image_type as it
      WHERE it.name = 'man_sup_default'
      ";
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function hookHeader() {

    $this->context->controller->registerStylesheet('bxslider_sup', 'js/jquery/plugins/bxslider/jquery.bxslider.css', array('media' => 'all', 'priority' => 900));
    $this->context->controller->registerJavascript('supplierhomepage', 'modules/'.$this->name.'/views/js/main.js', array('position' => 'bottom', 'priority' => 150));
    $this->context->controller->registerJavascript( 'bxslider_sup',  'js/jquery/plugins/bxslider/jquery.bxslider.js', array('position' => 'bottom', 'priority' => 100) );
  }

  public function getContent()
  {
    $errors = '';
    $this->context->controller->addCSS($this->_path.'views/css/style_admin.css');

    $output = '';
    if (Tools::isSubmit('submitSupplierHomePage'))
    {
      $title = (int)(Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_TITLE'));
      $all = (int)(Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_ALL_PAGE'));
      $text_list = (int)(Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT'));
      $text_nb = (Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_NB'));
      $form_list = (int)(Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_FORM'));
      $index_page = (int)(Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_INDEX_PAGE'));
      $left = (int)(Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_LEFT'));
      $center = (int)(Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_CENTER'));


      if (!Validate::isInt($text_nb)){
        $errors = $this->l('Invalid number of elements.');
      }
      else {
        Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_TITLE', $title);
        Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_ALL_PAGE', $all);
        Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT', $text_list);
        Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_NB', $text_nb);
        Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_FORM', $form_list);
        Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_LEFT', $left);
        Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_CENTER', $center);
        Configuration::updateValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_INDEX_PAGE', $index_page);
        $this->_clearCache('blocksupplier.tpl');
        $this->_clearCache('supplier-list.tpl');
      }

      if (isset($errors) AND $errors){
        $output .= $this->displayError($errors);
      }
      else{
        $output .= $this->displayConfirmation($this->l('Settings updated.'));
      }

    }
    return $output.$this->renderForm();
  }

  public function renderForm()
  {
    $fields_form = array(
      'form' => array(
        'legend' => array(
          'title' => $this->l('Settings'),
          'icon' => 'icon-cogs'
        ),
        'input' => array(

          array(
            'type' => 'html',
            'name' => 'html_data',
            'form_group_class'=> 'block_data_settings',
            'html_content' => $this->l('Center column settings'),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Display suppliers in center column'),
            'name' => 'GOMAKOIL_SUPPLIER_DISPLAY_CENTER',
            'values' => array(
              array(
                'id' => 'active_on',
                'value' => 1,
                'label' => $this->l('Enabled')
              ),
              array(
                'id' => 'active_off',
                'value' => 0,
                'label' => $this->l('Disabled')
              )
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Display supplier title'),
            'name' => 'GOMAKOIL_SUPPLIER_DISPLAY_TITLE',
            'desc' => $this->l('Show title supplier after logo.'),
            'values' => array(
              array(
                'id' => 'active_on',
                'value' => 1,
                'label' => $this->l('Enabled')
              ),
              array(
                'id' => 'active_off',
                'value' => 0,
                'label' => $this->l('Disabled')
              )
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Display supplier all page'),
            'name' => 'GOMAKOIL_SUPPLIER_DISPLAY_ALL_PAGE',
            'desc' => $this->l('Show supplier all page.'),
            'values' => array(
              array(
                'id' => 'active_on',
                'value' => 1,
                'label' => $this->l('Enabled')
              ),
              array(
                'id' => 'active_off',
                'value' => 0,
                'label' => $this->l('Disabled')
              )
            ),
          ),

          array(
            'type' => 'html',
            'name' => 'html_data',
            'form_group_class'=> 'block_data_settings',
            'html_content' => $this->l('Left column settings'),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Display suppliers in left column'),
            'name' => 'GOMAKOIL_SUPPLIER_DISPLAY_LEFT',
            'values' => array(
              array(
                'id' => 'active_on',
                'value' => 1,
                'label' => $this->l('Enabled')
              ),
              array(
                'id' => 'active_off',
                'value' => 0,
                'label' => $this->l('Disabled')
              )
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Display suppliers on homepage'),
            'name' => 'GOMAKOIL_SUPPLIER_DISPLAY_TEXT_INDEX_PAGE',
            'values' => array(
              array(
                'id' => 'active_on',
                'value' => 1,
                'label' => $this->l('Enabled')
              ),
              array(
                'id' => 'active_off',
                'value' => 0,
                'label' => $this->l('Disabled')
              )
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Use a plain-text list'),
            'name' => 'GOMAKOIL_SUPPLIER_DISPLAY_TEXT',
            'desc' => $this->l('Display suppliers in a plain-text list.'),
            'values' => array(
              array(
                'id' => 'active_on',
                'value' => 1,
                'label' => $this->l('Enabled')
              ),
              array(
                'id' => 'active_off',
                'value' => 0,
                'label' => $this->l('Disabled')
              )
            ),
          ),
          array(
            'type' => 'text',
            'label' => $this->l('Number of elements to display'),
            'name' => 'GOMAKOIL_SUPPLIER_DISPLAY_TEXT_NB',
            'class' => 'fixed-width-xs'
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Use a drop-down list'),
            'name' => 'GOMAKOIL_SUPPLIER_DISPLAY_FORM',
            'desc' => $this->l('Display suppliers in a drop-down list.'),
            'values' => array(
              array(
                'id' => 'active_on',
                'value' => 1,
                'label' => $this->l('Enabled')
              ),
              array(
                'id' => 'active_off',
                'value' => 0,
                'label' => $this->l('Disabled')
              )
            ),
          )
        ),
        'submit' => array(
          'title' => $this->l('Save'),
        )
      ),
    );

    $helper = new HelperForm();
    $helper->show_toolbar = false;
    $helper->table =  $this->table;
    $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    $helper->default_form_language = $lang->id;
    $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
    $helper->identifier = $this->identifier;
    $helper->submit_action = 'submitSupplierHomePage';
    $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->tpl_vars = array(
      'fields_value' => $this->getConfigFieldsValues(),
      'languages' => $this->context->controller->getLanguages(),
      'id_language' => $this->context->language->id
    );

    return $helper->generateForm(array($fields_form));
  }

  public function getConfigFieldsValues()
  {
    return array(
      'GOMAKOIL_SUPPLIER_DISPLAY_TITLE' => Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_TITLE', Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_TITLE')),
      'GOMAKOIL_SUPPLIER_DISPLAY_ALL_PAGE' => Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_ALL_PAGE', Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_ALL_PAGE')),
      'GOMAKOIL_SUPPLIER_DISPLAY_TEXT' => Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT', Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_TEXT')),
      'GOMAKOIL_SUPPLIER_DISPLAY_TEXT_NB' => Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_NB', Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_NB')),
      'GOMAKOIL_SUPPLIER_DISPLAY_FORM' => Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_FORM', Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_FORM')),
      'GOMAKOIL_SUPPLIER_DISPLAY_TEXT_INDEX_PAGE' => Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_INDEX_PAGE', Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_INDEX_PAGE')),
      'GOMAKOIL_SUPPLIER_DISPLAY_LEFT' => Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_LEFT', Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_LEFT')),
      'GOMAKOIL_SUPPLIER_DISPLAY_CENTER' => Tools::getValue('GOMAKOIL_SUPPLIER_DISPLAY_CENTER', Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_CENTER')),
    );
  }



  public function hookActionObjectSupplierUpdateAfter($params)
  {
    $this->_clearCache('supplier-list.tpl');
  }

  public function hookActionObjectSupplierAddAfter($params)
  {
    $this->_clearCache('supplier-list.tpl');
  }

  public function hookActionObjectSupplierDeleteAfter($params)
  {
    $this->_clearCache('supplier-list.tpl');
  }








  public function renderWidget($hookName = null, array $configuration = array())
  {
    if(!$this->active){
      return false;
    }

    if($hookName == 'displayLeftColumn'){
      $index_page = Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_INDEX_PAGE');
      $left = Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_LEFT');

      if( !$left || (Context::getContext()->controller->php_self == 'index' && !$index_page)){
        return false;
      }
    }
    else{
      $center = Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_CENTER');
      $all_page = Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_ALL_PAGE');

      if (!$center || (!$all_page && Context::getContext()->controller->php_self !== 'index')) {
        return false;
      }

      if (Context::getContext()->controller->php_self !== 'index' && Context::getContext()->controller->php_self !== 'product' && Context::getContext()->controller->php_self !== 'category') {
        return false;
      }
    }

    $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

    if($hookName == 'displayLeftColumn'){
      return $this->display(__FILE__, 'views/templates/hook/blocksupplier.tpl');
    }
    else{
      return $this->display(__FILE__, 'views/templates/hook/supplier-list.tpl');
    }

  }

  public function getWidgetVariables($hookName = null, array $configuration = array())
  {

    $title = Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_TITLE');
    $suppliers = Supplier::getSuppliers(false, $this->_langId);

    foreach ($suppliers as $key => $supplier) {
      $imageType = ImageType::getFormattedName('man_sup');
      if(!$imageType){
        $imageType = ' ';
      }
      $suppliers[$key]['image'] = $this->context->link->getSupplierImageLink($supplier['id_supplier'], $imageType);
      $suppliers[$key]['link'] = $this->context->link->getSupplierLink($supplier['id_supplier'], $supplier['link_rewrite'], $this->_langId);
    }

    return array(
      'id_shop'               => $this->_shopId,
      'id_lang'               => $this->_langId,
      'suppliers'             => $suppliers,
      'title'                 => $title,
      'link'                  => $this->context->link,
      'text_list'             => Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_TEXT'),
      'text_list_nb'          => Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_NB'),
      'form_list'             => Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_FORM'),
      'index_page'            => Configuration::get('GOMAKOIL_SUPPLIER_DISPLAY_TEXT_INDEX_PAGE'),
      'display_link_supplier' => Configuration::get('PS_DISPLAY_SUPPLIERS')
    );
  }

}