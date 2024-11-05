<div class="form-horizontal-menu">
    <div class="panel form-horizontal">
        <div class="panel-heading">
            <i class="icon-plus-sign-alt"></i> {l s='ADD VIDEO' mod='leftmenu'}
        </div>
        <div class="form-wrapper">
            <textarea class="videoCodeMenu" id="videoCodeMenu">
                {if isset($video_code) && $video_code}
                     {html_entity_decode($video_code|escape:'htmlall':'UTF-8')}
                 {/if}
            </textarea>
            <div class="save_menu_video_block">
                <button type="button" id="save_video_code_menu" class="btn btn-default">{l s='Save' mod='leftmenu'}</button>
                <div style="clear: both"></div>
            </div>
        </div>
    </div>
</div>