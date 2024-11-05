<div class="productsBlockFeatured">
    <div id="product-tab-content-wait" style="display:none">
        <div id="loading"><i class="icon-refresh icon-spin"></i>&nbsp;{l s='Loading...' mod='blockhomefeatured'}</div>
    </div>
    <div class="panel form-horizontal">
        <div class="panel-heading">
            <i class="icon-plus-sign-alt"></i> {l s='ADD PRODUCT' mod='blockhomefeatured'}
        </div>
        <div class="form-wrapper">
            <div class="form-group">
                <div id="control-label col-lg-3">
                    <div style="float: left">
                        <input id="attendee_home" name="AttendeeId" type="text" value="" placeholder="{l s='Search for a product' mod='blockhomefeatured'}"/>
                    </div>
                    <div class="col-lg-2 product-pack-button-menu">
                        <button type="button" id="add_products_item_featured" class="btn btn-default">
                            <i class="icon-plus-sign-alt"></i> {l s='Add this product' mod='blockhomefeatured'}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel form-horizontal added_products_list">
        <div class="panel-heading">
            <i class="icon-plus-sign-alt"></i> {l s='ADDED PRODUCTS' mod='blockhomefeatured'}
        </div>
        <div class="form-wrapper">
            {html_entity_decode($content|escape:'htmlall':'UTF-8')}
        </div>
    </div>
</div>