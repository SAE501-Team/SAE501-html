<header id="header">
  <div class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-2">
      <div class="flex justify-between items-center">
        
        {* Logo (ajusté à la taille correcte) *}
        <div class="w-10 h-auto">
          {block name='header_logo'}
            {if $shop.logo_details}
              {renderLogo}
            {/if}
          {/block}
        </div>

        {* Menu burger en version mobile *}
        <button aria-label="Open menu" class="md:hidden text-xl" id="menu-toggle">
          <i class="material-icons">menu</i>
        </button>

        {* Menu principal (en version desktop) *}
        <nav class="hidden md:flex space-x-6">
          {block name='header_menu'}
            <ul class="flex space-x-6">
              <li><a href="#cereals" class="tab-link px-4 py-2 rounded-md transition-all">Nos céréales</a></li>
              <li><a href="#melange-magique" class="tab-link px-4 py-2 rounded-md transition-all">Mélange Magique</a></li>
              <li><a href="#equipements" class="tab-link px-4 py-2 rounded-md transition-all">Équipements</a></li>
              <li><a href="#a-propos" class="tab-link px-4 py-2 rounded-md transition-all">À propos</a></li>
              <li><a href="#blog" class="tab-link px-4 py-2 rounded-md transition-all">Blog Matinal</a></li>
            </ul>
          {/block}
        </nav>

        {* Icônes du panier et utilisateur *}
        <div class="flex items-center space-x-4">
          {block name='header_icons'}
            <div id="cart-button">
              <i class="material-icons">shopping_cart</i>
            </div>
            <div id="user-menu-button">
              <i class="material-icons">account_circle</i>
            </div>
          {/block}
        </div>
      </div>
    </div>

    {* Menu mobile *}
    <div class="md:hidden hidden" id="mobile-menu">
      {block name='header_mobile_menu'}
        <ul class="space-y-4 py-4 px-6">
          <li><a href="#cereals" class="block text-lg">Nos céréales</a></li>
          <li><a href="#melange-magique" class="block text-lg">Mélange Magique</a></li>
          <li><a href="#equipements" class="block text-lg">Équipements</a></li>
          <li><a href="#a-propos" class="block text-lg">À propos</a></li>
          <li><a href="#blog" class="block text-lg">Blog Matinal</a></li>
        </ul>
      {/block}
    </div>
  </div>
</header>

<script>
  // Script pour le menu burger
  document.getElementById("menu-toggle").addEventListener("click", function() {
    const mobileMenu = document.getElementById("mobile-menu");
    mobileMenu.classList.toggle("hidden");
  });

  // Script pour ajouter l'effet de fond bleu et texte blanc seulement sur l'onglet actif (au clic)
  document.querySelectorAll('.tab-link').forEach(link => {
    link.addEventListener('click', function () {
      // Retirer les classes d'activation de tous les liens
      document.querySelectorAll('.tab-link').forEach(item => {
        item.classList.remove('bg-blue-500', 'text-white');
      });
      // Ajouter la classe active au lien cliqué (fond bleu + texte blanc)
      this.classList.add('bg-blue-500', 'text-white');
    });
  });
</script>
