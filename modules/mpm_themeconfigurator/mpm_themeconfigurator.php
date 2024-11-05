<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
  exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;


class mpm_themeconfigurator extends Module implements WidgetInterface
{
  private $templateFile;

  public function __construct()
  {
    $this->name = 'mpm_themeconfigurator';
    $this->version = '1.0.0';
    $this->author = 'PrestaShop';
    $this->need_instance = 0;
    $this->tab = 'front_office_features';
    $this->bootstrap = true;
    parent::__construct();

    $this->displayName = $this->l('Theme configurator');
    $this->description = $this->l('The customization tool allows you to make color and font changes in your theme.');

    $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

    $this->templateFile = 'module:mpm_themeconfigurator/views/templates/hook/configurator.tpl';

  }

  public function install()
  {


    if (!parent::install()
      || !Configuration::updateValue('CONFIGURATOR_ACTIVE', 0)
      || !Configuration::updateValue('FONTS_CONFIGURATOR_ACTIVE', '')
      || !Configuration::updateValue('THEME_BACKGROUND_COLOR', '#7bae23')
      || !Configuration::updateValue('THEME_BACKGROUND_COLOR_HOVER', '#629112')
      || !Configuration::updateValue('THEME_BACKGROUND_MIN_HEADER', '#eeedec')
      || !Configuration::updateValue('THEME_BACKGROUND_PAGE', '#f9f9f9')
      || !Configuration::updateValue('THEME_BACKGROUND_FOOTER', '#4d4d4d')
      || !Configuration::updateValue('THEME_BACKGROUND_BEFORE_FOOTER', '#3f3f3f')

      || !$this->registerHook('displayHeader')
    )
      return false;

    return true;
  }


  public function uninstall()
  {
    if ( !parent::uninstall()
      || !Configuration::deleteByName('CONFIGURATOR_ACTIVE')
      || !Configuration::deleteByName('FONTS_CONFIGURATOR_ACTIVE')
      || !Configuration::deleteByName('THEME_BACKGROUND_COLOR')
      || !Configuration::deleteByName('THEME_BACKGROUND_COLOR_HOVER')
      || !Configuration::deleteByName('THEME_BACKGROUND_MIN_HEADER')
      || !Configuration::deleteByName('THEME_BACKGROUND_PAGE')
      || !Configuration::deleteByName('THEME_BACKGROUND_FOOTER')
      || !Configuration::deleteByName('THEME_BACKGROUND_BEFORE_FOOTER')
    )
      return false;

    return true;
  }

  public function getContent()
  {
       $this->context->controller->addCss($this->_path.'views/css/mpm_themeconfigurator_admin.css', 'all');
    return $this->postProcess().$this->renderForm();
  }
  public function renderForm()
  {

    $fonts = array(

      array(
        'name'  => 'Choose a font',
        'id'  => 'font',
      ),
      array(
        'name'  => 'Open Sans',
        'id'  => 'font1',
      ),
      array(
        'name'  => 'Josefin Slab',
        'id'  => 'font2',
      ),
      array(
        'name'  => 'Arvo',
        'id'  => 'font3',
      ),
      array(
        'name'  => 'Lato',
        'id'  => 'font4',
      ),
      array(
        'name'  => 'Volkorn',
        'id'  => 'font5',
      ),
      array(
        'name'  => 'Abril Fatface',
        'id'  => 'font6',
      ),
      array(
        'name'  => 'Ubuntu',
        'id'  => 'font7',
      ),
      array(
        'name'  => 'PT Sans',
        'id'  => 'font8',
      ),
      array(
        'name'  => 'Old Standard TT',
        'id'  => 'font9',
      ),
      array(
        'name'  => 'Droid Sans',
        'id'  => 'font10',
      ),
      array(
        'name'  => 'PT Sans Narrow',
        'id'  => 'font11',
      ),
      array(
        'name'  => 'Arial',
        'id'  => 'font12',
      ),
    );

    $fields_form = array(
      'form' => array(
        'legend' => array(
          'title' =>$this->l('Settings'),
          'icon' => 'icon-cogs'
        ),
        'input' => array(
          array(
            'type' => 'switch',
            'label' => $this->l('Display Live Configurator'),
            'name' => 'CONFIGURATOR_ACTIVE',
            'required' => false,
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
            )
          ),
          array(
            'type' => 'html',
            'label' => $this->l('Theme color'),
            'name' => 'html_data',
            'form_group_class'=> 'html_data_settings default_settings',
            'html_content' => '',
          ),
          array(
            'type' => 'color',
            'label' => 'Main background color',
            'name' => 'THEME_BACKGROUND_COLOR'
          ),
          array(
            'type' => 'color',
            'label' => 'Main background color on hover',
            'name' => 'THEME_BACKGROUND_COLOR_HOVER'
          ),
          array(
            'type' => 'color',
            'label' => 'Scroll header background',
            'name' => 'THEME_BACKGROUND_MIN_HEADER'
          ),
          array(
            'type' => 'color',
            'label' => 'Background page',
            'name' => 'THEME_BACKGROUND_PAGE'
          ),
          array(
            'type' => 'color',
            'label' => 'Footer background',
            'name' => 'THEME_BACKGROUND_FOOTER'
          ),
          array(
            'type' => 'color',
            'label' => 'Background before footer',
            'name' => 'THEME_BACKGROUND_BEFORE_FOOTER'
          ),
          array(
            'type' => 'html',
            'label' => $this->l('Theme font'),
            'name' => 'html_data',
            'form_group_class'=> 'html_data_settings default_settings',
            'html_content' => '',
          ),
          array(
            'type' => 'select',
            'label' => $this->l('Font'),
            'name' => 'FONTS_CONFIGURATOR_ACTIVE',
            'options' => array(
              'query' => $fonts,
              'name' => 'name',
              'id' => 'id'
            ),
              ),
        ),
        'submit' => array(
          'title' => $this->l('Save')
        )
      ),
    );

    $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

    $helper = new HelperForm();
    $helper->show_toolbar = false;
    $helper->table = $this->table;
    $helper->default_form_language = $lang->id;
    $helper->module = $this;
    $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
    $helper->identifier = $this->identifier;
    $helper->submit_action = 'submitConfigurator';
    $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->tpl_vars = array(
      'uri' => $this->getPathUri(),
      'fields_value' => $this->getConfigFieldsValues(),
      'languages' => $this->context->controller->getLanguages(),
      'id_language' => $this->context->language->id
    );

    return $helper->generateForm(array($fields_form));
  }

  public function postProcess()
  {
    if (Tools::isSubmit('submitConfigurator')) {

      Configuration::updateValue('CONFIGURATOR_ACTIVE', Tools::getValue('CONFIGURATOR_ACTIVE'));
      Configuration::updateValue('FONTS_CONFIGURATOR_ACTIVE', Tools::getValue('FONTS_CONFIGURATOR_ACTIVE'));
      Configuration::updateValue('THEME_BACKGROUND_COLOR', Tools::getValue('THEME_BACKGROUND_COLOR'));
      Configuration::updateValue('THEME_BACKGROUND_COLOR_HOVER', Tools::getValue('THEME_BACKGROUND_COLOR_HOVER'));
      Configuration::updateValue('THEME_BACKGROUND_MIN_HEADER', Tools::getValue('THEME_BACKGROUND_MIN_HEADER'));
      Configuration::updateValue('THEME_BACKGROUND_PAGE', Tools::getValue('THEME_BACKGROUND_PAGE'));
      Configuration::updateValue('THEME_BACKGROUND_FOOTER', Tools::getValue('THEME_BACKGROUND_FOOTER'));
      Configuration::updateValue('THEME_BACKGROUND_BEFORE_FOOTER', Tools::getValue('THEME_BACKGROUND_BEFORE_FOOTER'));


      return $this->displayConfirmation($this->l('The settings have been updated.'));
    }

    return '';
  }

  public function getConfigFieldsValues()
  {
    return array(
      'FONTS_CONFIGURATOR_ACTIVE' => Tools::getValue('FONTS_CONFIGURATOR_ACTIVE', Configuration::get('FONTS_CONFIGURATOR_ACTIVE')),
      'CONFIGURATOR_ACTIVE' => Tools::getValue('CONFIGURATOR_ACTIVE', Configuration::get('CONFIGURATOR_ACTIVE')),
      'THEME_BACKGROUND_COLOR' => Tools::getValue('THEME_BACKGROUND_COLOR', Configuration::get('THEME_BACKGROUND_COLOR')),
      'THEME_BACKGROUND_COLOR_HOVER' => Tools::getValue('THEME_BACKGROUND_COLOR_HOVER', Configuration::get('THEME_BACKGROUND_COLOR_HOVER')),
      'THEME_BACKGROUND_MIN_HEADER' => Tools::getValue('THEME_BACKGROUND_MIN_HEADER', Configuration::get('THEME_BACKGROUND_MIN_HEADER')),
      'THEME_BACKGROUND_PAGE' => Tools::getValue('THEME_BACKGROUND_PAGE', Configuration::get('THEME_BACKGROUND_PAGE')),
      'THEME_BACKGROUND_FOOTER' => Tools::getValue('THEME_BACKGROUND_FOOTER', Configuration::get('THEME_BACKGROUND_FOOTER')),
      'THEME_BACKGROUND_BEFORE_FOOTER' => Tools::getValue('THEME_BACKGROUND_BEFORE_FOOTER', Configuration::get('THEME_BACKGROUND_BEFORE_FOOTER')),

    );
  }



  public function renderWidget($hookName, array $params = array())
  {
    if(!$this->active){
      return false;
    }
  }

  public function getWidgetVariables($hookName, array $params = array())
  {
    return array(
      'active' => 1,
    );
  }


  public function hookDisplayHeader($params)
  {

    if ((int)Configuration::get('CONFIGURATOR_ACTIVE') == 1)
    {


      if (Configuration::get('FONTS_CONFIGURATOR_ACTIVE') != ''){
        $this->context->controller->registerStylesheet('mpm_themeconfigurator_font_active', 'modules/'.$this->name.'/views/css/'.Configuration::get('FONTS_CONFIGURATOR_ACTIVE').'.css', array('media' => 'all', 'priority' => 900));
      }

      if (Configuration::get('THEME_BACKGROUND_COLOR') != '' && Configuration::get('THEME_BACKGROUND_COLOR_HOVER') != '' ){
        $this->smarty->assign(
          array(
            'background' => Configuration::get('THEME_BACKGROUND_COLOR'),
            'background_hover' => Configuration::get('THEME_BACKGROUND_COLOR_HOVER'),
            'header_min' => Configuration::get('THEME_BACKGROUND_MIN_HEADER'),
            'page' => Configuration::get('THEME_BACKGROUND_PAGE'),
            'footer' => Configuration::get('THEME_BACKGROUND_FOOTER'),
            'before_footer' => Configuration::get('THEME_BACKGROUND_BEFORE_FOOTER'),

          ));
        return $this->display(__FILE__, 'configurator.tpl');
      }
    }
  }


}
