<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.09.15
 * Time: 20:33
 */
require_once(dirname(__FILE__) . '/../../classes/blockFeatured.php');

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;



class blockhomefeaturedAjaxFormModuleFrontController extends FrontController
{

  public function initContent()
  {

    if (!$this->ajax) {
      parent::initContent();
    }
  }

  public function displayAjax()
  {

    $json = array();
    try{

      if (Tools::getValue('action') == 'showContent'){
        $id = Tools::getValue('id');
        $id_shop = Tools::getValue('id_shop');
        $id_lang = Tools::getValue('id_lang');
        $obj = new blockFeatured($id);
        $content = $this->getContent($obj, $id_lang, $id_shop, false);
        $json['content'] = $content;
      }

      if (Tools::getValue('action') == 'productsCategory'){
        $id = Tools::getValue('id');
        $id_category = Tools::getValue('id_category');
        $id_shop = Tools::getValue('id_shop');
        $id_lang = Tools::getValue('id_lang');
        $obj = new blockFeatured($id);
        $content = $this->getContent($obj, $id_lang, $id_shop, $id_category);
        $json['content'] = $content;
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



  public function getContent($obj, $id_lang, $id_shop, $id_category){



    if($obj->type == 'all'){
      $ids = $this->getIdsAllProducts();

    }

    if($obj->type == 'category'){
      if($obj->ids_categories){
        $ids = $this->getIdsProductsInCategory($obj->ids_categories);
      }
      else{
        $ids = false;
      }

    }

    if($obj->type == 'products'){
      $ids = $obj->ids_products;
    }

    if($obj->type == 'last_visited'){
      $ids = (isset(Context::getContext()->cookie->viewed) && !empty(Context::getContext()->cookie->viewed)) ? array_slice(array_reverse(explode(',', Context::getContext()->cookie->viewed)), 0, 40) : array();
      $ids = implode(',', $ids);

    }

    if($obj->type == 'discount'){
      $ids = $this->getIdsProductsDiscount();
    }

    if($obj->type == 'selling'){
      $ids = $this->getIdsProductsSale();
    }

    if($obj->type == 'new'){
      $ids = $this->getIdsNewProducts();

    }



    if(!$ids){
      return '<div class="homepage_tab_content_no_products"><div class="featured_overlay_loading"></div> <div class="featured_overlay"></div>'.Module::getInstanceByName('blockhomefeatured')->l("There are no products in this category.").'</div>';
    }

    if(!$id_category){
      $categories = $this->getCategoriesFeatured($ids, $id_lang, $id_shop);
      $products = $this->getProductsByIds($id_lang, $id_shop, $ids, false);
    }
    else{
      $categories = false;
      $products = $this->getProductsByIds($id_lang, $id_shop, $ids, $id_category);
    }

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

    return $this->getTplFeatured($categories, $array_result, $obj->type);
  }


  public function getTplFeatured($categories, $products, $type){


    if($categories){

      foreach ($categories as $key => $category){
        $imagesTypes = ImageType::getFormattedName('category_home');

        $categories[$key]['images'] = Context::getContext()->link->getCatImageLink(
          Tools::strtolower($category['name']),
          $category['id_category'],
          $imagesTypes = (isset($imagesTypes) ? $imagesTypes : null)
        );
      }


      $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'blockhomefeatured/views/templates/hook/tplFeatured.tpl');
    }
    else{
      $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'blockhomefeatured/views/templates/hook/featured-list.tpl');
    }

    $url = str_replace('http://', Tools::getShopProtocol(), _PS_BASE_URL_SSL_.__PS_BASE_URI__.'img/c/');
    $quick_view = Configuration::get('PS_QUICK_VIEW');

    $data->assign(
      array(
        'categories'    => $categories,
        'products'      => $products,
        'tpl_path'      => _PS_MODULE_DIR_.'blockhomefeatured/views/templates/hook/featured-list.tpl',
        'link'          => new Link(),
        'type'          => $type,
        'quick_view'    => $quick_view,
        'urls' => $this->getTemplateVarUrls(),
        'link_img'      => $url,
      )
    );
    return $data->fetch();
  }


  public function getIdsAllProducts(){

    $sql = '
        SELECT GROUP_CONCAT(x.id_product) as id_product FROM (
            SELECT DISTINCT p.id_product as id_product
            FROM ' . _DB_PREFIX_ . 'product as p
            WHERE p.active = 1
            LIMIT 20
        ) as x
        ';
    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

    if(isset($res[0]['id_product']) && $res[0]['id_product']){
      return $res[0]['id_product'];
    }
    else{
      return false;
    }
  }


  public function getIdsProductsInCategory($ids){

    $sql = '
        SELECT GROUP_CONCAT(x.id_product) as id_product FROM (
            SELECT DISTINCT cp.id_product as id_product
            FROM ' . _DB_PREFIX_ . 'category_product as cp
            INNER JOIN ' . _DB_PREFIX_ . 'product as p
            ON p.id_product = cp.id_product
            WHERE p.active = 1
            AND cp.id_category IN('.pSQL($ids).')
            LIMIT 20
        ) as x';

    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

    if(isset($res[0]['id_product']) && $res[0]['id_product']){
      return $res[0]['id_product'];
    }
    else{
      return false;
    }
  }



  public function getIdsProductsDiscount(){

    $sql = '
        SELECT GROUP_CONCAT(x.id_product) as id_product FROM (
            SELECT DISTINCT p.id_product as id_product
            FROM ' . _DB_PREFIX_ . 'product as p
            LEFT JOIN ' . _DB_PREFIX_ . 'specific_price as sp
            ON p.id_product = sp.id_product
            WHERE p.active = 1
            AND sp.id_specific_price IS NOT NULL
            LIMIT 20
        ) as x';

    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

    if(isset($res[0]['id_product']) && $res[0]['id_product']){
      return $res[0]['id_product'];
    }
    else{
      return false;
    }
  }

  public function getIdsProductsSale(){
    $sql = '
        SELECT GROUP_CONCAT(x.id_product) as id_product FROM (
            SELECT DISTINCT p.id_product as id_product
            FROM ' . _DB_PREFIX_ . 'product_sale as ps
            INNER JOIN ' . _DB_PREFIX_ . 'product as p
            ON p.id_product = ps.id_product
            WHERE p.active = 1
            LIMIT 20
        ) as x';

    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

    if(isset($res[0]['id_product']) && $res[0]['id_product']){
      return $res[0]['id_product'];
    }
    else{
      return false;
    }
  }

  public function getIdsNewProducts(){
    $sql = '
        SELECT GROUP_CONCAT(x.id_product) as id_product FROM (
            SELECT  DISTINCT p.id_product as id_product
            FROM ' . _DB_PREFIX_ . 'product as p
            WHERE p.active = 1
            ORDER BY p.date_add DESC
            LIMIT 20
        ) as x
        ';

    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    if(isset($res[0]['id_product']) && $res[0]['id_product']){
      return $res[0]['id_product'];
    }
    else{
      return false;
    }
  }

  public function getCategoriesFeatured($ids, $id_lang, $id_shop){
    $sql = '
           SELECT cp.id_category, cl.name, cl.link_rewrite, count(cp.id_category) as count_products
          FROM ' . _DB_PREFIX_ . 'category_product as cp
          INNER JOIN ' . _DB_PREFIX_ . 'category as c
          ON c.id_category = cp.id_category
          LEFT JOIN ' . _DB_PREFIX_ . 'category_lang as cl
          ON c.id_category = cl.id_category
          WHERE c.active = 1
          AND cl.id_shop = '.(int)$id_shop.'
          AND cl.id_lang = '.(int)$id_lang.'
          AND cp.id_product IN('.pSQL($ids).')
          AND cp.id_category != 2
          GROUP BY cp.id_category
        ';
      return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }



  public function getProductsByIds($id_lang, $id_shop, $ids, $id_category){

    $where = '';

    if($id_category){
      $where = ' AND cp.id_category = '.(int)$id_category;
    }

    $sql = '
			SELECT pl.name, p.*, i.id_image, pl.link_rewrite, p.reference
      FROM ' . _DB_PREFIX_ . 'product_lang as pl
      LEFT JOIN ' . _DB_PREFIX_ . 'image as i
      ON i.id_product = pl.id_product AND i.cover=1
      INNER JOIN ' . _DB_PREFIX_ . 'product as p
      ON p.id_product = pl.id_product
      LEFT JOIN ' . _DB_PREFIX_ . 'category_product as cp
      ON p.id_product = cp.id_product
      WHERE pl.id_lang = ' . (int)$id_lang . '
      AND pl.id_shop = ' . (int)$id_shop . '
      AND p.id_product IN ('.pSQL($ids).')
      '.$where.'
      GROUP BY p.id_product
      ORDER BY FIELD(p.id_product, '.pSQL($ids).')
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

}