{if count($slides) > 0 && $settings['active']}
    <div class="carousel-container" style="width: {$settings['width']|escape:'htmlall':'UTF-8'}px; height: {$settings['height']|escape:'htmlall':'UTF-8'}px;">
        <div id="carousel" >
            {foreach $slides as $slide}
                <div class="carousel-feature">
                    <a {if isset($slide['url']) && $slide['url']} href="{$slide['url']|escape:'htmlall':'UTF-8'}" {/if} {if isset($slide['title']) && $slide['title']} title="{$slide['title']|escape:'htmlall':'UTF-8'}" {/if}>
                        <img class="carousel-image" {if isset($slide['caption']) && $slide['caption']} alt="{$slide['caption']|escape:'htmlall':'UTF-8'}" {/if} src="{$img_dir|escape:'htmlall':'UTF-8'}{$slide['image']|escape:'htmlall':'UTF-8'}">
                    
					    {if isset($slide['description']) && $slide['description']}
                            <span  class="carousel-caption" data-position-desc="{$slide['position_desc']|escape:'htmlall':'UTF-8'}" style="
                                    width: {$slide['width_desc']|escape:'htmlall':'UTF-8'}px;
                                    height: {$slide['height_desc']|escape:'htmlall':'UTF-8'}px;
                            {if $slide['position_desc'] == 'top_left'}
                                    top:0px;
                                    left:0px;
                            {/if}
                            {if $slide['position_desc'] == 'top_right'}
                                    top:0px;
                                    right:0px;
                            {/if}
                            {if $slide['position_desc'] == 'bottom_right'}
                                    bottom:0px;
                                    right:0px;
                            {/if}
                            {if $slide['position_desc'] == 'bottom_left'}
                                    bottom:0px;
                                    left:0px;
                            {/if}
                            {if $slide['position_desc'] == 'center'}
                                    top:50%;
                                    left:50%;
                                    margin-left: -{($slide['width_desc']/2)|escape:'htmlall':'UTF-8'}px;
                                    margin-top: -{($slide['height_desc']/2)|escape:'htmlall':'UTF-8'}px;
                            {/if}">

                                {if $slide['opacity_desc']}
                                    <span class="carousel-caption-opacity" style=" opacity: {$slide['opacity_desc']|escape:'htmlall':'UTF-8'};    "></span>
                                {/if}

                                <span class="carousel-caption-cont">
                                    {$slide['description']|escape:'htmlall':'UTF-8' nofilter}
                                </span>
                            </span>
                        {/if}
					
					</a>

                </div>
            {/foreach}
        </div>
        {if $settings['controls']}
            <div id="carousel-left"><div></div></div>
            <div id="carousel-right"><div></div></div>
        {/if}
    </div>
    <div style="clear: both"></div>
{/if}

