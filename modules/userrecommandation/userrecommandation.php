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

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/src/Entity/CustomerRecommandation.php';


class UserRecommandation extends Module
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
        include(dirname(__FILE__).'/sql/install.php'); // Inclure le script SQL

        // Ajout du contrôleur
        if (!parent::install() ||
            !$this->registerHook('header') ||
            !$this->registerHook('displayFormReco') ||
            !$this->registerHook('displayProductReco') ||
            !$this->registerHook('displayBackOfficeHeader') ||
            !$this->registerHook('addWebserviceResources') || // Pour le webservice
            !$this->installTab()
        ) {
            return false;
        }

        return true;
    }

    // Méthode pour enregistrer l'onglet (controller) dans le back-office
    protected function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminUserrecommandation';
        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Recommandation utilisateur';
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminParentModulesSf'); // Placer sous "Modules"
        $tab->module = $this->name;

        return $tab->add();
    }

    // Méthode pour supprimer l'onglet lors de la désinstallation
    protected function uninstallTab()
    {
        $id_tab = (int) Tab::getIdFromClassName('AdminUserrecommandation');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }

        return true;
    }

    /**
     * Uninstall the module and remove the table from the database
     */
    public function uninstall()
    {
        Configuration::deleteByName('USERRECOMMANDATION_LIVE_MODE');
        include(dirname(__FILE__).'/sql/uninstall.php');  // Include the uninstall script to drop the table

        return parent::uninstall() &&
            $this->uninstallTab() &&
            include(dirname(__FILE__).'/sql/uninstall.php');
    }

    public function hookAddWebserviceResources($params)
    {
        return [
            'user_recommandations' => [
                'description' => 'Customer Recommendations', // Description visible via l'API
                'class' => 'CustomerRecommandation' // Classe associée à cette ressource
            ],
        ];
    }


    /**
     * Display module configuration page in the back office
     */
    public function getContent()
    {
        if (((bool)Tools::isSubmit('submitUserrecommandationModule')) == true) {
            $this->postProcess();
        }

        $currentUrl = $this->context->link->getAdminLink('AdminUserrecommandation', true);
        $this->context->smarty->assign('current', $currentUrl);

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

    public function hookDisplayFormReco($params) //changer ce hook
    {
        if (!$this->context->customer->isLogged()) {
            return ''; // Ne rien afficher si l'utilisateur n'est pas connecté
        }
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

        return $this->display(__FILE__, 'views/templates/hook/displayFormReco.tpl');
    }

    public function hookDisplayProductReco($params)
    {
        if (!$this->context->customer->isLogged()) {
            // Si l'utilisateur n'est pas connecté, récupérer les produits via la requête SQL par défaut
            $productIds = Db::getInstance()->executeS('
                SELECT pc.id_product AS id_product
                FROM ps_product_comment pc
                LEFT JOIN ps_product p ON pc.id_product = p.id_product
                LEFT JOIN ps_category_product cp ON p.id_product = cp.id_product
                LEFT JOIN ps_category_lang cl ON cp.id_category = cl.id_category AND cl.id_lang = 1
                WHERE pc.grade >= 4
                AND cl.id_category IN (11, 12, 13, 14)
                GROUP BY pc.id_product
                ORDER BY AVG(pc.grade) DESC
                LIMIT 8
            ');
        
        } else {
            // Si l'utilisateur est connecté, récupérer les produits recommandés
            $id_customer = $this->context->customer->id;
            $productIds = Db::getInstance()->executeS('
                SELECT id_product 
                FROM ps_produit_recommander
                WHERE id_customer = '.(int)$id_customer
            );
        
            // Si aucun produit n'est trouvé, utiliser la requête SQL par défaut
            if (empty($productIds)) {
                $productIds = Db::getInstance()->executeS('
                    SELECT pc.id_product AS id_product
                    FROM ps_product_comment pc
                    LEFT JOIN ps_product p ON pc.id_product = p.id_product
                    LEFT JOIN ps_category_product cp ON p.id_product = cp.id_product
                    LEFT JOIN ps_category_lang cl ON cp.id_category = cl.id_category AND cl.id_lang = 1
                    WHERE pc.grade >= 4
                    AND cl.id_category IN (11, 12, 13, 14)
                    GROUP BY pc.id_product
                    ORDER BY AVG(pc.grade) DESC
                    LIMIT 3
                ');
            }
        }

        // Extraire uniquement les IDs des produits

        $productIds = array_column($productIds, 'id_product');
        $productidValue = implode(",", $productIds);
        
        //PrestaShopLogger::addLog('Product avant implode : ' . print_r($productIds, true), 1, null, 'CustomTest');

        $products = $this->getProductsReco(
            (int) $this->context->language->id,
            0,
            3,
            'id_product',
            'ASC',
            $productidValue,
            false,
            true
        );

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        if (version_compare(_PS_VERSION_, '1.7.5', '>=')) {
            $presenter = new \PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingPresenter(
                new ImageRetriever(
                    $this->context->link
                ),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );
        } else {
            $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
                new ImageRetriever(
                    $this->context->link
                ),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );
        }

        $products_for_template = [];

        if (is_array($products)) {
            foreach ($products as $rawProduct) {
                $products_for_template[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                );
            }
        }

        // Charger les produits avec leurs propriétés complètes
        /*$products = [];
        foreach ($productIds as $id_product) {
            $product = new Product($id_product, true, $this->context->language->id);

            // Ajouter les champs de lien et d'image
            $productFields = Product::getProductProperties($this->context->language->id, $product->getFields());
            $productFields['link'] = $this->context->link->getProductLink($product);
            $productFields['image'] = $this->context->link->getImageLink(
                $productFields['link_rewrite'], 
                $productFields['id_image'], 
                'home_default' // Taille de l'image (modifiable selon votre configuration)
            );

            // Ajout des produits au tableau
            $products[] = $productFields;
        }*/

        // Assigner les produits au template
        $this->context->smarty->assign([
            'products' => $products_for_template,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/displayProductReco.tpl');
    }




/*$this->context->smarty->assign(array(
            'type_consommation' => $preferences['type_consommation'] ?? '',
            'souhaitez_cereales_originales' => $preferences['souhaitez_cereales_originales'] ?? '',
            'gout_prefere' => $preferences['gout_prefere'] ?? '',
            'forme_favorite' => $preferences['forme_favorite'] ?? '',
            'consommation_pour_qui' => $preferences['consommation_pour_qui'] ?? '',
        ));*/
    public function saveCustomerPreferences($id_customer, $type_consommation, $souhaitez_cereales_originales, $gout_prefere, $forme_favorite, $consommation_pour_qui)
    {
        //PrestaShopLogger::addLog('Je passe dans la fonction saveCustomerPreferences', 1, null, 'CustomTest');
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
        $this->creat_recommandation($id_customer);
    }

    public function creat_recommandation($id_customer)
    {
        PrestaShopLogger::addLog('Id customer : ' . print_r($id_customer, true), 1, null, 'CustomTest');
        // Connexion à la base
        $db = Db::getInstance();

        // Requêtes SQL pour récupérer les informations de l'utilisateur
        $userInfo = $db->getRow("
            SELECT
                c.birthday AS date_de_naissance,
                c.id_gender AS sexe,
                ur.type_consommation,
                ur.souhaitez_cereales_originales,
                ur.gout_prefere,
                ur.forme_favorite,
                ur.consommation_pour_qui
            FROM
                ps_customer c
            LEFT JOIN ps_user_recommandation ur ON c.id_customer = ur.id_customer
            WHERE
                c.id_customer = $id_customer
        ");

        PrestaShopLogger::addLog('Données utilisateur récupérées après la connexion: ' . print_r($userInfo, true), 1, null, 'CustomTest');

        // Requêtes SQL pour récupérer les produits noté par l'utilisateur
        $userRating = $db->executeS("
            SELECT
                pc.id_product AS id_produit,
                pc.grade AS rating,
                p.price AS prix,
                GROUP_CONCAT(DISTINCT CASE WHEN f.id_feature = 2 THEN fvl.value END) AS caracteristiques_formes,
                GROUP_CONCAT(DISTINCT CASE WHEN f.id_feature = 4 THEN fvl.value END) AS caracteristiques_gouts,
                GROUP_CONCAT(DISTINCT cl.name) AS categorie
            FROM
                ps_product_comment pc
                LEFT JOIN ps_product p ON pc.id_product = p.id_product
                LEFT JOIN ps_category_product cp ON p.id_product = cp.id_product
                LEFT JOIN ps_category_lang cl ON cp.id_category = cl.id_category AND cl.id_lang = 1
                LEFT JOIN ps_feature_product fp ON p.id_product = fp.id_product
                LEFT JOIN ps_feature f ON fp.id_feature = f.id_feature
                LEFT JOIN ps_feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value AND fvl.id_lang = 1
            WHERE
                pc.grade >= 4
                AND pc.id_customer = $id_customer
                AND cl.id_category IN (11, 12, 13, 14)
            GROUP BY
                pc.id_product;
        ");

        PrestaShopLogger::addLog('Données utilisateur produit rating récup après la connexion: ' . print_r($userRating, true), 1, null, 'CustomTest');

        // Requêtes SQL pour récupérer les produits dans la wishlist de l'utilisateur
        $userWishlist = $db->executeS("
            SELECT
                wp.id_product AS id_produit_wishlist,
                p.price AS prix_wishlist,
                GROUP_CONCAT(DISTINCT CASE WHEN f.id_feature = 2 THEN fvl.value END) AS caracteristiques_formes_wishlist,
                GROUP_CONCAT(DISTINCT CASE WHEN f.id_feature = 4 THEN fvl.value END) AS caracteristiques_gouts_wishlist,
                GROUP_CONCAT(DISTINCT cl.name) AS categorie_wishlist
            FROM
                ps_wishlist w
                LEFT JOIN ps_wishlist_product wp ON w.id_wishlist = wp.id_wishlist
                LEFT JOIN ps_product p ON wp.id_product = p.id_product
                LEFT JOIN ps_category_product cp ON p.id_product = cp.id_product
                LEFT JOIN ps_category_lang cl ON cp.id_category = cl.id_category AND cl.id_lang = 1
                LEFT JOIN ps_feature_product fp ON p.id_product = fp.id_product
                LEFT JOIN ps_feature f ON fp.id_feature = f.id_feature
                LEFT JOIN ps_feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value AND fvl.id_lang = 1
            WHERE
                w.id_customer = $id_customer
                AND cl.id_category IN (11, 12, 13, 14)
            GROUP BY
                wp.id_product;
        ");

        PrestaShopLogger::addLog('Données utilisateur produit wishlist récup après la connexion: ' . print_r($userWishlist, true), 1, null, 'CustomTest');

        // Requêtes SQL pour récupérer les produits dans le panier de l'utilisateur
        $userPanier = $db->executeS("
            SELECT
                cd.id_product AS id_produit,
                cd.quantity AS quantite_panier,
                p.price AS prix,
                GROUP_CONCAT(DISTINCT CASE WHEN f.id_feature = 2 THEN fvl.value END) AS caracteristiques_formes,
                GROUP_CONCAT(DISTINCT CASE WHEN f.id_feature = 4 THEN fvl.value END) AS caracteristiques_gouts,
                GROUP_CONCAT(DISTINCT cl.name) AS categorie
            FROM
                ps_cart c
                LEFT JOIN ps_cart_product cd ON c.id_cart = cd.id_cart
                LEFT JOIN ps_product p ON cd.id_product = p.id_product
                LEFT JOIN ps_category_product cp ON p.id_product = cp.id_product
                LEFT JOIN ps_category_lang cl ON cp.id_category = cl.id_category AND cl.id_lang = 1
                LEFT JOIN ps_feature_product fp ON p.id_product = fp.id_product
                LEFT JOIN ps_feature f ON fp.id_feature = f.id_feature
                LEFT JOIN ps_feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value AND fvl.id_lang = 1
            WHERE
                c.id_customer = $id_customer
                AND cl.id_category IN (11, 12, 13, 14)
            GROUP BY
                cd.id_product;
        ");

        PrestaShopLogger::addLog('Données utilisateur produit dans panier récup après la connexion: ' . print_r($userPanier, true), 1, null, 'CustomTest');

        $resultat = $this->generateCustomerProfile($userInfo, $userRating, $userWishlist, $userPanier);
        // Convertir en JSON
        $jsonResultat = json_encode($resultat);
        PrestaShopLogger::addLog('Données utilisateur au format json : ' . print_r($jsonResultat, true), 1, null, 'CustomTest');
    }


    /*public function postProcess()
    {
        // Traitement des préférences utilisateur
        if (Tools::isSubmit('submit_preferences')) {
            $id_customer = $this->context->customer->id;
            $type_consommation = Tools::getValue('type_consommation');
            $souhaitez_cereales_originales = Tools::getValue('souhaitez_cereales_originales');
            $gout_prefere = Tools::getValue('gout_prefere');
            $forme_favorite = Tools::getValue('forme_favorite');
            $consommation_pour_qui = Tools::getValue('consommation_pour_qui');

            PrestaShopLogger::addLog('Je passe dans la fonction postProcess', 1, null, 'CustomTest');

            $this->saveCustomerPreferences($id_customer, $type_consommation, $souhaitez_cereales_originales, $gout_prefere, $forme_favorite, $consommation_pour_qui);
        }

        // Traitement de la configuration du module
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }*/
    
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'USERRECOMMANDATION_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Enable or disable live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    //fonction pour transformer mes données au bon format json
    public function generateCustomerProfile($userInfo, $userRating, $userWishlist, $userPanier)
    {
        // On commence par structurer les données utilisateur
        $profile = [
            "date_de_naissance" => $userInfo['date_de_naissance'],
            "sexe" => (int)$userInfo['sexe'],
            "type_consommation" => $userInfo['type_consommation'],
            "souhaitez_cereales_originales" => ($userInfo['souhaitez_cereales_originales'] === 'oui' ? true : false),
            "gout_prefere" => $userInfo['gout_prefere'],
            "forme_favorite" => $userInfo['forme_favorite'],
            "consommation_pour_qui" => $userInfo['consommation_pour_qui'],
            "rating" => [], // Nous allons remplir cet array avec les ratings
            "wish_liste" => [], // Nous allons remplir cet array avec les wishlist
            "panier" => [] // Nous allons remplir cet array avec les produits du panier
        ];

        // Transforme les $userRating en données de rating avec details_produit
        foreach ($userRating as $rating) {
            $profile['rating'][] = [
                "id_produit" => (int)$rating['id_produit'],
                "rating" => (int)$rating['rating'],
                "details_produit" => [
                    "id_produit" => (int)$rating['id_produit'],
                    "prix" => (float)$rating['prix'],
                    "caracteristiques_formes" => explode(',', $rating['caracteristiques_formes']),
                    "caracteristiques_gouts" => explode(',', $rating['caracteristiques_gouts']),
                    "categorie" => explode(',', $rating['categorie'])
                ]
            ];
        }

        // Transforme les $userWishlist en données de wishlist avec details_produit
        foreach ($userWishlist as $wishlist) {
            $wishlistData = [
                "id_produit" => (int)$wishlist['id_produit_wishlist'],
                "details_produit" => [
                    "id_produit" => (int)$wishlist['id_produit_wishlist'],
                    "prix" => (float)$wishlist['prix_wishlist'],
                    "caracteristiques_formes" => explode(',', $wishlist['caracteristiques_formes_wishlist']),
                    "caracteristiques_gouts" => explode(',', $wishlist['caracteristiques_gouts_wishlist']),
                    "categorie" => explode(',', $wishlist['categorie_wishlist'])
                ]
            ];
            
            // Si la quantité est présente, ajoute-la
            if (isset($wishlist['quantite_souhaitee'])) {
                $wishlistData['quantite_souhaitee'] = (int)$wishlist['quantite_souhaitee'];
            }
            
            $profile['wish_liste'][] = $wishlistData;
        }

        // Transforme les $userPanier en données de panier avec details_produit
        foreach ($userPanier as $panier) {
            $profile['panier'][] = [
                "id_produit" => (int)$panier['id_produit'],
                "quantite" => (int)$panier['quantite_panier'],
                "details_produit" => [
                    "id_produit" => (int)$panier['id_produit'],
                    "prix" => (float)$panier['prix'],
                    "caracteristiques_formes" => explode(',', $panier['caracteristiques_formes']),
                    "caracteristiques_gouts" => explode(',', $panier['caracteristiques_gouts']),
                    "categorie" => explode(',', $panier['categorie'])
                ]
            ];
        }
        // Retourner le profil complet sous forme de tableau
        return $profile;
    }


    static public function getProductsReco($id_lang, $start, $limit, $orderBy, $orderWay, $productsIds, $id_category = false, $only_active = false)
	{
		if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
			die (Tools::displayError());
		if ($orderBy == 'id_product' OR	$orderBy == 'price' OR	$orderBy == 'date_add')
			$orderByPrefix = 'p';
		elseif ($orderBy == 'name')
			$orderByPrefix = 'pl';
		elseif ($orderBy == 'position')
			$orderByPrefix = 'c';
		$rq = Db::getInstance()->ExecuteS('
		SELECT p.*, pl.* , m.`name` AS manufacturer_name, s.`name` AS supplier_name
		FROM `'._DB_PREFIX_.'product` p
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product`)
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		LEFT JOIN `'._DB_PREFIX_.'supplier` s ON (s.`id_supplier` = p.`id_supplier`)'.
		($id_category ? 'LEFT JOIN `'._DB_PREFIX_.'category_product` c ON (c.`id_product` = p.`id_product`)' : '').'
		WHERE pl.`id_lang` = '.intval($id_lang).
		($id_category ? ' AND c.`id_category` = '.intval($id_category) : '').
		($only_active ? ' AND p.`active` = 1' : '').'
        AND p.`id_product` IN ('.$productsIds.')
		ORDER BY '.(isset($orderByPrefix) ? pSQL($orderByPrefix).'.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).
		($limit > 0 ? ' LIMIT '.intval($start).','.intval($limit) : '')
		);
		if($orderBy == 'price')
			Tools::orderbyPrice($rq,$orderWay);

		return ($rq);
	}

}
