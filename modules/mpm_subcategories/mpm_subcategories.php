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
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;

class Mpm_subcategories extends Module implements WidgetInterface
{
  private $templateFile;

  public function __construct()
  {
    $this->name = 'mpm_subcategories';
    $this->version = '1.0.0';
    $this->author = 'PrestaShop';
    $this->need_instance = 0;
    $this->tab = 'front_office_features';
    $this->bootstrap = true;
    parent::__construct();

    $this->displayName = $this->l('Subcategories block');
    $this->description = $this->l('Displays a subcategories on category page.');

    $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

    $this->templateFile = 'module:mpm_subcategories/views/templates/hook/subcategories.tpl';

  }

  public function install()
  {
    return (parent::install() &&
      $this->registerHook('displayHeader') );
  }


  public function uninstall()
  {

    return parent::uninstall();
  }


  public function hookDisplayHeader($params)
  {
    if($this->context->controller->php_self == 'category'){
      $this->context->controller->registerJavascript('mpm_subcategories', 'modules/'.$this->name.'/views/js/mpm_subcategories.js', array('position' => 'bottom', 'priority' => 150));
    }
  }

  public function renderWidget($hookName, array $params = array())
  {
    if(!$this->active){
      return false;
    }
    if($this->context->controller->php_self != 'category' || !$params['id_category']){
      return false;
    }

    $this->smarty->assign($this->getWidgetVariables($hookName, $params));
    return $this->fetch($this->templateFile);
  }

  public function getWidgetVariables($hookName, array $params = array())
  {

    $id_category = $params['id_category'];
    $imagesTypes = ImageType::getFormattedName('category');
    $obj = new Category($id_category, Context::getContext()->language->id);
    $categories = $obj->getSubCategories(Context::getContext()->language->id);
    $obj_img = new ImageRetriever( Context::getContext()->link);

    if(!$categories){
      return false;
    }

    foreach ($categories as $key => $category){
      $cat = new Category($category['id_category'], Context::getContext()->language->id);
      $categories[$key]['images'] = $obj_img->getImage($cat, $cat->id_image);
      $categories[$key]['link'] = Context::getContext()->link->getCategoryLink($category['id_category'],$category['link_rewrite'],Context::getContext()->language->id );
    }

    return array(
      'categories' => $categories,
    );
  }




}
