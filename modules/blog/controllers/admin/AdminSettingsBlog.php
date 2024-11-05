<?php

class AdminSettingsBlogController extends ModuleAdminController
{
  public function __construct()
  {
    $redirect = Context::getContext()->link->getAdminLink('AdminModules');
    $redirect .= '&configure=blog';
    Tools::redirectAdmin($redirect);
    die;
  }
}