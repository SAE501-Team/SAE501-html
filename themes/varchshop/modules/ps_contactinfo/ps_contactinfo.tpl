{*
* 2007-2015 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="block-contact col-md-2 links wrapper">
  <div class="hidden-sm-down">
    <h4 class="text-uppercase block-contact-title">{l s='Store information' d='Shop.Theme'}</h4>

      <div class="block-contact-item">
          <i class="material-icons">location_on</i>
          <span class="contact_infos_address">
              {$contact_infos.address.formatted nofilter}
          </span>
      </div>

      {if $contact_infos.phone}
          <div class="block-contact-item">
              <i class="material-icons">phone</i>
              <span class="contact_infos_phone">

                {l s='Call us: [1]%phone%[/1]'
                sprintf=[
                '[1]' => '<span>',
                '[/1]' => '</span>',
                '%phone%' => $contact_infos.phone
                ]
                d='Shop.Theme'
                }
              </span>
          </div>


      {/if}


      {if $contact_infos.email}
          <div class="block-contact-item">
              <i class="material-icons">email</i>
              <span class="contact_infos_email">

                  {l
                  s='Email: [1]%email%[/1]'
                  sprintf=[
                  '[1]' => '<span>',
                  '[/1]' => '</span>',
                  '%email%' => $contact_infos.email
                  ]
                  d='Shop.Theme'
                  }
              </span>
          </div>
      {/if}


      {if $contact_infos.fax}


          <div class="block-contact-item">
              <i class="material-icons"></i>
              <span class="contact_infos_fax">
                          {l
                          s='Fax: [1]%fax%[/1]'
                          sprintf=[
                          '[1]' => '<span>',
                          '[/1]' => '</span>',
                          '%fax%' => $contact_infos.fax
                          ]
                          d='Shop.Theme'
                          }
              </span>
          </div>

      {/if}

  </div>
  <div class="hidden-md-up">
    <div class="title">
      <a class="h3" href="{$urls.pages.stores}">{l s='Store information' d='Shop.Theme'}</a>
    </div>
  </div>
</div>