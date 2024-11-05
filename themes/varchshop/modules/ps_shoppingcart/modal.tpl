<div id="blockcart-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title h6 text-xs-center" id="myModalLabel">{l s='Product successfully added to your shopping cart' d='Shop.Theme.Checkout'}</h4>
      </div>
      <div class="modal-body">
        <div class="">
          <div class="modal-body-left divide-right">
            <div class="">
              <div class="product-image-modal">
                <img class="product-image" src="{$product.cover.medium.url}" alt="{$product.cover.legend}" title="{$product.cover.legend}" itemprop="image">
              </div>
            </div>
          </div>
          <div class="modal-body-right">
            <div class="cart-content">
              <div class="cart-content-product">
                <h6 class="h6 product-name">{$product.name}</h6>
                {foreach from=$product.attributes item="property_value" key="property"}
                  <div class="cart-line-item" >
                    <span class="label">{$property}:</span>
                    <span class="value">{$property_value}</span>
                  </div>
                {/foreach}
                <div class="cart-line-item" >
                  <span class="label">{l s='Quantity:' d='Shop.Theme.Checkout'}</span>
                  <span class="value">{$product.cart_quantity}</span>
                </div>
              </div>

              {if $cart.products_count > 1}
                <p class="cart-products-count">{l s='There are %products_count% items in your cart.' sprintf=['%products_count%' => $cart.products_count] d='Shop.Theme.Checkout'}</p>
              {else}
                <p class="cart-products-count">{l s='There is %product_count% item in your cart.' sprintf=['%product_count%' =>$cart.products_count] d='Shop.Theme.Checkout'}</p>
              {/if}


              <div class="cart-content-all">
                <div class="cart-line-item" >
                  <span class="label">{l s='Total products:' d='Shop.Theme.Checkout'}</span>
                  <span class="value">{$cart.subtotals.products.value}</span>
                </div>
                <div class="cart-line-item" >
                  <span class="label">{l s='Total shipping:' d='Shop.Theme.Checkout'}</span>
                  <span class="value">{$cart.subtotals.shipping.value} {hook h='displayCheckoutSubtotalDetails' subtotal=$cart.subtotals.shipping}</span>
                </div>

                {if $cart.subtotals.tax}
                  <div class="cart-line-item" >
                    <span class="label">{$cart.subtotals.tax.label}:</span>
                    <span class="value">{$cart.subtotals.tax.value}</span>
                  </div>
                {/if}

                <div class="cart-line-item" >
                  <span class="label">{l s='Total:' d='Shop.Theme.Checkout'}</span>
                  <span class="value">{$cart.totals.total.value} {$cart.labels.tax_short}</span>
                </div>
              </div>


              <div class="cart-content-button">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Continue shopping' d='Shop.Theme.Actions'}</button>
                <a href="{$cart_url}" class="btn btn-primary btn-primary-checkout">{l s='Proceed to checkout' d='Shop.Theme.Actions'}</a>
              </div>

            </div>
          </div>
          <div style="clear: both"></div>
        </div>
      </div>
    </div>
  </div>
</div>
