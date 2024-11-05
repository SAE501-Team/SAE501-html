<input type="hidden" class="id_shop" value="{$id_shop|escape:'htmlall':'UTF-8'}">
<input type="hidden" class="id_lang" value="{$id_lang|escape:'htmlall':'UTF-8'}">
<input name="top_menu_token" value="{$token|escape:'htmlall':'UTF-8'}" type="hidden">
<div class="base_content">
    <div class="left_selection">
        <div class="btn-group pull-right">
            <a  title="{l s='Edit' mod='topmenu'}" class="edit btn btn-default">
                <i class="icon-pencil"></i>{l s='Edit' mod='topmenu'}
            </a>
        </div>
        <input type="checkbox" class="checkbox_left_selection" id="checkbox_left_selection" {if isset($config['left_selection']) && $config['left_selection']} checked {/if} value="1">
        <label for="checkbox_left_selection">{l s='Left Section' mod='topmenu'}</label>
    </div>
    <div class="main_selection">
        <div class="btn-group pull-right">
            <a  title="{l s='Edit' mod='topmenu'}" class="edit btn btn-default">
                <i class="icon-pencil"></i>{l s='Edit' mod='topmenu'}
            </a>
        </div>
        <input type="checkbox" class="checkbox_main_selection" id="checkbox_main_selection" {if isset($config['main_selection']) && $config['main_selection']} checked {/if}  value="1">
        <label for="checkbox_main_selection">{l s='Main Section' mod='topmenu'}</label>
    </div>
    <div class="right_selection">
        <div class="btn-group pull-right">
            <a  title="{l s='Edit' mod='topmenu'}" class="edit btn btn-default">
                <i class="icon-pencil"></i>{l s='Edit' mod='topmenu'}
            </a>
        </div>
        <input type="checkbox" class="checkbox_right_selection" id="checkbox_right_selection" {if isset($config['right_selection']) && $config['right_selection']} checked {/if}  value="1">
        <label for="checkbox_right_selection">{l s='Right Section' mod='topmenu'}</label>
    </div>
    <div class="botton_selection">
        <div class="btn-group pull-right">
            <a  title="{l s='Edit' mod='topmenu'}" class="edit btn btn-default">
                <i class="icon-pencil"></i>{l s='Edit' mod='topmenu'}
            </a>
        </div>
        <input type="checkbox" class="checkbox_botton_selection" id="checkbox_botton_selection" {if isset($config['botton_selection']) && $config['botton_selection']} checked {/if}  value="1">
        <label for="checkbox_botton_selection">{l s='Bottom Section' mod='topmenu'}</label>
    </div>
</div>
