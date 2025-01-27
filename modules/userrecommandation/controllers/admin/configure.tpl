{*
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
*}

<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='Outil de recommandation BEHH' mod='userrecommandation'}</h3>
	<p>
		<strong>{l s='Grâce à l\'IA machine learning, crée une recommandation performante !' mod='userrecommandation'}</strong><br />
		{l s='Ce module ajoute permet d\'ajouter un formulaire pour faire vos choix de préférence en tant que consommateur.' mod='userrecommandation'}<br />
		{l s='Grâce à un modèle, vous pourrez recommander et afficher les produits les plus adaptés en fonction du profil de vos utilisateurs.' mod='userrecommandation'}
	</p>
	<br />
	<p>
		{l s='Ici vous pouvez lancer l\'entrainement du modèle pour qu\'il colle au mieux au profil de vos clients.' mod='userrecommandation'}
	</p>
</div>

<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Entrainer le modèle' mod='userrecommandation'}</h3>
	<p>
		&raquo; {l s='En cliquant sur ce bouton vous allez entraîner le modèle avec les données actuel de votre boutique' mod='userrecommandation'} :
		<form action="{$current}&run_python_script=1" method="post">
			<button type="submit" class="btn btn-primary" style="background-color: #7BAAF7;" onmouseover="this.style.backgroundColor='#5f97f2';" onmouseout="this.style.backgroundColor='#7BAAF7';">Entrainer modèle</button>
		</form>
	</p>
</div>
