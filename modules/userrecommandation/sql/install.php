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
* to license@prestashop.com so we can send you a copy immediately.
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
$sql = array();

$sql[] = "CREATE TABLE IF NOT EXISTS `ps_user_recommandation` (
    `id_customer` INT(10) UNSIGNED NOT NULL,
    `type_consommation` ENUM('sportif', 'gourmand', 'bio') NULL,
    `souhaitez_cereales_originales` ENUM('oui', 'non') NULL,
    `gout_prefere` ENUM('chocolat', 'nature', 'sucree', 'miel', 'caramel', 'speculoos', 'fruits rouges') NULL,
    `forme_favorite` ENUM('boule', 'triangle', 'cube', 'petale', 'donut', 'etoile') NULL,
    `consommation_pour_qui` ENUM('personnel', 'enfants', 'les deux') NULL,
    PRIMARY KEY (`id_customer`),
    FOREIGN KEY (`id_customer`) REFERENCES `ps_customer` (`id_customer`) ON DELETE CASCADE
);";


$sql[] = "CREATE TABLE IF NOT EXISTS `ps_produit_recommander` (
    `id_customer` INT(10) UNSIGNED NOT NULL,
    `id_product` INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_customer`, `id_product`),
    FOREIGN KEY (`id_customer`) REFERENCES `ps_customer` (`id_customer`) ON DELETE CASCADE,
    FOREIGN KEY (`id_product`) REFERENCES `ps_product` (`id_product`) ON DELETE CASCADE
);";


foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

return true;


