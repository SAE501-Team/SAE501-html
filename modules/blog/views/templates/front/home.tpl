<div class="home_page_articles">
    <h3 class="h3 home_page_articles_title">{l s='Latest articles'  mod='blog'}</h3>
    <ul id="home_page_menu_blog" class="">
        {foreach from=$articles key=key item=value}
            {if $value['is_image']}
                <li class="item_articles" style="width: {((100)/(count($articles)))|escape:'htmlall':'UTF-8'}%">
                    <a id="link-blog-img" class="" href="{$blogUrl|escape:'htmlall':'UTF-8'}{$value['link_rewrite_category']|escape:'htmlall':'UTF-8'}/{$value['link_rewrite']|escape:'htmlall':'UTF-8'}.html" >
                        <img alt="{$value['name']|truncate:90|escape:'htmlall':'UTF-8'}" class="grid_image_home" src="{$value['is_image']|escape:'htmlall':'UTF-8'}{$value['id_blog_post']|escape:'htmlall':'UTF-8'}-image_grid.jpg" />
                    </a>
                    <a id="link-blog-home" class="" href="{$blogUrl|escape:'htmlall':'UTF-8'}{$value['link_rewrite_category']|escape:'htmlall':'UTF-8'}/{$value['link_rewrite']|escape:'htmlall':'UTF-8'}.html" >
                        {$value['name']|truncate:90|escape:'htmlall':'UTF-8'}
                    </a>
                    <span class="home_page_description">
                        {$value['description_short']|escape:'htmlall':'UTF-8' nofilter}
                    </span>
                </li>
            {/if}
        {/foreach}
    </ul>
</div>