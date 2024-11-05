<li class="category_{$node.id|escape:'htmlall':'UTF-8'} {if isset($last) && $last == 'true'} last{/if} level_depth_{$node.level_depth|escape:'html':'UTF-8'} {if $node.children|@count > 0 || $node.right_section || $node.bottom_section }has_children{/if}">
	<a  href="{$node.link|escape:'html':'UTF-8'}" class="{if isset($currentCategoryId) && $node.id == $currentCategoryId}selected{/if} item_level_depth_{$node.level_depth|escape:'html':'UTF-8'}">
		{if $node.level_depth == 2}
			{if $node.icon}
				<span class="icon_category_block">
					<img class="icon_category" src="{$node.icon|escape:'htmlall':'UTF-8'}" alt="{$node.name|escape:'html':'UTF-8'}">
				</span>
			{else}
				<span class="icon_category_block"></span>
			{/if}
		{/if}
		{$node.name|escape:'html':'UTF-8'}
		<span class="arrow_category_block"><span></span></span>
	</a>
	{if $node.children|@count > 0 || $node.right_section || $node.bottom_section }
		<div data-width="{if $node.width}{$node.width|escape:'htmlall':'UTF-8'}{else}300{/if}"
			{if isset($node.background) && $node.background}
				data-img-width="{$node.background['width']|escape:'htmlall':'UTF-8'}" data-img-height="{$node.background['height']|escape:'htmlall':'UTF-8'}"
			{/if}
			class="subcategories_level_depth_{$node.level_depth|escape:'html':'UTF-8'}"
			{if $node.level_depth == 2}
				style="
					width: {if $node.width}{$node.width|escape:'htmlall':'UTF-8'}px{else}300px{/if};

					font-size: {if $node.font_size}{($node.font_size)|escape:'htmlall':'UTF-8'}px{else}15px{/if};
					line-height: {if $node.font_size}{($node.font_size+3)|escape:'htmlall':'UTF-8'}px{else}17px{/if};
					right:{if $node.width}{-($node.width-1)|escape:'htmlall':'UTF-8'}{else}-299{/if}px;
					{if isset($node.background) && $node.background}
							background-image: url({$node.background['link']|escape:'htmlall':'UTF-8'});
							background-repeat: no-repeat;
							background-position: 100% 0%;
					{/if}
				"
			{/if}>
			{if $node.children|@count > 0}
				<ul class="categories_block_left_menu" {if $node.right_section}style="width: calc(100% - {$node.right_section_width|escape:'htmlall':'UTF-8'}px)" {/if} >
					{foreach from=$node.children item=child name=categoryTreeBranch}
						{if $smarty.foreach.categoryTreeBranch.last}
							{include file="$tpl_path" node=$child last='true'}
						{else}
							{include file="$tpl_path" node=$child last='false'}
						{/if}
					{/foreach}
					<li style="clear: both"></li>
				</ul>
			{/if}
			{if $node.level_depth == 2}
				{if $node.right_section}
					<div class="right_block_left_menu" {if $node.right_section_width}style="width: {$node.right_section_width|escape:'htmlall':'UTF-8'}px"  data-width="{$node.right_section_width|escape:'htmlall':'UTF-8'}"{/if} >
						{if $node.title_right_section}
							<div class="right_title_block">{$node.title_right_section|escape:'htmlall':'UTF-8'}</div>
						{/if}
						<div class="right_block_content"> {if $node.tpl_right}{$node.tpl_right|escape:'htmlall':'UTF-8' nofilter}{/if}</div>
					</div>
				{/if}
				<div style="clear: both"></div>
				{if $node.bottom_section}
					<div class="bottom_block_left_menu">
						{if $node.title_bottom_section}
							<div class="bottom_title_block">{$node.title_bottom_section|escape:'htmlall':'UTF-8'}</div>
						{/if}
						<div class="bottom_block_content" >{if $node.tpl_bottom}{$node.tpl_bottom|escape:'htmlall':'UTF-8' nofilter}{/if}</div>
					</div>
				{/if}
			{/if}
		</div>
	{/if}
</li>
