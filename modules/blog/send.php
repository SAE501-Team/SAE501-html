<?php
include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');
require_once (dirname(__FILE__).'/classes/blogPost.php');
require_once (dirname(__FILE__).'/classes/blogCategory.php');

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || Tools::strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
  header('HTTP/1.0 403 Forbidden');
  echo 'You are forbidden!';  die;
}
$json = array();

try {

  if( Tools::getValue('addComment') !== false){
    $settings = Tools::unserialize( Configuration::get( 'GOMAKOIL_FUNCTIONAL_BLOG') );
    if(!Tools::getValue('name')){
      throw new Exception ('1');
    }
    if(!Tools::getValue('email') || !Validate::isEmail(trim(Tools::getValue('email')))){
      throw new Exception ('2');
    }
    if(!Tools::getValue('comment')){
      throw new Exception ('3');
    }
    $captcha_session = Tools::strtolower(Context::getContext()->cookie->_CAPTCHA);
    $captcha = Tools::strtolower(Tools::getValue('captcha'));
    if($settings['using_captcha'] && ( $captcha !== $captcha_session || !$captcha ) ){
      throw new Exception ('4');
    }
    if(Tools::getValue('id_blog_post')){
      if($settings['validate_comments']){
        $active = 0;
      }
      else{
        $active = 1;
      }
      $comment = array(
        'id_blog_post' => (int)Tools::getValue('id_blog_post'),
        'active'       => $active,
        'content'      => pSQL(Tools::getValue('comment')),
        'author_name'  => pSQL(Tools::getValue('name')),
        'author_email' => pSQL(Tools::getValue('email')),
        'id_shop'      => (int)Tools::getValue('shopId'),
        'date_add'     => date('Y-m-d H:i:s'),
        'rating'       => (int)Tools::getValue('raty'),
      );
  
      $rez = Db::getInstance()->insert('blog_comment', $comment);
    }

    if($rez){
      if($settings['new_comments']){
        $objPost = new blogPost(Tools::getValue('id_blog_post'),Tools::getValue('langId'),Tools::getValue('shopId'));
        $objCategory = new blogCategory($objPost->id_blog_category,Tools::getValue('langId'),Tools::getValue('shopId'));
        $link_rewrite_cat = $objCategory->link_rewrite;
        $link_rewrite = $objPost->link_rewrite;
        $url = _PS_BASE_URL_SSL_.__PS_BASE_URI__ .'/blog/'.$link_rewrite_cat.'/'.$link_rewrite.'.html';

        $mailMessage = '';
        $mailMessage .= '<div style="width: 50%; min-width: 160px;margin: 0 auto;margin-top: 40px;margin-bottom: 40px;border: 1px solid #dadada;border-radius: 6px;    ">';
        $mailMessage .= '<div style="padding: 20px;border-bottom: 1px solid #dadada;font-size: 20px;text-align: center;
        border-radius: 6px 6px 0px 0px;
        background-image: -ms-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: -moz-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: -o-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #FFFFFF), color-stop(20, #FFFFFF), color-stop(40, #FCFCFC), color-stop(60, #FAFAFA), color-stop(80, #FAFAFA), color-stop(100, #EDEDED));
        background-image: -webkit-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: linear-gradient(to bottom, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);">'.Module::getInstanceByName('blog')->l('New comment', 'send').'</div><div style="padding: 30px;font-size: 14px;">';
        if($objPost->name){
          $mailMessage .= '<div style="margin-bottom: 10px;"><div style="float: left;width: 150px;margin: 2px 10px 2px 0px;"><strong>'.Module::getInstanceByName('blog')->l('Article:', 'send').'</strong></div><div style="float: left;margin-top: 2px;width: 65%;"> <a href="'. $url .'">'. $objPost->name .'</a></div><div style="clear: both;"></div></div>';
        }
        if( Tools::getValue('comment')){
          $mailMessage .= '<div style="margin-bottom: 10px;"><div style="float: left;width: 150px;margin: 2px 10px 2px 0px;"><strong>'.Module::getInstanceByName('blog')->l('Comment:', 'send').'</strong></div><div style="float: left;margin-top: 2px;"> ' . Tools::getValue('comment') .'</div><div style="clear: both;"></div></div>';
        }
        if( Tools::getValue('raty')){
          $mailMessage .= '<div style="margin-bottom: 10px;"><div style="float: left;width: 150px;margin: 2px 10px 2px 0px;"><strong>'.Module::getInstanceByName('blog')->l('Rating:', 'send').'</strong></div><div style="float: left;margin-top: 2px;"> ' . Tools::getValue('raty') .'</div><div style="clear: both;"></div></div>';
        }
        if( Tools::getValue('name')){
          $mailMessage .= '<div style="margin-bottom: 10px;"><div style="float: left;width: 150px;margin: 2px 10px 2px 0px;"><strong>'.Module::getInstanceByName('blog')->l('Customer name:', 'send').'</strong></div><div style="float: left;margin-top: 2px;"> ' . Tools::getValue('name') .'</div><div style="clear: both;"></div></div>';
        }
        if( Tools::getValue('email')){
          $mailMessage .= '<div style="margin-bottom: 10px;"><div style="float: left;width: 150px;margin: 2px 10px 2px 0px;"><strong>'.Module::getInstanceByName('blog')->l('Customer email:', 'send').'</strong></div><div style="float: left;margin-top: 2px;"> ' . Tools::getValue('email') .'</div><div style="clear: both;"></div></div>';
        }
        $mailMessage .= '<div style="clear: both;display: block !important;"></div><div style="clear: both;display: block !important;"></div><div style="clear: both; display: block !important;">';
        $template_vars = array('{content}' => $mailMessage);
        $users_emails = trim($settings['send_email']);
        $users_emails = explode(',',$users_emails);
        foreach( $users_emails as $users_email ){
          $mail = Mail::Send(
            Configuration::get('PS_LANG_DEFAULT'),
            'blog',
            Module::getInstanceByName('blog')->l('New comment', 'send'),
            $template_vars,
            "$users_email",
            NULL,
            Tools::getValue('email') ? Tools::getValue('email') : NULL,
            Tools::getValue('name') ? Tools::getValue('name') : NULL,
            NULL,
            NULL,
            dirname(__FILE__).'/mails/');
        }

      }
      $json['success'] = Module::getInstanceByName('blog')->l('Comment successfully added!', 'send');
    }
    else{
      throw new Exception ('5');
    }
  }

  if( Tools::getValue('captcha_true') !== false){
    $json['captcha'] = Module::getInstanceByName('blog')->captchaBlog();
  }
  if( Tools::getValue('add_product') !== false){
    $id_blog_post = Tools::getValue('id_blog_post');
    if( isset($id_blog_post) && $id_blog_post && $id_blog_post !== 'undefined'){
      $objPost = new blogPost();
      $related_products = $objPost->getRelatedProducts(Tools::getValue('id_lang'),Tools::getValue('id_shop'), $id_blog_post);
      if($related_products){
        $related_products = Tools::unserialize($related_products);
        if (!in_array( Tools::getValue('id_product'), $related_products)){
          array_push($related_products, Tools::getValue('id_product'));
        }
        else{
          $key = array_search(Tools::getValue('id_product'), $related_products);
          if ($key !== false)
          {
            unset ($related_products[$key]);
          }
        }
      }
      else{
        $related_products[] = Tools::getValue('id_product');
      }
      $related_products =serialize($related_products);
      $related_products = array('id_related_products' => $related_products);
      Db::getInstance()->update('blog_post', $related_products, 'id_blog_post='.(int)$id_blog_post);
    }
    else{
      $name_config = 'GOMAKOIL_PRODUCTS_CHECKED';
      $config = Tools::unserialize(Configuration::get($name_config));
      if( !$config ){
        $config = array();
      }
      if (!in_array( Tools::getValue('id_product'), $config)){
        array_push($config, Tools::getValue('id_product'));
      }
      else{
        $key = array_search(Tools::getValue('id_product'), $config);
        if ($key !== false)
        {
          unset($config[$key]);
        }
      }
      $config = serialize($config);
      Configuration::updateValue($name_config, $config);
    }
  }

  if( Tools::getValue('search_product') !== false){
    $json['products'] = Module::getInstanceByName('blog')->searchProducts(Tools::getValue('search_product'), Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_blog_post'));
  }

  if( Tools::getValue('show_checked_products') !== false){
    $json['products'] = Module::getInstanceByName('blog')->showCheckedProducts(Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_blog_post'));
  }

  if( Tools::getValue('show_all_products') !== false){
    $json['products'] = Module::getInstanceByName('blog')->showAllProducts(Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_blog_post'));
  }

  if( Tools::getValue('add_post') !== false){

    $id_blog_post = Tools::getValue('id_blog_post');

    if( isset($id_blog_post) && $id_blog_post && $id_blog_post !== 'undefined'){

      $objPost = new blogPost();
      $related_posts = $objPost->getRelatedPosts(Tools::getValue('id_lang'),Tools::getValue('id_shop'), $id_blog_post);

      if($related_posts){
        $related_posts = Tools::unserialize($related_posts);
        if (!in_array( Tools::getValue('id_post'), $related_posts)){
          array_push($related_posts, Tools::getValue('id_post'));
        }
        else{
          $key = array_search(Tools::getValue('id_post'), $related_posts);
          if ($key !== false)
          {
            unset ($related_posts[$key]);
          }
        }
      }
      else{
        $related_posts[] = Tools::getValue('id_post');
      }
      $related_posts =serialize($related_posts);

      $related_posts = array('id_related_posts' => $related_posts);

      Db::getInstance()->update('blog_post', $related_posts, 'id_blog_post='.(int)$id_blog_post);
    }
    else{
      $name_config = 'GOMAKOIL_POSTS_CHECKED';
      $config = Tools::unserialize(Configuration::get($name_config));
      if( !$config ){
        $config = array();
      }
      if (!in_array( Tools::getValue('id_post'), $config)){
        array_push($config, Tools::getValue('id_post'));
      }
      else{
        $key = array_search(Tools::getValue('id_post'), $config);
        if ($key !== false)
        {
          unset($config[$key]);
        }
      }
      $config = serialize($config);
      Configuration::updateValue($name_config, $config);
    }
  }

  if( Tools::getValue('search_post') !== false){
    $json['post'] = Module::getInstanceByName('blog')->searchPosts(Tools::getValue('search_post'), Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_blog_post'));
  }

  if( Tools::getValue('show_checked_post') !== false){
    $json['post'] = Module::getInstanceByName('blog')->showCheckedPosts( Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_blog_post'));
  }

  if( Tools::getValue('show_all_post') !== false){
    $json['post'] = Module::getInstanceByName('blog')->showAllPosts( Tools::getValue('id_shop'), Tools::getValue('id_lang'), Tools::getValue('id_blog_post'));
  }

  echo json_encode($json);
}
catch( Exception $e ){
  $json['error'] = $e->getMessage();
  echo json_encode($json);
}