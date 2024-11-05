<style>

    .close_form_freecall{
        border-top-right-radius: {($settings['border_radius']-1)|escape:'htmlall':'UTF-8'}px;
    }

    {if !$settings['show_email'] && !$settings['show_comment']}
        @media (min-width: 992px) {
            .button_freecall{
                margin-top: 30px;
            }
        }
    {/if}
</style>

<div class="overlay_freecall" onclick="" style="
        background-color: {$settings['background_overlay']|escape:'htmlall':'UTF-8'};
        opacity: {$settings['opacity_overlay']|escape:'htmlall':'UTF-8'};">
</div>
<div class="freecall_form {if isset($is_mobile) && $is_mobile}is_mobile{/if}" style="
        border-radius: {$settings['border_radius']|escape:'htmlall':'UTF-8'}px;
        color: {$settings['color_form']|escape:'htmlall':'UTF-8'};">
    <div class="background_form" style="
            background-color: {$settings['background_form']|escape:'htmlall':'UTF-8'};
            opacity: {$settings['opacity_form']|escape:'htmlall':'UTF-8'};
            border-radius: {$settings['border_radius']|escape:'htmlall':'UTF-8'}px;">
    </div>
    <div class="content_form">
        <div class="header_freecall">
                <div class="title title1"  {if $click == 'false'} style="display: none;"  {/if}>
                     {$settings['title_form']|escape:'htmlall':'UTF-8'}
                </div>
                <div class="title title2"  {if $click !== 'false'} style="display: none;"  {/if}>
                     {$settings['title_question']|escape:'htmlall':'UTF-8'}
                </div>
            <div class="close_form_freecall" onclick="" style="color: {$settings['color_button']|escape:'htmlall':'UTF-8'};">
                <i class="close_icon" onclick=""></i>
            </div>
        </div>
        <div class="description_freecall desc1"  {if $click == 'false'} style="display: none;"  {/if}>
            {$settings['description']|escape:'htmlall':'UTF-8'}
        </div>
        <div class="description_freecall desc2"  {if $click !== 'false'} style="display: none;"  {/if}>
            <div class="description_question">{$settings['description_question']|escape:'htmlall':'UTF-8'}</div>
            <div class="button_question ">
                <a onclick=""  class="da-button da_button_freecall">{l s='Yes' mod='freecall'}</a>
                <a onclick=""  class="no-button no_button_freecall">{l s='No' mod='freecall'}</a>
                <div style="clear: both"></div>
            </div>
            <div style="clear: both"></div>
        </div>
        <div style="clear: both"></div>
        <div class="footer_freecall" style="{if $click == 'false'} display: none;{/if}">
            <div class="input_phone"  {if $settings['show_email'] || $settings['show_comment'] || $settings['show_captcha']}style="width: 100%;     float: none;" {/if}>
                <div class="label_freecall">{l s='Phone Number' mod='freecall'}</div>
                <div class="icon">
                    <i class="icon-phone" >&#xE0B0;</i>
                </div>
                <input type="text" class="phone_user" {if $settings['show_email'] || $settings['show_comment'] || $settings['show_captcha']}style="width: 545px" {/if}>
            </div>
            {if isset($settings['show_email']) && $settings['show_email']}
                <div class="input_email">
                    <div class="label_freecall">{l s='Email address' mod='freecall'}</div>
                    <div class="icon">
                        <i class="icon-envelope" >&#xE158;</i>
                    </div>
                    <input type="text" class="email_user">
                </div>
            {/if}
            {if isset($settings['show_comment']) && $settings['show_comment']}
                <div class="field_comment">
                    <div class="label_freecall">{l s='Message' mod='freecall'}</div>
                    <div class="icon">
                        <i class="icon-comments-alt" >&#xE0B9;</i>
                    </div>
                    <textarea class="comment_user"></textarea>
                </div>
            {/if}

            {if isset($settings['show_captcha']) && $settings['show_captcha']}
                <div class="field_captcha">
                    <div class="label_captcha">{l s='Captcha' mod='freecall'}</div>
                    <div class="icon">
                        <i class="material-icons" >&#xE899;</i>
                    </div>
                    <div class="captcha_img">
                        <img src="{$captcha_url|escape:'htmlall':'UTF-8'}">
                    </div>
                    <div class="captcha_input">
                        <input type="text" class="captcha_res">
                    </div>
                    <div style="clear: both"></div>
                </div>
            {/if}



            <div class="button_freecall" style="  {if $settings['show_email'] || $settings['show_comment']  || $settings['show_captcha']}  width: 100%; text-align: center; {/if}">
                <button onclick="">
                    <span>
                        {l s='Call me' mod='freecall'}
                    </span>
                </button>
            </div>
            <div style="clear: both"></div>
        </div>
        <div style="clear: both"></div>
    </div>
    <div style="clear: both"></div>
</div>
<div style="clear: both"></div>
