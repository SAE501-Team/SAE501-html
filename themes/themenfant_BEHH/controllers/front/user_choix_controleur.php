<?php
class UserChoixControleurModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $this->setTemplate('themes/themenfant_BEHH/templates/front/choix_utilisateur.tpl');
    }
}
