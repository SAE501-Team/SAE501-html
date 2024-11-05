
<div class="form-group">
    <div id="control-label col-lg-3">
        <div style="float: left">
            <input class="attendee" id="attendee" name="AttendeeId" type="text" value="" placeholder="{l s='Search for a product' mod='freecall'}"/>

        </div>
        <div class="col-lg-2 product-pack-button">
            <button type="button" id="add_products_item" class="btn btn-default">
                <i class="icon-plus-sign-alt"></i> {l s='Add this product' mod='freecall'}
            </button>
            <input id="productIds" name="productIds"  type="hidden" value="{if isset($ids) && $ids}{$ids|escape:'htmlall':'UTF-8'}{/if}" />

        </div>
    </div>
</div>
{html_entity_decode($list|escape:'htmlall':'UTF-8')}
