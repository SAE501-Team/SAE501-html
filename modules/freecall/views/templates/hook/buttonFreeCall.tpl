<div class="freecall_bottom_block">

    <div class="pozvonim-button" onclick=" ">

        <div class="block_button">
            <div onclick="" class=" title_callback">      {l s='Request a CallBack' mod='freecall'}</div>
        </div>

        <div class="block_inform">
            {if isset($settings['phone']) && $settings['phone']}
                <span class="infos_phone_one">{$settings['phone']|escape:'htmlall':'UTF-8'},</span>
            {/if}

            {if isset($settings['phone2']) && $settings['phone2']}
                <span class="infos_phone_one">{$settings['phone2']|escape:'htmlall':'UTF-8'}</span>
            {/if}
        </div>

        <input type="hidden" value="{$id_lang|escape:'htmlall':'UTF-8'}" name="idLang">
        <input type="hidden" value="{$id_shop|escape:'htmlall':'UTF-8'}" name="idShop">
        <input type="hidden" value="{$base_url|escape:'htmlall':'UTF-8'}" name="basePath">
    </div>
    <script type="text/javascript">
        {if $show_question && $time_show_question}
        {literal}
        setTimeout(function(){
            showFreeCallForm(false);
        }, {/literal}{$time_show_question|escape:'htmlall':'UTF-8'}{literal});
        {/literal}
        {/if}
    </script>
</div>
