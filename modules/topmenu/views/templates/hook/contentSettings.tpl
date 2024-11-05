<input name="section" value="{$section|escape:'htmlall':'UTF-8'}" type="hidden">
<div class="select_content">
    <div class="title_selection_block">
        {$titleSelection nofilter}
        <div style="clear: both"></div>
    </div>
    <ul>
        <li>
            <div class="link_block">
                <input type="radio" id="links_val" name="select_type" {if $config['val'] == 'links'} checked {/if} value="links">
                <label for="links_val">{l s='Links' mod='topmenu'}</label>
            </div>
            <div class="content_bl links_bl formLinksBlock {if $config['val'] == 'links'} content_bl_active {/if}">
                {$renderLinksBlock nofilter}
                {html_entity_decode($linkList|escape:'htmlall':'UTF-8')}
            </div>
        </li>
        <li>
            <div class="link_block">
                <input type="radio" id="text_val" name="select_type" {if $config['val'] == 'text'} checked {/if} value="text">
                <label for="text_val">{l s='Description' mod='topmenu'}</label>
            </div>
            <div class="content_bl textareaBlock {if $config['val'] == 'text'} content_bl_active {/if}">
                {$textareaBlock nofilter}
            </div>
        </li>
        <li>
            <div class="link_block">
                <input type="radio" id="categories_val" name="select_type" {if $config['val'] == 'categories'} checked {/if} value="categories">
                <label for="categories_val">{l s='Categories' mod='topmenu'}</label>
            </div>
            <div class="content_bl categoriesBlock {if $config['val'] == 'categories'} content_bl_active {/if}">
                {$categoriesBlock nofilter}
            </div>
        </li>

        {if $section != 'botton'}
        <li>
            <div class="link_block">
                <input type="radio" id="products_val" name="select_type" {if $config['val'] == 'products'} checked {/if} value="products">
                <label for="products_val">{l s='Products' mod='topmenu'}</label>
            </div>
            <div class="content_bl {if $config['val'] == 'products'} content_bl_active {/if}">

                <div id="product-tab-content-wait" style="display:none">
                    <div id="loading"><i class="icon-refresh icon-spin"></i>&nbsp;{l mod='topmenu' s='Loading...'}</div>
                </div>
                <div class="panel form-horizontal">
                    <div class="panel-heading">
                        <i class="icon-plus-sign-alt"></i> {l s='ADD PRODUCTS' mod='topmenu'}
                    </div>
                    <div class="form-wrapper">
                        <div class="form-group">
                            <div id="control-label col-lg-3">
                                <div style="float: left">
                                    <input id="attendee" name="AttendeeId" type="text" value="" placeholder="{l s='Search for a product' mod='topmenu'}"/>
                                </div>
                                <div class="col-lg-2 product-pack-button">
                                    <button type="button" id="add_products_item" class="btn btn-default">
                                        <i class="icon-plus-sign-alt"></i> {l s='Add this product' mod='topmenu'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.form-wrapper -->
                </div>
                <div class="add_products_list ">
                    {html_entity_decode($list|escape:'htmlall':'UTF-8')}
                </div>


            </div>
        </li>
        {/if}
        <li>
            <div class="link_block">
                <input type="radio" id="cms_val" name="select_type" {if $config['val'] == 'cms'} checked {/if} value="cms">
                <label for="cms_val">{l s='Cms Page' mod='topmenu'}</label>
            </div>
            <div class="content_bl {if $config['val'] == 'cms'} content_bl_active {/if}">
                <div class="panel form-horizontal">
                    <div class="panel-heading">
                        <i class="icon-plus-sign-alt"></i> {l s='ADD CMS PAGE' mod='topmenu'}
                    </div>
                    <div class="form-wrapper">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="fixed-width-xs"><span class="title_box">{l s='Selected' mod='topmenu'}</span></th>
                                <th><span class="title_box">{l s='Cms Name' mod='topmenu'}</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$cms_all item=cms}
                                <tr>
                                    <td><input type="checkbox" id="cms_{$cms['id_cms']|escape:'htmlall':'UTF-8'}" class="cmsCheckBox" name="check_cms_{$cms['id_cms']|escape:'htmlall':'UTF-8'}" {if $cms['is_selected'] == true}checked="checked"{/if} value="{$cms['id_cms']|escape:'htmlall':'UTF-8'}" /></td>
                                    <td><label for="cms_{$cms['id_cms']|escape:'htmlall':'UTF-8'}">{$cms['meta_title']|escape:'htmlall':'UTF-8'}</label></td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="link_block">
                <input type="radio" id="video_val" name="select_type" {if $config['val'] == 'video'} checked {/if} value="video">
                <label for="video_val">{l s='Video' mod='topmenu'}</label>
            </div>
            <div class="content_bl {if $config['val'] == 'video'} content_bl_active {/if}">
                <div class="panel form-horizontal">
                    <div class="panel-heading">
                        <i class="icon-plus-sign-alt"></i> {l s='ADD VIDEO CODE' mod='topmenu'}
                    </div>
                    <div class="form-wrapper">

                        <textarea class="videoCodeBlock" id="videoCodeBlock">
                            {if isset($video_code) && $video_code}
                                {html_entity_decode($video_code|escape:'htmlall':'UTF-8')}
                            {/if}
                        </textarea>
                        <div class="save_video_block">
                            <button type="button" id="save_video_code" class="btn btn-default">{l s='Save' mod='topmenu'}</button>
                            <div style="clear: both"></div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="link_block">
                <input type="radio" id="manufacturers_val" name="select_type" {if $config['val'] == 'manufacturers'} checked {/if} value="manufacturers">
                <label for="manufacturers_val">{l s='Manufacturers' mod='topmenu'}</label>
            </div>
            <div class="content_bl {if $config['val'] == 'manufacturers'} content_bl_active {/if}">
                <div class="panel form-horizontal">
                    <div class="panel-heading">
                        <i class="icon-plus-sign-alt"></i> {l s='ADD MANUFACTURERS' mod='topmenu'}
                    </div>
                    <div class="form-wrapper">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="fixed-width-xs"><span class="title_box">{l s='Selected' mod='topmenu'}</span></th>
                                <th><span class="title_box">{l s='Manufacturer Name' mod='topmenu'}</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$manufacturers item=manufacturer}
                                <tr>
                                    <td><input type="checkbox" id="manufacturer_{$manufacturer['id_manufacturer']|escape:'htmlall':'UTF-8'}" class="manufacturerCheckBox" name="check_manufacturer_{$manufacturer['id_manufacturer']|escape:'htmlall':'UTF-8'}" {if $manufacturer['is_selected'] == true}checked="checked"{/if} value="{$manufacturer['id_manufacturer']|escape:'htmlall':'UTF-8'}" /></td>
                                    <td><label for="manufacturer_{$manufacturer['id_manufacturer']|escape:'htmlall':'UTF-8'}">{$manufacturer['name']|escape:'htmlall':'UTF-8'}</label></td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="link_block">
                <input type="radio" id="suppliers_val" name="select_type" {if $config['val'] == 'suppliers'} checked {/if} value="suppliers">
                <label for="suppliers_val">{l s='Suppliers' mod='topmenu'}</label>
            </div>
            <div class="content_bl {if $config['val'] == 'suppliers'} content_bl_active {/if}">

                <div class="panel form-horizontal">
                    <div class="panel-heading">
                        <i class="icon-plus-sign-alt"></i> {l s='ADD SUPPLIERS' mod='topmenu'}
                    </div>
                    <div class="form-wrapper">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="fixed-width-xs"><span class="title_box">{l s='Selected' mod='topmenu'}</span></th>
                                <th><span class="title_box">{l s='Supplier Name' mod='topmenu'}</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$suppliers item=supplier}
                                <tr>
                                    <td><input type="checkbox" id="supplier_{$supplier['id_supplier']|escape:'htmlall':'UTF-8'}" class="supplierCheckBox" name="check_supplier_{$supplier['id_supplier']|escape:'htmlall':'UTF-8'}" {if $supplier['is_selected'] == true}checked="checked"{/if} value="{$supplier['id_supplier']|escape:'htmlall':'UTF-8'}" /></td>
                                    <td><label for="supplier_{$supplier['id_supplier']|escape:'htmlall':'UTF-8'}">{$supplier['name']|escape:'htmlall':'UTF-8'}</label></td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </li>
    </ul>
    <div class="button_back">
        <a class="btn btn-default button_back_positions" >
            <i class="process-icon-back"></i> {l s='Back' mod='topmenu'}
        </a>
    </div>


</div>

