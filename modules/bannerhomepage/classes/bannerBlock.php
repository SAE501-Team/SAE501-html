<?php

class bannerBlock extends ObjectModel
{
  public $id_banner;
  public $active;
  public $position;
  public $date_add;
  public $title;
  public $url;
  public $description;
  public $image;
  public $id_image;

  public static $definition = array(
    'table' => 'banner',
    'primary' => 'id_banner',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(
      //basic fields

      'active' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'position' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'date_add' =>            array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

      // Lang fields
      'image' => 	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 255),
      'title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 255),
      'url' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 255),
      'description' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
    )
  );

  public function __construct($id_banner = null, $id_lang = null, $id_shop = null)
  {
    $this->image_dir = _PS_MODULE_DIR_ . 'bannerhomepage/views/img/banner/';
    $this->id_image = ($this->id && file_exists($this->image_dir.(int)$this->id.'.jpg')) ? (int)$this->id : false;

    parent::__construct($id_banner, $id_lang, $id_shop);

    Shop::addTableAssociation('banner_lang', array('type' => 'fk_shop'));
  }

  public function update($null_values = false)
  {
    $res = parent::update($null_values);
    return $res;
  }

  public function delete()
  {
    $this->deleteImgSlider($this->image);
    $res = parent::delete();
    return $res;
  }

  public function add($autodate = true, $null_values = false)
  {
    $banner = $this->issetBanner();
    if(!isset($banner[0]['id_banner']) || !$banner[0]['id_banner']){
      $position = 0;
    }
    else{
      $position = (int)$this->getLastSlidesPosition() + 1;
    }
    $this->position = $position;
    $res = parent::add($autodate, $null_values);

    return $res;
  }

  public function getLastSlidesPosition()
  {
    return (int)(Db::getInstance()->getValue('
		SELECT MAX(s.`position`)
		FROM `'._DB_PREFIX_.'banner` s
		') );
  }

  public function deleteImgSlider($images){

    $dir = _PS_MODULE_DIR_ . 'bannerhomepage/views/img/banner/';
    foreach($images as $val){
      if(file_exists($dir.$val)){
        @unlink($dir.$val);
      }
    }
  }

  public function issetBanner(){
    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'banner as b

			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getAllBanners($id_lang, $id_shop){
    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'banner as b
      LEFT JOIN ' . _DB_PREFIX_ . 'banner_lang as bl
      ON b.id_banner = bl.id_banner
      WHERE bl.id_shop = ' . (int)$id_shop . '
      AND bl.id_lang = '. (int)$id_lang .'
      AND b.active = 1
      ORDER BY b.position

			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


}