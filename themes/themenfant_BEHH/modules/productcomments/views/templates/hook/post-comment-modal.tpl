{** * Copyright since 2007 PrestaShop SA and Contributors * PrestaShop is an
International Registered Trademark & Property of PrestaShop SA * * NOTICE OF
LICENSE * * This source file is subject to the Academic Free License 3.0
(AFL-3.0) * that is bundled with this package in the file LICENSE.md. * It is
also available through the world-wide-web at this URL: *
https://opensource.org/licenses/AFL-3.0 * If you did not receive a copy of the
license and are unable to * obtain it through the world-wide-web, please send an
email * to license@prestashop.com so we can send you a copy immediately. * *
DISCLAIMER * * Do not edit or add to this file if you wish to upgrade PrestaShop
to newer * versions in the future. If you wish to customize PrestaShop for your
* needs please refer to https://devdocs.prestashop.com/ for more information. *
* @author PrestaShop SA and Contributors
<contact@prestashop.com>
  * @copyright Since 2007 PrestaShop SA and Contributors * @license
  https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0) *}

  <script type="text/javascript">
    var productCommentPostErrorMessage = '{l s='Sorry, your review cannot be posted.' d='Modules.Productcomments.Shop' js=1}';
    var productCommentMandatoryMessage = '{l s='Please choose a rating for your review.' d='Modules.Productcomments.Shop' js=1}';
    var ratingChosen = false;
  </script>

  <div
    id="post-product-comment-modal"
    class="modal fade product-comment-modal"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content !border-3 !border-black">
        <div class="modal-header">
          <p class="h2">
            {l s='Write your review' d='Modules.Productcomments.Shop'}
          </p>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="{l s='Close' d='Shop.Theme.Global'}"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form
            id="post-product-comment-form"
            action="{$post_comment_url nofilter}"
            method="POST"
          >
            <div class="row">
              <div class="col-sm-2">
                {if isset($product) && $product} {block name='product_flags'}
                <ul class="product-flags">
                  {foreach from=$product.flags item=flag}
                  <li class="product-flag {$flag.type}">{$flag.label}</li>
                  {/foreach}
                </ul>
                {/block} {block name='product_cover'}
                <div class="product-cover">
                  {if $product.cover}
                  <img
                    class="js-qv-product-cover"
                    src="{$product.cover.bySize.medium_default.url}"
                    alt="{$product.cover.legend}"
                    title="{$product.cover.legend}"
                    style="width: 100%"
                    itemprop="image"
                  />
                  {else}
                  <img
                    src="{$urls.no_picture_image.bySize.large_default.url nofilter}"
                    style="width: 100%"
                  />
                  {/if}
                </div>
                {/block} {/if}
              </div>
              <div class="col-sm-4">
                <p class="h3">{$product.name}</p>
                {block name='product_description_short'}
                <div itemprop="description">
                  {$product.description_short nofilter}
                </div>
                {/block}
              </div>
              <div class="col-sm-6">
                {if $criterions|@count > 0}
                <ul id="criterions_list">
                  {foreach from=$criterions item='criterion'}
                  <li>
                    <div class="criterion-rating">
                      <label>{$criterion.name|escape:'html':'UTF-8'}:</label>
                      <div
                        class="grade-stars"
                        data-grade="3"
                        data-input="criterion[{$criterion.id_product_comment_criterion}]"
                      ></div>
                    </div>
                  </li>
                  {/foreach}
                </ul>
                {/if}
              </div>
            </div>

            {if !$logged}
            <div class="row">
              <div class="col-sm-8 form-group flex flex-col group">
                <label
                  class="form-label"
                  for="comment_title"
                  class="text-black font-adlam text-base group-focus-within:text-be-bleu"
                  >{l s='Title' d='Modules.Productcomments.Shop'}<sup
                    class="required"
                    >*</sup
                  ></label
                >
                <input
                  id="comment_title"
                  name="comment_title"
                  type="text"
                  value=""
                  class="!bg-white !border-3 !border-black transition ease-out duration-500 hover:scale-[0.99] focus:!border-be-bleu focus:!outline-none"
                />
              </div>
              <div class="col-sm-4 form-group flex flex-col group">
                <label
                  class="form-label"
                  for="customer_name"
                  class="text-black font-adlam text-base group-focus-within:text-be-bleu"
                  >{l s='Your name' d='Modules.Productcomments.Shop'}<sup
                    class="required"
                    >*</sup
                  ></label
                >
                <input
                  id="customer_name"
                  name="customer_name"
                  type="text"
                  value=""
                  class="!bg-white !border-3 !border-black transition ease-out duration-500 hover:scale-[0.99] focus:!border-be-bleu focus:!outline-none"
                />
              </div>
            </div>
            {else}
            <div class="form-group flex flex-col group">
              <label
                class="form-label"
                for="comment_title"
                class="text-black font-adlam text-base group-focus-within:text-be-bleu"
                >{l s='Title' d='Modules.Productcomments.Shop'}<sup
                  class="required"
                  >*</sup
                ></label
              >
              <input
                id="comment_title"
                name="comment_title"
                type="text"
                value=""
                class="!bg-white !border-3 !border-black transition ease-out duration-500 hover:scale-[0.99] focus:!border-be-bleu focus:!outline-none"
              />
            </div>
            {/if}
            <div class="form-group flex flex-col group">
              <label
                class="form-label"
                for="comment_content"
                class="text-black font-adlam text-base group-focus-within:text-be-bleu"
                >{l s='Review' d='Modules.Productcomments.Shop'}<sup
                  class="required"
                  >*</sup
                ></label
              >
              <textarea
                id="comment_content"
                name="comment_content"
                class="!bg-white !border-3 !border-black transition ease-out duration-500 hover:scale-[0.99] focus:!border-be-bleu focus:!outline-none"
              ></textarea>
            </div>
            {hook h='displayGDPRConsent' mod='psgdpr' id_module=$id_module}

            <div class="row">
              <div class="col-sm-6">
                <p class="required">
                  <sup>*</sup> {l s='Required fields'
                  d='Modules.Productcomments.Shop'}
                </p>
              </div>
              <div class="col-sm-6 post-comment-buttons">
                <button
                  type="button"
                  class="btn btn-comment-big  !text-be-rose hover:!text-be-bleu !border-2 !border-be-rose transition ease-out duration-500 hover:scale-[1.04] hover:!border-be-bleu !bg-white"
                  data-dismiss="modal"
                >
                  {l s='Cancel' d='Modules.Productcomments.Shop'}
                </button>
                <button
                  type="submit"
                  class="btn btn-comment btn-comment-big btn btn-primary !bg-be-rose transition ease-out duration-500 hover:scale-[1.04] hover:!bg-be-bleu"
                >
                  {l s='Send' d='Modules.Productcomments.Shop'}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {* Comment posted modal *} {if $moderation_active} {$comment_posted_message =
  {l s='Your comment has been submitted and will be available once approved by a
  moderator.' d='Modules.Productcomments.Shop'}} {else} {$comment_posted_message
  = {l s='Your comment has been added!' d='Modules.Productcomments.Shop'}} {/if}
  {include file='module:productcomments/views/templates/hook/alert-modal.tpl'
  modal_id='product-comment-posted-modal' modal_title={l s='Review sent'
  d='Modules.Productcomments.Shop'} modal_message=$comment_posted_message } {*
  Comment post error modal *} {include
  file='module:productcomments/views/templates/hook/alert-modal.tpl'
  modal_id='product-comment-post-error' modal_title={l s='Your review cannot be
  sent' d='Modules.Productcomments.Shop'} icon='error' }
</contact@prestashop.com>
