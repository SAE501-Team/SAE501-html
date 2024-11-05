<?php

class blockFeatured extends ObjectModel
{
  public $id_homefeatured;
  public $active = 1;
  public $position;
  public $type;
  public $ids_products;
  public $ids_categories;
  public $date_add;
  public $title;

  public static $definition = array(
    'table' => 'homefeatured',
    'primary' => 'id_homefeatured',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(
      //basic fields

      'active' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'position' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'type' =>			array('type' => self::TYPE_STRING,  'validate' => 'isCleanHtml'),
      'ids_products' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'ids_categories' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'date_add' =>            array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

      // Lang fields

      'title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'required' => true, 'validate' => 'isCleanHtml',  'size' => 255),
    )
  );

  public function __construct($id_homefeatured = null, $id_lang = null, $id_shop = null)
  {

    parent::__construct($id_homefeatured, $id_lang, $id_shop);
    Shop::addTableAssociation('homefeatured_lang', array('type' => 'fk_shop'));
  }

  public function update($null_values = false)
  {
//    $this->clearSmartyCacheSlider(_PS_MODULE_DIR_.'blockhomeslider/views/templates/hook/slider.tpl', 'blockhomeslider_');
    $res = parent::update($null_values);
    return $res;
  }

  public function delete()
  {
    $res = parent::delete();
//    $this->clearSmartyCacheSlider(_PS_MODULE_DIR_.'blockhomeslider/views/templates/hook/slider.tpl', 'blockhomeslider_');
    return $res;
  }

  public function add($autodate = true, $null_values = false)
  {
    $isset = $this->getHomeFeatured(Context::getContext()->language->id, Context::getContext()->shop->id);
        if(!isset($isset[0])){
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
		FROM `'._DB_PREFIX_.'homefeatured` s
		') );
  }


//  public function clearSmartyCacheSlider($tpl, $id)
//  {
//    $languages = Language::getLanguages(false);
//    foreach ($languages as $language)
//    {
//      Tools::enableCache();
//      Tools::clearCache(null, $tpl, $id.$language['id_lang']);
//      Tools::restoreCacheSettings();
//    }
//  }

  public function getHomeFeatured($id_lang, $id_shop, $id = false, $active = false){
    $and = '';
    $act = '';
    if($id){
      $and = ' AND h.id_homefeatured = '.(int)$id;
    }
    if($active){
      $act= ' AND h.active = 1';
    }
    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'homefeatured as h
      INNER JOIN ' . _DB_PREFIX_ . 'homefeatured_lang as hl
      ON h.id_homefeatured = hl.id_homefeatured
      WHERE hl.id_lang = ' . (int)$id_lang . '
      AND hl.id_shop = ' . (int)$id_shop . $and . $act. '
      ORDER BY h.position


			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

}