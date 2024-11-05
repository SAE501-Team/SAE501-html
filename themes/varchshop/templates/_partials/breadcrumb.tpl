{**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2016 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{hook h="displayLeftMenuProductPage"}
{if Context::getContext()->controller->php_self !== 'index'}
    <nav data-depth="{$breadcrumb.count}" class="breadcrumb hidden-sm-down">
        <ol itemscope itemtype="http://schema.org/BreadcrumbList">
            {foreach from=$breadcrumb.links key=key item=path name=breadcrumb}
                <li  {if $breadcrumb.count !== ($key+1)}itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"{/if}>
                    {if $breadcrumb.count !== ($key+1)}
                        <a itemprop="item" href="{$path.url}">
                    {/if}

                       <span {if $breadcrumb.count !== ($key+1)}itemprop="name"{/if}>{$path.title}</span>

                    {if $breadcrumb.count !== ($key+1)}
                        </a>
                        <meta itemprop="position" content="{$smarty.foreach.breadcrumb.iteration}">
                    {/if}
                </li>
            {/foreach}
        </ol>
    </nav>
{/if}
