<?php

class leftMenuItem extends ObjectModel
{
  public $id;
  public $id_category;
  public $max_depth;
  public $font_size = 15;
  public $right_section;
  public $bottom_section;
  public $right_section_width = 0;

  public $right_section_val;
  public $bottom_section_val;
  public $title_right_section;
  public $title_bottom_section;
  public $width = 0;

  public static $definition = array(
    'table' => 'left_menu',
    'primary' => 'id_left_menu',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(
      //basic fields

      'right_section' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'bottom_section' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'right_section_val' => 	array('type' => self::TYPE_HTML,  'validate' => 'isString'),
      'bottom_section_val' => 	array('type' => self::TYPE_HTML,  'validate' => 'isString'),
      'id_category'  => 		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'font_size' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'width' => 	array('type' => self::TYPE_INT, 'required' => true,  'validate' => 'isunsignedInt'),
      'right_section_width' => 	array('type' => self::TYPE_INT, 'required' => true,  'validate' => 'isunsignedInt'),

      // Lang fields
      'title_right_section' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 255),
      'title_bottom_section' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 255),

    )
  );

  public function __construct($id_left_menu = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_left_menu, $id_lang, $id_shop);
    Shop::addTableAssociation('left_menu_lang', array('type' => 'fk_shop'));
  }
  public function add($autodate = true, $null_values = false)
  {
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockMenu.tpl', 'leftmenu_');
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockMenu.tpl', 'leftmenu_product_');
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockCategoriesFooter.tpl', 'leftmenu_footer_');
    return parent::add($autodate, $null_values);
  }

  public function update($null_values = false)
  {
    $id_category = Tools::getValue('id_category');
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockMenu.tpl', 'leftmenu_');
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockMenu.tpl', 'leftmenu_product_');
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockCategoriesFooter.tpl', 'leftmenu_footer_');

    if(!$id_category){
      $id_left_menu = Tools::getValue('id_left_menu');
      $obj = new leftMenuItem($id_left_menu);
      $this->id_category = $obj->id_category;
    }

    $res = parent::update($null_values);
    return $res;
  }

  public function delete()
  {
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockMenu.tpl', 'leftmenu_');
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockMenu.tpl', 'leftmenu_product_');
    $this->clearSmartyCache(_PS_MODULE_DIR_.'leftmenu/views/templates/hook/blockCategoriesFooter.tpl', 'leftmenu_footer_');
    $this->deleteImage();
    $res = parent::delete();

    return $res;
  }

  public function clearSmartyCache($tpl, $id)
  {
    $languages = Language::getLanguages(false);

    foreach ($languages as $language)
    {
      Tools::enableCache();
      Tools::clearCache(null, $tpl, $id.Context::getContext()->controller->php_self.'_'.$language['id_lang']);
      Tools::restoreCacheSettings();
    }
  }

}