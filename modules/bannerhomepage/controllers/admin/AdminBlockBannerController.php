<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/bannerBlock.php');

class AdminBlockBannerController extends ModuleAdminController
{
  private $_imgDir;
  private $_images;
  private $_idShop;
  private $_idLang;
//  private $_homeSlider;
  protected $position_identifier = 'id_banner';

  public function __construct()
  {
    $this->className = 'bannerBlock';
    $this->table = 'banner';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_banner';
    $this->_defaultOrderBy = 'a!position';
    $this->orderBy = 'position';
    $this->_imgDir = _PS_MODULE_DIR_ . 'bannerhomepage/views/img/banner/';
    $this->_images = _MODULE_DIR_ . 'bannerhomepage/views/img/banner/';
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
//    $this->_homeSlider = new blockHomeSlide();

    $this->bulk_actions = array(
      'delete' => array(
        'text' => $this->l('Delete selected'),
        'icon' => 'icon-trash',
        'confirm' => $this->l('Delete selected items?')
      )
    );

    $this->fields_list = array(
      'id_banner' => array(
        'title' => $this->l('ID'),
        'search' => true,
        'onclick' => false,
        'filter_key' => 'a!id_banner',
        'width' => 20
      ),
     'image' => array(
        'title' => $this->l('Image'),
        'width' => 20,
        'orderby' => false,
        'filter' => false,
        'search' => false,
        'align' => 'left',
        'callback' => 'getSliderImage',
      ),
      'title' => array(
        'title' => $this->l('Title'),
        'filter_key' => 'b!title',
        'search' => true,
        'width' =>100,
        'align' => 'left',
      ),
      'active' => array(
        'title' => $this->l('Displayed'),
        'search' => true,
        'active' => 'status',
        'type' => 'bool',
        'width' => 20,
      ),
      'position' => array(
        'title' => $this->l('Position'),
        'width' => 40,
        'search' => false,
        'filter_key' => 'a!position',
        'align' => 'left',
        'position' => 'position'
      ),
      'date_add' => array(
        'title' => $this->l('Date add'),
        'maxlength' => 190,
        'width' =>100,
        'align' => 'left',
      )
    );
  }

  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewbanner') === false)
      $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
  }

  public function initProcess(){
    parent::initProcess();
  }

  public function initContent()
  {
    $settings = Tools::unserialize(Configuration::get('GOMAKOIL_BLOCK_BANNER'));
    $form = $this->getFormGeneralSettings($settings);
    $this->tpl_list_vars['form'] = $form;
    $this->tpl_list_vars['token_admin'] = Tools::getAdminTokenLite('AdminBlockBanner');
    parent::initContent();
  }

  public function renderList()
  {
    $this->addRowAction('edit');
    $this->addRowAction('delete');
    return parent::renderList();
  }

  public function postProcess()
  {
    return parent::postProcess();
  }

  public function getFormGeneralSettings($settings){

    $this->fields_form = array(
      'tinymce' => true,
      'legend' => array(
        'title' => $this->l('Settings'),
        'icon' => 'icon-cogs'
      ),
      'input' => array(
        array(
          'type' => 'switch',
          'label' => $this->l('Show description'),
          'name' => 'show_description',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'active_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'active_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Show banner on all pages'),
          'name' => 'show_on_all_pages',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'active_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'active_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'hidden',
          'name' => 'idLang',
        ),
        array(
          'type' => 'hidden',
          'name' => 'idShop',
        ),
        array(
          'type' => 'hidden',
          'name' => 'token_banner',
        ),
      ),
      'buttons' => array(
        'save' => array(
          'title' => $this->l('Save'),
          'name' => 'submitSaveGeneralSettingsBanner',
          'type' => 'submit',
          'class' => 'btn btn-default pull-right submitSaveGeneralSettingsBanner',
          'icon' => 'process-icon-save'
        ),
      ),
    );

    if(isset($settings) && $settings){
      foreach($settings as $key => $val){
        $this->fields_value[$key] = $val;
      }
    }

    $this->fields_value['idLang'] = $this->_idLang;
    $this->fields_value['idShop'] = $this->_idShop;
    $this->fields_value['token_banner'] = Tools::getAdminTokenLite('AdminBlockBanner');
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');

    return parent::renderForm();
  }

  public function renderForm()
  {
    $this->fields_form = array(
      'tinymce' => true,
      'legend' => array(
        'title' => $this->l('Banner'),
        'icon' => 'icon-list-ul'
      ),
      'input' => array(
        array(
          'type' => 'switch',
          'label' => $this->l('Active'),
          'name' => 'active',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'active_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'active_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'file_lang',
          'label' => $this->l('Select a file'),
          'name' => 'image',
          'form_group_class' => 'form_group_img',
          'required' => true,
          'lang' => true,
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Title'),
          'name' => 'title',
          'lang' => true,
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Target URL'),
          'name' => 'url',
          'lang' => true,
        ),
        array(
          'type' => 'textarea',
          'label' => $this->l('Description'),
          'name' => 'description',
          'lang' => true,
          'autoload_rte' => true,
          'rows' => 10,
          'cols' => 100,
          'hint' => $this->l('Invalid characters:').' <>;=#{}'
        ),
      ),
      'submit' => array(
        'title' => $this->l('Save'),
      ),
      'buttons' => array(
        'save-and-stay' => array(
          'title' => $this->l('Save and stay'),
          'name' => 'submitAdd'.$this->table.'AndStay',
          'type' => 'submit',
          'class' => 'btn btn-default pull-right',
          'icon' => 'process-icon-save'
        ),
      ),
    );

    $fields = array();
    $obj = $this->loadObject(true);
    $languages = Language::getLanguages(false);

    foreach ($languages as $lang)
    {
      $image = $this->_imgDir.$obj->id.'_'.$lang['id_lang'].'.'.$this->imageType;

      $image_size = file_exists($image) ? filesize($image) / 1000 : false;

      if($image_size){
        $image_url = $this->_images.$obj->id.'_'.$lang['id_lang'].'.'.$this->imageType;
      }
      else{
        $image_url = false;
      }

      $fields[$lang['id_lang']] = array(
        'url'  => $image_url,
        'size' => $image_size,
      );
    }

    $this->tpl_form_vars['lang_def'] = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    $this->tpl_form_vars['images'] = $fields;
    $this->tpl_form_vars['save_error'] = !empty($this->errors);
    $this->tpl_form_vars['idLang'] = $this->_idLang;
    $this->tpl_form_vars['idShop'] = $this->_idShop;
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');

    return parent::renderForm();
  }

  public function displayAjax()
  {
    $json = array();
    try{
      if (Tools::getValue('action') == 'saveGeneralSettings'){
        $fields = array(
          'show_description'         => Tools::getValue('show_description'),
          'show_on_all_pages'        => Tools::getValue('show_on_all_pages'),
        );
        $base = serialize($fields);
        Configuration::updateValue('GOMAKOIL_BLOCK_BANNER', $base);
        $json['success'] = Module::getInstanceByName('bannerhomepage')->l("Successfully saved!") ;
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

  protected function postImage($id)
  {
    $width = 330;
    $height = 156;
    $res = $this->uploadImage($id, 'image', $this->_imgDir, 'jpg', $width, $height);
    return $res;
  }

  protected function uploadImage($id, $name, $dir, $ext = false, $width = null, $height = null)
  {
    $errors = array();
    $item = new bannerBlock($id);

    /* Sets each langue fields */
    $languages = Language::getLanguages(false);
    $lang_def = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    $image = $this->_imgDir.$id.'_'.($lang_def->id).'.'.$this->imageType;
    $image_size = file_exists($image) ? filesize($image) / 1000 : false;

    foreach ($languages as $language)
    {

      $image_size = file_exists($image) ? filesize($image) / 1000 : false;
      if(!$_FILES[$name.'_'.$language['id_lang']]['size'] && !$image_size){
        $this->errors[] = Tools::displayError('An error occurred while uploading image.');
        return false;
      }

      if(!$_FILES[$name.'_'.$language['id_lang']]['tmp_name'] && $image_size){
        $im = $this->_imgDir.$id.'_'.$language['id_lang'].'.'.$this->imageType;
        $im_size = file_exists($im) ? filesize($im) / 1000 : false;
        if(!$im_size){
          $item->image[$language['id_lang']] = $id.'_'.$lang_def->id.'.'.$this->imageType;
          $item->save();
        }
      }

      if($_FILES[$name.'_'.$language['id_lang']]['tmp_name']){
        /* Uploads image and sets slide */
        $type = Tools::strtolower(Tools::substr(strrchr($_FILES[$name.'_'.$language['id_lang']]['name'], '.'), 1));
        $imagesize = @getimagesize($_FILES[$name.'_'.$language['id_lang']]['tmp_name']);
        if (isset($_FILES[$name.'_'.$language['id_lang']]) &&
          isset($_FILES[$name.'_'.$language['id_lang']]['tmp_name']) &&
          !empty($_FILES[$name.'_'.$language['id_lang']]['tmp_name']) &&
          !empty($imagesize) &&
          in_array(
            Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)), array(
              'jpg',
              'gif',
              'jpeg',
              'png'
            )
          ) &&
          in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
        )
        {
          $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
          if ($error = ImageManager::validateUpload($_FILES[$name.'_'.$language['id_lang']])){
            $errors[] = $error;
          }
          elseif (!$temp_name || !move_uploaded_file($_FILES[$name.'_'.$language['id_lang']]['tmp_name'], $temp_name)){
            return false;
          }
          elseif (!ImageManager::resize($temp_name, $dir.$id.'_'.$language['id_lang'].'.'.$this->imageType, $width, $height, $type))
          {
          }
          if (isset($temp_name)){
            @unlink($temp_name);
          }
          $item->image[$language['id_lang']] = $id.'_'.$language['id_lang'].'.'.$this->imageType;
          $item->save();
        }
      }

    }
    return true;
  }

  public function ajaxProcessUpdatePositions()
  {
    $banner = Tools::getValue('banner');
    foreach($banner as $key => $value){
      $value = explode('_', $value);
      Db::getInstance()->update('banner', array('position' => (int)$key), 'id_banner='.(int)$value[2]);
    }
  }

  public function getSliderImage($image){
    $image_s = $this->_imgDir.$image;
    $image_size = file_exists($image_s) ? filesize($image_s) / 1000 : false;

    if($image && $image_size){
      $image_url = '<img src="'.$this->_images.$image.'" class="img-thumbnail" >';
    }
    else{
      $image_url = '<img src="'.$this->_images.'default.jpg" class="img-thumbnail" >';
    }

    return $image_url;
  }

}