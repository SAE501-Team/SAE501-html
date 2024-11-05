<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.09.15
 * Time: 20:33
 */
require_once(dirname(__FILE__) . '/../../classes/FreeCallClass.php');

class freecallAjaxFormModuleFrontController extends FrontController
{
  private $_freeCallClass;

  public function initContent()
  {

    if (!$this->ajax) {
      parent::initContent();
    }
  }

  public function displayAjax()
  {
    $this->_freeCallClass = new FreeCallClass();
    $json = array();
    try{
      if (Tools::getValue('action') == 'send'){
        $phone = Tools::getValue('phone');
        $email = Tools::getValue('email');
        $comment = Tools::getValue('comment');
        $id_lang = Tools::getValue('id_lang');
        $id_shop = Tools::getValue('id_shop');
        $captcha = Tools::getValue('captcha');

        $settings = $this->_freeCallClass->getFreeCall($id_lang, $id_shop);
        if(isset($settings[0]) && $settings[0]){
          $settings = $settings[0];
        }

        if( !$phone || !ValidateCore::isPhoneNumber($phone)){
          throw new Exception ( 'phone' );
        }

        if( $email && !Validate::isEmail($email)){
          throw new Exception ( 'email' );
        }

          if($settings['show_captcha']){

              $captcha_session = Tools::strtolower(Context::getContext()->cookie->_CAPTCHA);
              $captcha = Tools::strtolower($captcha);

              if(!$captcha || ($captcha_session !== $captcha)){
                  throw new Exception ( 'captcha' );
              }
          }

        $this->setToCustomerService($phone, $email, $comment, $id_lang, $id_shop);
        if($settings['email']){
          $emails = explode(',', $settings['email']);
          foreach($emails as $send_to){
            $template_vars = $this->templateMail($phone, $email, $comment);
            $template_vars = array('{content}' => $template_vars);
            $send = $this->sendMessage($template_vars, trim($send_to), $email);
          }

          if( !$send ){
            $json['error'] = true;
            $json['title'] = Module::getInstanceByName('freecall')->l('Error');
            $json['description'] = Module::getInstanceByName('freecall')->l('Some error occurred please contact us!');
          }
          else{
            $json['success'] = true;
            $json['title'] = $settings['title_success'];
            $json['description'] = $settings['description_success'];
          }
        }
      }

      if (Tools::getValue('action') == 'showForm'){
        $settings = $this->_freeCallClass->getFreeCall(Tools::getValue('id_lang'), Tools::getValue('id_shop'));
        $json['form'] = $this->getFreeCallForm($settings, Tools::getValue('id_lang'), Tools::getValue('id_shop'), Tools::getValue('click')) ;
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

  public function setToCustomerService($phone, $email, $comment, $id_lang, $id_shop)
  {
    $com = ' ';
    $id_contact = 2;
    $contact = new Contact($id_contact, $id_lang);

    $com .= Module::getInstanceByName('freecall')->l('Request a CallBack.')."\n";
    $com .= Module::getInstanceByName('freecall')->l('Phone Number:').$phone."\n";

    if($email){
      $com .= Module::getInstanceByName('freecall')->l('Email:').$email."\n";
    }
    if($comment){
      $com .= Module::getInstanceByName('freecall')->l('Message:').$comment."\n";
    }


    if($email){
      $id_customer_thread = $this->getIdCustomerThreadByEmail($email, $id_shop);
    }
    else{
      $id_customer_thread = false;
    }

    if($id_customer_thread){
      $old = $this->oldMessage($id_customer_thread, $id_shop);
      if ($old == $com) {
        $contact->email = '';
        $contact->customer_service = 0;
      }
    }

    if ($contact->customer_service) {
      if ((int)$id_customer_thread) {
        $ct = new CustomerThread($id_customer_thread);
        $ct->id_shop = (int)$id_shop;
        $ct->id_lang = (int)$id_lang;
        $ct->id_contact = $id_contact;
        $ct->email = $email;
        $ct->status = 'open';
        $ct->token = Tools::passwdGen(12);
        $ct->update();
      }
      else{
        $ct = new CustomerThread();
        $ct->id_shop = (int)$id_shop;
        $ct->id_lang = (int)$id_lang;
        $ct->id_contact = $id_contact;
        $ct->email = $email;
        $ct->status = 'open';
        $ct->token = Tools::passwdGen(12);
        $ct->add();
      }

      if ($ct->id) {
        $cm = new CustomerMessage();
        $cm->id_customer_thread = $ct->id;
        $cm->message = $com;
        $cm->ip_address = (int)ip2long(Tools::getRemoteAddr());
        $cm->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $cm->add();
      }
    }
  }

  public function sendMessage($template_vars, $send_to, $email){
         $mail = Mail::Send(
            Configuration::get('PS_LANG_DEFAULT'),
            'freecall',
            Module::getInstanceByName('freecall')->l('Request a CallBack'),
            $template_vars,
            "$send_to",
            NULL,
            $email ? $email : NULL,
            NULL,
            NULL,
            NULL,
            dirname(__FILE__).'/../../mails/');
    return $mail;
  }

  public function templateMail($phone, $email, $comment){

    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'freecall/views/templates/hook/templateMail.tpl');
    $baseUrl = _PS_BASE_URL_SSL_.__PS_BASE_URI__;
    $logo = self::$link->getMediaLink(_PS_IMG_.Configuration::get('PS_LOGO'));
    $data->assign(
      array(
        'logo_url'     =>  $logo,
        'baseUrl'      => $baseUrl,
        'phone'        => $phone,
        'email'        => $email,
        'comment'      => $comment,
      )
    );
    return $data->fetch();
  }

  public function getFreeCallForm($settings, $id_lang, $id_shop, $click)
  {

    if( version_compare(_PS_VERSION_, '1.6.0.0') >= 0 && version_compare(_PS_VERSION_, '1.7.0.0') < 0) {
      $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'freecall/views/templates/hook/formFreeCall.tpl');
    }
    else{
      $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'freecall/views/templates/hook/formFreeCall17.tpl');
    }

    if(isset($settings[0]) && $settings[0]){
      $settings = $settings[0];
    }

    if(!$settings){
      return false;
    }

    $data->assign(
      array(
        'id_shop'           => $id_shop,
        'id_lang'           => $id_lang,
        'settings'          => $settings,
        'captcha_url'           => _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/freecall/secpic.php',
        'click'             => $click,
        'is_mobile'         => Context::getContext()->getMobileDevice(),
      )
    );
    return $data->fetch();
  }

  public function getIdCustomerThreadByEmail($email, $id_shop)
  {
    return Db::getInstance()->getValue('
			SELECT cm.id_customer_thread
			FROM '._DB_PREFIX_.'customer_thread cm
			WHERE cm.email = \''.pSQL($email).'\'
				AND cm.id_shop = '.(int)$id_shop
    );
  }

  public function oldMessage($id_customer_thread, $id_shop){
    return Db::getInstance()->getValue('
					SELECT cm.message FROM '._DB_PREFIX_.'customer_message cm
					LEFT JOIN '._DB_PREFIX_.'customer_thread cc on (cm.id_customer_thread = cc.id_customer_thread)
					WHERE cc.id_customer_thread = '.(int)$id_customer_thread.' AND cc.id_shop = '.(int)$id_shop.'
					ORDER BY cm.date_add DESC');
  }

}