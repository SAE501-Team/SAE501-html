{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{if $displayedFacets|count}
  <div id="search_filters" class="border-3 border-black">
    {block name='facets_title'}
      <p class="text-uppercase h6 hidden-sm-down font-adlam border-3 border-black text-center">{l s='Filter By' d='Shop.Theme.Actions'}</p>
    {/block}

    {block name='facets_clearall_button'}
      {if $activeFilters|count}
        <div id="_desktop_search_filters_clear_all" class="hidden-sm-down clear-all-wrapper">
          <button data-search-url="{$clear_all_link}" class="js-search-filters-clear-all bg-gradient-to-tl from-be-rose to-be-bleu text-white text-[10px] rounded-[10px] font-adlam hover:scale-90 degRose">
            <i class="material-icons">&#xE14C;</i>
            {l s='Clear all' d='Shop.Theme.Actions'}
          </button>
        </div>
      {/if}
    {/block}

    {foreach from=$displayedFacets item="facet"}
      <section class="facet clearfix" data-type="{$facet.type}">
        <p class="h6 facet-title hidden-sm-down">{$facet.label}</p>
        {assign var=_expand_id value=10|mt_rand:100000}
        {assign var=_collapse value=true}
        {foreach from=$facet.filters item="filter"}
          {if $filter.active}{assign var=_collapse value=false}{/if}
        {/foreach}

        <div class="title hidden-md-up" data-target="#facet_{$_expand_id}" data-toggle="collapse"{if !$_collapse} aria-expanded="true"{/if}>
          <p class="h6 facet-title">{$facet.label}</p>
          <span class="navbar-toggler collapse-icons">
            <i class="material-icons add">&#xE313;</i>
            <i class="material-icons remove">&#xE316;</i>
          </span>
        </div>

        {if in_array($facet.widgetType, ['radio', 'checkbox'])}
          {block name='facet_item_other'}
            <ul id="facet_{$_expand_id}" class="collapse{if !$_collapse} in{/if}">
              {foreach from=$facet.filters key=filter_key item="filter"}
                {if !$filter.displayed}
                  {continue}
                {/if}

                <li>
                  <label class="facet-label{if $filter.active} active {/if}" for="facet_input_{$_expand_id}_{$filter_key}">
                    <div class="checkbox-b">
                      <input 
                        id="facet_input_{$_expand_id}_{$filter_key}" 
                        class="checkbox-b__input" 
                        data-search-url="{$filter.nextEncodedFacetsURL}" 
                        type="checkbox" 
                        {if $filter.active}checked{/if} 
                      />
                      <label for="facet_input_{$_expand_id}_{$filter_key}" class="checkbox-b__label">
                        {if isset($filter.properties.color)}
                          <!-- Afficher la couleur de fond -->
                          <span class="color" style="background-color:{$filter.properties.color}"></span>
                        {elseif isset($filter.properties.texture)}
                          <!-- Afficher l'image de texture comme fond -->
                          <span class="color texture" style="background-image:url({$filter.properties.texture})"></span>
                        {else}
                          <!-- Si aucune couleur ou texture, afficher une coche ou autre icône -->
                          <div class="checkbox-b__custom">
                            <span class="checkbox-b__custom-fill"></span>
                          </div>
                        {/if}
                        
                        <span class="checkbox-b__text">{$filter.label}{if $filter.magnitude and $show_quantities} <span>({$filter.magnitude})</span>{/if}</span>
                      </label>
                    </div>
                  </label>                  
                </li>
              {/foreach}
            </ul>
          {/block}

        {elseif $facet.widgetType == 'dropdown'}
          {block name='facet_item_dropdown'}
            <ul id="facet_{$_expand_id}" class="collapse{if !$_collapse} in{/if}">
              <li>
                <div class="col-sm-12 col-xs-12 col-md-12 facet-dropdown dropdown desColapse">
                  <a class="select-title" rel="nofollow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {$active_found = false}
                    <span>
                      {foreach from=$facet.filters item="filter"}
                        {if $filter.active}
                          {$filter.label}
                          {if $filter.magnitude and $show_quantities}
                            ({$filter.magnitude})
                          {/if}
                          {$active_found = true}
                        {/if}
                      {/foreach}
                      {if !$active_found}
                        {l s='(no filter)' d='Shop.Theme.Global'}
                      {/if}
                    </span>
                    <i class="material-icons float-xs-right">&#xE5C5;</i>
                  </a>
                  <div class="dropdown-menu">
                    {foreach from=$facet.filters item="filter"}
                      {if !$filter.active}
                        <a
                          rel="nofollow"
                          href="{$filter.nextEncodedFacetsURL}"
                          class="select-list js-search-link"
                        >
                          {$filter.label}
                          {if $filter.magnitude and $show_quantities}
                            ({$filter.magnitude})
                          {/if}
                        </a>
                      {/if}
                    {/foreach}
                  </div>
                </div>
              </li>
            </ul>
          {/block}

        {elseif $facet.widgetType == 'slider'}
          {block name='facet_item_slider'}
            {foreach from=$facet.filters item="filter"}
              <ul id="facet_{$_expand_id}"
                class="faceted-slider collapse{if !$_collapse} in{/if}"
                data-slider-min="{$facet.properties.min}"
                data-slider-max="{$facet.properties.max}"
                data-slider-id="{$_expand_id}"
                data-slider-values="{$filter.value|@json_encode}"
                data-slider-unit="{$facet.properties.unit}"
                data-slider-label="{$facet.label}"
                data-slider-specifications="{$facet.properties.specifications|@json_encode}"
                data-slider-encoded-url="{$filter.nextEncodedFacetsURL}"
              >
                <li>
                  <p id="facet_label_{$_expand_id}">
                    {$filter.label}
                  </p>

                  <div id="slider-range_{$_expand_id}"></div>
                </li>
              </ul>
            {/foreach}
          {/block}
        {/if}
      </section>
    {/foreach}
  </div>
{else}
  <div id="search_filters" style="display:none;">
  </div>  
{/if}
