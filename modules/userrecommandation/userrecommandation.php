<?php
/**
* 2007-2024 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immédiatement.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2024 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Userrecommandation extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'userrecommandation';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'BEHH';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Outil de recommandation BEHH');
        $this->description = $this->l('Ce module, va permettre de stoker dans la base de données des informations concernant les préférences de consommation des utilisateurs, pour leur fournir une recommandation de produits pertinente.');
        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller ce module ?');

        $this->ps_versions_compliancy = array('min' => '8.0', 'max' => _PS_VERSION_);
    }

    /**
     * Install the module and create the table in the database
     */
    public function install()
    {
        Configuration::updateValue('USERRECOMMANDATION_LIVE_MODE', false);
        include(dirname(__FILE__).'/sql/install.php');  // Include the install script to create the table

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayCustomerForm');
    }

    /**
     * Uninstall the module and remove the table from the database
     */
    public function uninstall()
    {
        Configuration::deleteByName('USERRECOMMANDATION_LIVE_MODE');
        include(dirname(__FILE__).'/sql/uninstall.php');  // Include the uninstall script to drop the table

        return parent::uninstall();
    }

    /**
     * Display module configuration page in the back office
     */
    public function getContent()
    {
        if (((bool)Tools::isSubmit('submitUserrecommandationModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form for configuring the module in the back office
     */
    protected function renderForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitUserrecommandationModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Set values for the form fields
     */
    protected function getConfigFormValues()
    {
        return array(
            'USERRECOMMANDATION_LIVE_MODE' => Configuration::get('USERRECOMMANDATION_LIVE_MODE', true),
        );
    }


    /**
     * Add CSS and JavaScript files to the back office
     */
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add CSS and JavaScript files to the front office
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookDisplayCustomerForm($params)
    {
        // Récupérer les préférences existantes de l'utilisateur
        $id_customer = $this->context->customer->id;
        $preferences = Db::getInstance()->getRow('
            SELECT * FROM `ps_user_recommandation`
            WHERE `id_customer` = '.(int)$id_customer
        );

        $this->context->smarty->assign(array(
            'type_consommation' => $preferences['type_consommation'] ?? '',
            'souhaitez_cereales_originales' => $preferences['souhaitez_cereales_originales'] ?? '',
            'gout_prefere' => $preferences['gout_prefere'] ?? '',
            'forme_favorite' => $preferences['forme_favorite'] ?? '',
            'consommation_pour_qui' => $preferences['consommation_pour_qui'] ?? '',
        ));

        return $this->display(__FILE__, 'views/templates/hook/displayCustomerForm.tpl');
    }


    public function saveCustomerPreferences($id_customer, $type_consommation, $souhaitez_cereales_originales, $gout_prefere, $forme_favorite, $consommation_pour_qui)
    {
        $existingPreferences = Db::getInstance()->getRow('
            SELECT * FROM `ps_user_recommandation`
            WHERE `id_customer` = '.(int)$id_customer
        );

        if ($existingPreferences) {
            Db::getInstance()->update(
                'user_recommandation',
                array(
                    'type_consommation' => pSQL($type_consommation),
                    'souhaitez_cereales_originales' => pSQL($souhaitez_cereales_originales),
                    'gout_prefere' => pSQL($gout_prefere),
                    'forme_favorite' => pSQL($forme_favorite),
                    'consommation_pour_qui' => pSQL($consommation_pour_qui)
                ),
                'id_customer = '.(int)$id_customer
            );
        } else {
            Db::getInstance()->insert(
                'user_recommandation',
                array(
                    'id_customer' => (int)$id_customer,
                    'type_consommation' => pSQL($type_consommation),
                    'souhaitez_cereales_originales' => pSQL($souhaitez_cereales_originales),
                    'gout_prefere' => pSQL($gout_prefere),
                    'forme_favorite' => pSQL($forme_favorite),
                    'consommation_pour_qui' => pSQL($consommation_pour_qui)
                )
            );
        }
    }

    public function postProcess()
    {
        // Traitement des préférences utilisateur
        if (Tools::isSubmit('submit_preferences')) {
            $id_customer = $this->context->customer->id;
            $type_consommation = Tools::getValue('type_consommation');
            $souhaitez_cereales_originales = Tools::getValue('souhaitez_cereales_originales');
            $gout_prefere = Tools::getValue('gout_prefere');
            $forme_favorite = Tools::getValue('forme_favorite');
            $consommation_pour_qui = Tools::getValue('consommation_pour_qui');
    
            $this->saveCustomerPreferences($id_customer, $type_consommation, $souhaitez_cereales_originales, $gout_prefere, $forme_favorite, $consommation_pour_qui);
        }

        // Traitement de la configuration du module
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }
}
