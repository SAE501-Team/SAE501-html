<?php

class FreeCallClass extends ObjectModel
{
  public $id_freecall;
  public $email = 'admin@gmail.com';


  public $border_radius = 10;
  public $color_form = '#ffffff';
  public $background_form = '#000000';
  public $opacity_form = 0.8;
  public $background_overlay = '#000000';
  public $opacity_overlay = 0.3;
  public $background_button = '#199c0c';
  public $background_button_hover = '#408e39';
  public $color_button = '#ffffff';
  public $color_button_hover = '#ffffff';
  public $show_email = 0;

  public $show_comment = 0;
  public $show_captcha = 0;
  public $time_show_question = 6000;
  public $show_question = 1;
  public $title_icon;
  public $category_page = 0;
  public $product_page = 0;

  public $phone;
  public $phone2;
  public $title_form;
  public $description;
  public $title_success;
  public $description_success;
  public $title_question;
  public $description_question ;
  public $date_add;

  public static $definition = array(
    'table' => 'freecall',
    'primary' => 'id_freecall',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(
      //basic fields

      'date_add' =>    array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
      'show_question' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'show_email' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'category_page' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'product_page' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'show_comment' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'show_captcha' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),

      'phone' => 	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
      'phone2' => 	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
      'border_radius' => 	array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
      'time_show_question' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'opacity_form' => 	array('type' => self::TYPE_FLOAT, 'required' => true, 'validate' => 'isUnsignedFloat'),
      'opacity_overlay' => 	array('type' => self::TYPE_FLOAT, 'required' => true, 'validate' => 'isUnsignedFloat'),
      'email' =>     array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isString'),


      'color_form' =>     array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isString'),
      'background_form' =>     array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isString'),
      'background_overlay' =>     array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isString'),
      'color_button' =>     array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isString'),
      'color_button_hover' =>     array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isString'),
      'background_button' =>     array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isString'),
      'background_button_hover' =>     array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isString'),


      // Lang fields
      'title_form' =>			array('type' => self::TYPE_STRING, 'required' => true, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 512),
      'title_success' =>			array('type' => self::TYPE_STRING, 'required' => true, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 512),
      'title_question' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 512),
      'description' =>			array('type' => self::TYPE_HTML, 'required' => true,  'lang' => true, 'validate' => 'isCleanHtml'),
      'description_success' =>			array('type' => self::TYPE_HTML, 'required' => true, 'lang' => true, 'validate' => 'isCleanHtml'),
      'description_question' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
    )
  );

  public function __construct($id_block_home_slider = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_block_home_slider, $id_lang, $id_shop);
    Shop::addTableAssociation('block_home_slider_lang', array('type' => 'fk_shop'));
  }

  public function update($null_values = false)
  {
    $res = parent::update($null_values);


    return $res;
  }

  public function delete()
  {
    $res = parent::delete();
    return $res;
  }




  public function add($autodate = true, $null_values = false)
  {
    $res = parent::add($autodate, $null_values);
    return $res;
  }

  public function getFreeCall($id_lang, $id_shop){
    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'freecall as f
      INNER JOIN ' . _DB_PREFIX_ . 'freecall_lang as fl
      ON f.id_freecall = fl.id_freecall
      WHERE fl.id_lang = ' . (int)$id_lang . '
      AND fl.id_shop = ' . (int)$id_shop . '
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function validateField($field, $value, $id_lang = null, $skip = array(), $human_errors = false)
  {
    if ($field == 'email') {
      $emails = explode(',', $value);
      foreach($emails as $email){
        $email = trim($email);
        if(!Validate::isEmail($email)){
          $this->def['fields']['email']['validate'] = Tools::displayError('Email : Incorrect value');
        }
      }
    }

    return parent::validateField($field, $value, $id_lang, $skip, $human_errors);
  }

}