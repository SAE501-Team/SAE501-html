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


class mpm_scrolltop extends Module implements WidgetInterface
{
  private $templateFile;

  public function __construct()
  {
    $this->name = 'mpm_scrolltop';
    $this->version = '1.0.0';
    $this->author = 'PrestaShop';
    $this->need_instance = 0;
    $this->tab = 'front_office_features';
    $this->bootstrap = true;
    parent::__construct();

    $this->displayName = $this->l('Scroll top button');
    $this->description = $this->l('Scroll top button.');

    $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

    $this->templateFile = 'module:mpm_scrolltop/views/templates/hook/button.tpl';

  }

  public function install()
  {


    if (!parent::install()
      || !$this->registerHook('displayHeader')
      || !$this->registerHook('displayFooterBefore')
    )
      return false;

    return true;
  }


  public function uninstall()
  {
    if ( !parent::uninstall()
    )
      return false;

    return true;
  }

  public function renderWidget($hookName, array $params = array())
  {
    if(!$this->active){
      return false;
    }
    if($hookName !== 'displayFooterBefore'){
      return false;
    }

    $this->smarty->assign($this->getWidgetVariables($hookName, $params));
    return $this->fetch($this->templateFile);
  }

  public function getWidgetVariables($hookName, array $params = array())
  {
    return array(
      'active' => 1,
    );
  }


  public function hookDisplayHeader($params)
  {
    $this->context->controller->registerJavascript('mpm_scrolltop', 'modules/'.$this->name.'/views/js/scrolltop.js', array('media' => 'all', 'position' => 'bottom', 'priority' => 150));
  }


}
