<div id="_desktop_user_info">
  <div class="user-info">
    {if $logged}
      <a
        class="account"
        href="{$my_account_url}"
        title="{l s='View my customer account' d='Shop.Theme.CustomerAccount'}"
        rel="nofollow"
      >
        <i class="material-icons logged">person_outline</i>
        <span class="hidden-sm-down">{$customerName}</span>
      </a>
    {else}
      <a
        href="{$my_account_url}"
        title="{l s='Log in to your customer account' d='Shop.Theme.CustomerAccount'}"
        rel="nofollow"
      >
        <i class="material-icons">person_outline</i>
        <span class="hidden-sm-down">{l s='Sign in' d='Shop.Theme.Actions'}</span>
      </a>
    {/if}
  </div>
</div>