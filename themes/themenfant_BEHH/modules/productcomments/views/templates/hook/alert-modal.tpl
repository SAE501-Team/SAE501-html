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

{$icon = $icon|default:'check_circle'}
{$modal_message = $modal_message|default:''}

<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function() {
    const alertModal = $('#{$modal_id}');
    alertModal.on('hidden.bs.modal', function () {
      alertModal.modal('hide');
    });
  });
</script>

<div id="{$modal_id}" class="modal fade product-comment-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content !border-3 !border-black">
      <div class="modal-header">
        <p class="h2">
          <i class="material-icons {$icon}" data-icon="{$icon}"></i>
          {$modal_title}
        </p>
      </div>
      <div class="modal-body">
        <div id="{$modal_id}-message">
          {$modal_message}
        </div>
        <div class="post-comment-buttons">
          <button type="button" class="btn btn-comment btn-comment-huge btn-primary !bg-be-rose transition ease-out duration-500 hover:scale-[1.04] hover:!bg-be-bleu" data-dismiss="modal">
            {l s='OK' d='Modules.Productcomments.Shop'}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
