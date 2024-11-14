{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{extends file='page.tpl'}

{block name='page_title'}
  {l s='Log in to your account' d='Shop.Theme.Customeraccount'}
{/block}

{block name='page_content'}
{var_dump($urls)}
    {block name='login_form_container'}
      <p class="no-account"><strong>{l s='Login with demo account:' d='Shop.Theme.Customeraccount'}</strong><br><br>demo@demo.fr<br>demodemo</p>
      <section class="login-form">
        {render file='customer/_partials/login-form.tpl' ui=$login_form}
      </section>
      <hr/>
      {block name='display_after_login_form'}
        {hook h='displayCustomerLoginFormAfter'}
      {/block}
      <div class="no-account">
        <a href="{$urls.pages.register}" data-link-action="display-register-form">
          {l s='No account? Create one here' d='Shop.Theme.Customeraccount'}
        </a>
      </div>
    {/block}
    <script src="{$urls.child_js_url}/custom-login.js"></script>
{/block}
