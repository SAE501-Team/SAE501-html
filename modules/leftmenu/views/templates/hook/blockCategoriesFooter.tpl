<div class="blockCategoriesFooter footer-block links col-xs-12 col-sm-2">
	<h3 class="h3 hidden-sm-down">{l s='Categories' mod='leftmenu'}</h3>

	<div class="title clearfix hidden-md-up" data-target="#footer_sub_menu_category" data-toggle="collapse">
		<span class="h3">{l s='Categories'  mod='leftmenu'}</span>
		<span class="pull-xs-right">
          <span class="navbar-toggler collapse-icons">
            <i class="material-icons add">add</i>
            <i class="material-icons remove">remove</i>
          </span>
        </span>
	</div>




	<ul class="tree collapse" id="footer_sub_menu_category">
		{foreach from=$blockCategTree.children item=child name=blockCategTree}
			<li>
				<a  href="{$child.link|escape:'html':'UTF-8'}">{$child.name|escape:'html':'UTF-8'}</a>
			</li>
		{/foreach}
	</ul>

<div class="clear"></div>
</div>
