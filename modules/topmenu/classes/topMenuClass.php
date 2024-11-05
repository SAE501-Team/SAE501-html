<?php

class topMenuClass extends ObjectModel
{
  public $id;
  public $active = 1;
  public $position;
  public $narrow;
  public $link;
  public $background;

  public $left_selection;
  public $main_selection;
  public $right_selection;
  public $botton_selection;

  public $left_selection_val;
  public $main_selection_val;
  public $right_selection_val;
  public $botton_selection_val;

  public $title;
  public $content = 0;
  public $custom = 0;
  public $width = 200;
  public $height = 200;
  public $left_width;
  public $main_width;
  public $right_width;
  public $botton_width;
  public $left_height;
  public $main_height;
  public $right_height;
  public $botton_height;
  public $id_image ;

  public static $definition = array(
    'table' => 'top_menu',
    'primary' => 'id_top_menu',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(
      //basic fields
      'active'  => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'narrow'  => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),


      'left_width' => 	array('type' => self::TYPE_INT,   'validate' => 'isunsignedInt'),
      'main_width' => 	array('type' => self::TYPE_INT,   'validate' => 'isunsignedInt'),
      'right_width' => 	array('type' => self::TYPE_INT,   'validate' => 'isunsignedInt'),
      'botton_width' => 	array('type' => self::TYPE_INT,   'validate' => 'isunsignedInt'),
      'left_height' => 	array('type' => self::TYPE_INT,   'validate' => 'isunsignedInt'),
      'main_height' => 	array('type' => self::TYPE_INT,   'validate' => 'isunsignedInt'),
      'right_height' => 	array('type' => self::TYPE_INT,   'validate' => 'isunsignedInt'),
      'botton_height' => 	array('type' => self::TYPE_INT,  'validate' => 'isunsignedInt'),

      'content'  => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'custom'  => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),

      'position'  => 		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'link' => 	array('type' => self::TYPE_HTML,  'validate' => 'isString'),
      'background' => 	array('type' => self::TYPE_HTML,  'validate' => 'isString'),

      'left_selection' => 	array('type' => self::TYPE_INT,   'validate' => 'isunsignedInt'),
      'main_selection' => 	array('type' => self::TYPE_INT,   'validate' => 'isunsignedInt'),
      'right_selection' => 	array('type' => self::TYPE_INT,   'validate' => 'isunsignedInt'),
      'botton_selection' => 	array('type' => self::TYPE_INT,   'validate' => 'isunsignedInt'),
      'left_selection_val' => 	array('type' => self::TYPE_HTML,  'validate' => 'isString'),
      'main_selection_val' => 	array('type' => self::TYPE_HTML,  'validate' => 'isString'),
      'right_selection_val' => 	array('type' => self::TYPE_HTML,  'validate' => 'isString'),
      'botton_selection_val' => 	array('type' => self::TYPE_HTML,  'validate' => 'isString'),
      'width' => 	array('type' => self::TYPE_INT, 'required' => true,  'validate' => 'isunsignedInt'),
      'height' => 	array('type' => self::TYPE_INT, 'required' => true,  'validate' => 'isunsignedInt'),


      // Lang fields
      'title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255),



    )
  );

  public function __construct($id_top_menu = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_top_menu, $id_lang, $id_shop);
    
    $this->image_dir = _PS_MODULE_DIR_ . 'topmenu/views/img/';
    $this->id_image = ($this->id && file_exists($this->image_dir.(int)$this->id.'.png')) ? (int)$this->id : false;

    Shop::addTableAssociation('top_menu_lang', array('type' => 'fk_shop'));
  }


  public function add($autodate = true, $null_values = false)
  {

    if(!$this->getTopMenu(Context::getContext()->shop->id, Context::getContext()->language->id)){
      $position = 0;
    }
    else{
      $position = (int)$this->getLastCategoryPosition() + 1;
    }


    $this->position = $position;
    $res = parent::add($autodate, $null_values);
    return $res;
  }

  public function getLastCategoryPosition()
  {
    return (int)(Db::getInstance()->getValue('
		SELECT MAX(c.`position`)
		FROM `'._DB_PREFIX_.'top_menu` c
		') );
  }

  public function delete()
  {
    $this->deleteImage();
    $res = parent::delete();

    return $res;
  }

  public function getTopMenu($id_shop = false, $id_lang = false)
  {
    $sql = 'SELECT *
        FROM '._DB_PREFIX_.'top_menu tm
        LEFT JOIN '._DB_PREFIX_.'top_menu_lang tml
        ON (tm.id_top_menu = tml.id_top_menu AND tml.id_shop = '.($id_shop ? (int)$id_shop: Configuration::get('PS_SHOP_DEFAULT')).')
        WHERE tm.active=1
        AND tml.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).'
        ORDER BY tm.position'
    ;

    return Db::getInstance()->ExecuteS($sql);
  }

}