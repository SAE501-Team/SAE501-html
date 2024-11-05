
<div class="top_menu desktop_device">
    {foreach from=$menu key=key item=item}
        <div class="top_menu_one item_{$item['id_top_menu']|escape:'htmlall':'UTF-8'} {if isset($item['narrow']) && $item['narrow'] && $item['width']} narrow_item{/if} {if $item['content']} isset_content{/if}">
            <a {if $item['custom']} href="{$item['link']|escape:'htmlall':'UTF-8'}" class="item_top_menu"{else} class="item_top_menu content_hide"{/if}>{$item['title']|escape:'htmlall':'UTF-8'}
                {if $item['content']}
                    <i class="material-icons material-icons-add">add</i>
                    <i class="material-icons material-icons-remove">remove</i>
                {/if}
            </a>
            {if $item['content']}
                <div class="top_menu_content  narrow_block{if $item['custom']} disable_block{/if}"  data-narrow="{$item['narrow']|escape:'htmlall':'UTF-8'}"   data-width="{$item['width']|escape:'htmlall':'UTF-8'}" data-height="{$item['height']|escape:'htmlall':'UTF-8'}"  {if $item['narrow']}style="width: {$item['width']|escape:'htmlall':'UTF-8'}px"{/if}>
                    <div class="block" {if isset($item['background']) && $item['background'] && $item['content']} data-background="1" data-img-width="{$item['background']['width']|escape:'htmlall':'UTF-8'}" data-img-height="{$item['background']['height']|escape:'htmlall':'UTF-8'}"
                        style="background: url({$item['background']['link']|escape:'htmlall':'UTF-8'}) no-repeat;" {else}data-background="0"{/if}
                    >

                        {if $item['left_selection']}
                            <div class="left_selection_front selection_front"  data-height="{$item['left_height']|escape:'htmlall':'UTF-8'}" data-width="{$item['left_width']|escape:'htmlall':'UTF-8'}">
                                {if isset($item['title_left_selection']) && $item['title_left_selection']}
                                    <div class="title">{$item['title_left_selection']|escape:'htmlall':'UTF-8'}</div>
                                {/if}
                                {if isset($item['left_content']) && $item['left_content']}
                                    <div class="content">{$item['left_content']|escape:'htmlall':'UTF-8' nofilter}</div>
                                {/if}
                            </div>
                        {/if}
                        {if $item['main_selection']}
                            <div class="main_selection_front selection_front " data-height="{$item['main_height']|escape:'htmlall':'UTF-8'}" data-width="{$item['main_width']|escape:'htmlall':'UTF-8'}"  >
                                {if isset($item['title_main_selection']) && $item['title_main_selection']}
                                    <div class="title">{$item['title_main_selection']|escape:'htmlall':'UTF-8'}</div>
                                {/if}
                                {if isset($item['main_content']) && $item['main_content']}
                                    <div class="content">{$item['main_content']|escape:'htmlall':'UTF-8' nofilter}</div>
                                {/if}
                            </div>
                        {/if}
                        {if $item['right_selection']}
                            <div class="right_selection_front selection_front " data-height="{$item['right_height']|escape:'htmlall':'UTF-8'}" data-width="{$item['right_width']|escape:'htmlall':'UTF-8'}" >
                                {if isset($item['title_right_selection']) && $item['title_right_selection']}
                                    <div class="title">{$item['title_right_selection']|escape:'htmlall':'UTF-8'}</div>
                                {/if}
                                {if isset($item['right_content']) && $item['right_content']}
                                    <div class="content">{$item['right_content']|escape:'htmlall':'UTF-8' nofilter}</div>
                                {/if}
                            </div>
                        {/if}
                        {if $item['botton_selection']}
                            <div class="botton_selection_front selection_front"  data-height="{$item['botton_height']|escape:'htmlall':'UTF-8'}" data-width="{$item['botton_width']|escape:'htmlall':'UTF-8'}" >
                                {if isset($item['title_botton_selection']) && $item['title_botton_selection']}
                                    <div class="title">{$item['title_botton_selection']|escape:'htmlall':'UTF-8'}</div>
                                {/if}
                                {if isset($item['botton_content']) && $item['botton_content']}
                                    <div class="content">{$item['botton_content']|escape:'htmlall':'UTF-8' nofilter}</div>
                                {/if}
                             </div>
                        {/if}
                        <div style="clear: both"></div>

                    </div>
                    <div style="clear: both"></div>
                </div>
            {/if}
        </div>
    {/foreach}
    <div style="clear: both"></div>
</div>

<div style="clear: both"></div>
