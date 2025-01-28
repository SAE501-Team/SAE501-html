<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminUserrecommandationController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;  // Utiliser le CSS/JS de l'admin
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
        // Assigner l'URL actuelle pour le formulaire
        $currentUrl = $this->context->link->getAdminLink('AdminUserrecommandation', true);
        $this->context->smarty->assign('current', $currentUrl);

        PrestaShopLogger::addLog('Je suis dans le initContent de admin', 1, null, 'CustomTest');

        $this->setTemplate('configure.tpl');
    }

    // Cette méthode peut être appelée lors du submit du formulaire
    public function postProcess()
    {
        PrestaShopLogger::addLog('Je passe dans la fonction postProcess de admin', 1, null, 'CustomTest');
        if (Tools::isSubmit('run_python_script')) {
            $donneeModelUser = $this->creat_donnee_model();
            $donneeModelProduct = $this->cherche_donnee_product();
            PrestaShopLogger::addLog('Données des utilisateurs récupérées : ' . print_r($donneeModelUser, true), 1, null, 'CustomTest');
            // Exécuter ton script Python ici
            $this->runPythonScript($donneeModelUser,$donneeModelProduct);
        }
    }

    // Méthode pour exécuter le script Python
    private function runPythonScript($json_donnees_user, $json_donnees_product)
    {
        PrestaShopLogger::addLog('Je suis dans runPythonScript', 1, null, 'CustomTest');
        $dynamicValues = $this->getDynamicValues();

        $dynamicValuesJson = json_encode($dynamicValues);
        PrestaShopLogger::addLog('Les value dynamic : ' . print_r($dynamicValuesJson, true), 1, null, 'CustomTest');
        // Exemple d'exécution d'un script Python
        $command = escapeshellcmd(
            'python3 ' . _PS_MODULE_DIR_ . 'userrecommandation/model_ia/model.py ' .
            $json_donnees_user . ' ' .
            $json_donnees_product . ' ' .
            $dynamicValuesJson
        );

        $output = [];
        $resultCode = null;
        exec($command, $output, $resultCode);
        PrestaShopLogger::addLog('Commande exécutée : ' . $command, 1, null, 'Userrecommandation');
        PrestaShopLogger::addLog('Output brut : ' . implode("\n", $output), 1, null, 'Userrecommandation');
        PrestaShopLogger::addLog('Code de retour : ' . $resultCode, 1, null, 'Userrecommandation');

        // Ajouter un log dans PrestaShop
        if ($resultCode === 0) {
            PrestaShopLogger::addLog('Résultats de l\'exécution du script Python : ' . implode("\n", $output), 1, null, 'Userrecommandation');
        } else {
            PrestaShopLogger::addLog('Erreur lors de l\'exécution du script Python.', 3, null, 'Userrecommandation');
        }
    }

    public function creat_donnee_model()
    {
        // Connexion à la base
        $db = Db::getInstance();

        // Requêtes SQL pour récupérer les informations des utilisateurs
        $usersInfo = $db->executeS("
            SELECT
                c.id_customer AS id_customer,
                c.birthday AS date_de_naissance,
                c.id_gender,
                ur.type_consommation,
                ur.souhaitez_cereales_originales,
                ur.gout_prefere,
                ur.forme_favorite,
                ur.consommation_pour_qui
            FROM
                ps_customer c
                LEFT JOIN ps_user_recommandation ur ON c.id_customer = ur.id_customer
            WHERE
                ur.type_consommation IS NOT NULL;
        ");

        // Requêtes SQL pour récupérer les produits noté par l'utilisateur
        $usersRating = $db->executeS("
            SELECT
                pc.id_product AS id_produit,
                pc.id_customer AS id_utilisateur, -- Ajout de l'ID utilisateur
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
                AND cl.id_category IN (11, 12, 13, 14)
            GROUP BY
                pc.id_product, pc.id_customer;
        ");

        // Requêtes SQL pour récupérer les produits dans la wishlist de l'utilisateur
        $usersWishlist = $db->executeS("
            SELECT
                wp.id_product AS id_produit_wishlist,
                w.id_customer AS id_utilisateur, -- Ajout de l'ID utilisateur
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
                cl.id_category IN (11, 12, 13, 14) -- Limiter aux catégories spécifiées
            GROUP BY
                w.id_customer, wp.id_product; -- Groupement par produit et utilisateur
        ");

        // Requêtes SQL pour récupérer les produits dans le panier de l'utilisateur
        $usersPanier = $db->executeS("
            SELECT
                cd.id_product AS id_produit,
                c.id_customer AS id_utilisateur, -- ID utilisateur
                cd.quantity AS quantite_panier, -- Quantité de produit dans le panier
                p.price AS prix, -- Prix du produit
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
                cl.id_category IN (11, 12, 13, 14) -- Catégories spécifiques
            GROUP BY
                c.id_customer, cd.id_product; -- Groupement par produit et utilisateur
        ");

        $resultat = $this->generateCustomersProfile($usersInfo, $usersRating, $usersWishlist, $usersPanier);
        // Convertir en JSON
        $jsonResultat = json_encode($resultat);
        return $jsonResultat;
    }

    private function generateCustomersProfile($usersInfo, $usersRating, $usersWishlist, $usersPanier)
    {
        $users = [];

        // Indexer les utilisateurs par leur ID pour une gestion plus simple
        foreach ($usersInfo as $user) {
            $idUser = $user['id_customer'];
            $users[$idUser] = [
                'date_de_naissance' => $user['date_de_naissance'],
                'sexe' => $user['id_gender'],
                'type_consommation' => $user['type_consommation'],
                'souhaitez_cereales_originales' => (bool)$user['souhaitez_cereales_originales'],
                'gout_prefere' => $user['gout_prefere'],
                'forme_favorite' => $user['forme_favorite'],
                'consommation_pour_qui' => $user['consommation_pour_qui'],
                'rating' => [],
                'wish_liste' => [],
                'panier' => [],
            ];
        }

        // Ajouter les produits notés par utilisateur
        foreach ($usersRating as $rating) {
            $idUser = $rating['id_utilisateur'];
            if (isset($users[$idUser])) {
                $users[$idUser]['rating'][] = [
                    'id_produit' => $rating['id_produit'],
                    'rating' => $rating['rating'],
                    'details_produit' => [
                        'id_produit' => $rating['id_produit'],
                        'prix' => (float)$rating['prix'],
                        'caracteristiques_formes' => $this->splitFeatures($rating['caracteristiques_formes']),
                        'caracteristiques_gouts' => $this->splitFeatures($rating['caracteristiques_gouts']),
                        'categorie' => $this->splitFeatures($rating['categorie']),
                    ],
                ];
            }
        }

        // Ajouter les produits de la wishlist par utilisateur
        foreach ($usersWishlist as $wishlist) {
            $idUser = $wishlist['id_utilisateur'];
            if (isset($users[$idUser])) {
                $users[$idUser]['wish_liste'][] = [
                    'id_produit' => $wishlist['id_produit_wishlist'],
                    'quantite_souhaitee' => 1, // Par défaut, ici 1 (ajuster si nécessaire)
                    'details_produit' => [
                        'id_produit' => $wishlist['id_produit_wishlist'],
                        'prix' => (float)$wishlist['prix_wishlist'],
                        'caracteristiques_formes' => $this->splitFeatures($wishlist['caracteristiques_formes_wishlist']),
                        'caracteristiques_gouts' => $this->splitFeatures($wishlist['caracteristiques_gouts_wishlist']),
                        'categorie' => $this->splitFeatures($wishlist['categorie_wishlist']),
                    ],
                ];
            }
        }

        // Ajouter les produits du panier par utilisateur
        foreach ($usersPanier as $panier) {
            $idUser = $panier['id_utilisateur'];
            if (isset($users[$idUser])) {
                $users[$idUser]['panier'][] = [
                    'id_produit' => $panier['id_produit'],
                    'quantite' => (int)$panier['quantite_panier'],
                    'details_produit' => [
                        'id_produit' => $panier['id_produit'],
                        'prix' => (float)$panier['prix'],
                        'caracteristiques_formes' => $this->splitFeatures($panier['caracteristiques_formes']),
                        'caracteristiques_gouts' => $this->splitFeatures($panier['caracteristiques_gouts']),
                        'categorie' => $this->splitFeatures($panier['categorie']),
                    ],
                ];
            }
        }

        // Retourner les utilisateurs sous forme de tableau structuré
        return ['users' => array_values($users)];
    }

    // Fonction utilitaire pour splitter les données séparées par des virgules
    private function splitFeatures($features)
    {
        return $features ? array_map('trim', explode(',', $features)) : [];
    }

    public function cherche_donnee_product()
    {
        // Connexion à la base
        $db = Db::getInstance();

        // Requêtes SQL pour récupérer les informations des utilisateurs
        $productsInfo = $db->executeS("
            SELECT
                p.id_product AS id_produit,
                p.price AS prix,
                -- Récupération des formes
                GROUP_CONCAT(DISTINCT CASE WHEN f.id_feature = 2 THEN fvl.value END) AS caracteristiques_formes,
                -- Récupération des goûts
                GROUP_CONCAT(DISTINCT CASE WHEN f.id_feature = 4 THEN fvl.value END) AS caracteristiques_gouts,
                -- Récupération des catégories
                GROUP_CONCAT(DISTINCT cl.name) AS categorie
            FROM
                ps_product p
            -- Jointure avec les catégories
            LEFT JOIN ps_category_product cp ON p.id_product = cp.id_product
            LEFT JOIN ps_category_lang cl ON cp.id_category = cl.id_category AND cl.id_lang = 1
            -- Jointure avec les caractéristiques
            LEFT JOIN ps_feature_product fp ON p.id_product = fp.id_product
            LEFT JOIN ps_feature f ON fp.id_feature = f.id_feature
            LEFT JOIN ps_feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value AND fvl.id_lang = 1
            WHERE
                cp.id_category IN (11, 12, 13, 14) -- Filtrer les catégories
            GROUP BY
                p.id_product;

        ");

        $resultat = $this->generateProductsProfile($productsInfo);
        // Convertir en JSON
        $jsonResultat = json_encode($resultat);
        return $jsonResultat;
    }

    private function generateProductsProfile($productsInfo)
    {
        $resultat = ['products' => []];

        foreach ($productsInfo as $product) {
            $resultat['products'][] = [
                'id_produit' => (int) $product['id_produit'],
                'prix' => (float) $product['prix'],
                'caracteristiques_formes' => !empty($product['caracteristiques_formes']) 
                    ? array_filter(explode(',', $product['caracteristiques_formes'])) 
                    : [],
                'caracteristiques_gouts' => !empty($product['caracteristiques_gouts']) 
                    ? array_filter(explode(',', $product['caracteristiques_gouts'])) 
                    : [],
                'categorie' => !empty($product['categorie']) 
                    ? array_filter(explode(',', $product['categorie'])) 
                    : [],
            ];
        }

        return $resultat;
    }

    public function getDynamicValues()
    {
        $db = Db::getInstance();

        $tastes = $db->executeS("
            SELECT 
                DISTINCT fvl.value AS gout
            FROM 
                ps_feature f
            LEFT JOIN 
                ps_feature_value fv ON f.id_feature = fv.id_feature
            LEFT JOIN 
                ps_feature_value_lang fvl ON fv.id_feature_value = fvl.id_feature_value AND fvl.id_lang = 1
            WHERE 
                f.id_feature = 4;
        ");

        $shapes = $db->executeS("
            SELECT 
                DISTINCT fvl.value AS forme
            FROM 
                ps_feature f
            LEFT JOIN 
                ps_feature_value fv ON f.id_feature = fv.id_feature
            LEFT JOIN 
                ps_feature_value_lang fvl ON fv.id_feature_value = fvl.id_feature_value AND fvl.id_lang = 1
            WHERE 
                f.id_feature = 2;
        ");

        $categories = $db->executeS("
            SELECT 
                cl.name AS categorie
            FROM 
                ps_category_lang cl
            WHERE 
                cl.id_category IN (11, 12, 13, 14)
                AND cl.id_lang = 1; -- Langue française
        ");

        return [
            'tastes' => array_column($tastes, 'gout'),
            'shapes' => array_column($shapes, 'forme'),
            'categories' => array_column($categories, 'categorie')
        ];
    }


}

