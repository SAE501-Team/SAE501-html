<header id="header">
    <div class="bg-white shadow-md">
      <div class="container mx-auto px-4 py-2">
        <div class="flex justify-between items-center">
          
          {* Logo (ajusté à la taille correcte) *}
          <div class="w-10 h-auto">
            {if $shop.logo_details}
              {if $page.page_name == 'index'}
                {renderLogo}
              {else}
                {renderLogo}
              {/if}
            {/if}
          </div>
  
          {* Menu burger en version mobile *}
        <div class="md:hidden flex gap-5 ">
          {hook h='displayNav2'}
          <button aria-label="Open menu" class="md:hidden text-xl" id="menu-toggle">

            <i class="material-icons">menu</i>
          </button>
        </div>
          {* Menu principal (en version desktop) *}
          <nav class="hidden md:flex space-x-6 customNav">
            {block name='header_menu'}
              <ul class="flex space-x-6 relative">
                <!-- Nos céréales -->
                <li class="group relative">
                  <a href="{$urls.base_url}fr/10-nos-cereales" class="tab-link px-4 py-2 rounded-md transition-all hover:bg-gray-100">Nos céréales</a>
                  <ul class="absolute hidden group-hover:flex flex-col space-y-2 bg-white border rounded-lg shadow-lg py-4 px-6 top-full left-0 z-50">
                    <li><a href="{$urls.base_url}fr/11-gourmand" class="block text-gray-700 hover:text-gray-900">Gourmand</a></li>
                    <li><a href="{$urls.base_url}fr/12-sport" class="block text-gray-700 hover:text-gray-900">Sport</a></li>
                    <li><a href="{$urls.base_url}fr/13-bio" class="block text-gray-700 hover:text-gray-900">Bio</a></li>
                  </ul>
                </li>
          
                <!-- Mélange Magique -->
                <li class="group relative">
                  <a href="{$urls.base_url}fr/14-melange-magique" class="tab-link px-4 py-2 rounded-md transition-all hover:bg-gray-100">Mélange Magique</a>
                  <ul class="absolute hidden group-hover:flex flex-col space-y-2 bg-white border rounded-lg shadow-lg py-4 px-6 top-full left-0 z-50">
                    <li><a href="{$urls.base_url}fr/content/6-melange-magique" class="block text-gray-700 hover:text-gray-900">Créer</a></li>
                    <li><a href="{$urls.base_url}fr/14-melange-magique" class="block text-gray-700 hover:text-gray-900">Découvrir</a></li>
                  </ul>
                </li>
          
                <!-- Équipements -->
                <li class="group relative">
                  <a href="{$urls.base_url}fr/15-nos-equipements" class="tab-link px-4 py-2 rounded-md transition-all hover:bg-gray-100">Équipements</a>
                  <ul class="absolute hidden group-hover:flex flex-col space-y-2 bg-white border rounded-lg shadow-lg py-4 px-6 top-full left-0 z-50">
                    <li><a href="{$urls.base_url}fr/16-bols-tasses" class="block text-gray-700 hover:text-gray-900">Bols & Tasses</a></li>
                    <li><a href="{$urls.base_url}fr/17-distributeurs" class="block text-gray-700 hover:text-gray-900">Distributeurs</a></li>
                    <li><a href="{$urls.base_url}fr/18-cuilleres-pailles" class="block text-gray-700 hover:text-gray-900">Cuillères & Pailles</a></li>
                  </ul>
                </li>
          
                <!-- À propos -->
                <li>
                  <a href="{$urls.base_url}fr/content/7-nutrition" class="tab-link px-4 py-2 rounded-md transition-all hover:bg-gray-100">À propos</a>
                </li>
          
                <!-- Blog Matinal -->
                <li>
                  <a href="{$urls.base_url}fr/blog" class="tab-link px-4 py-2 rounded-md transition-all hover:bg-gray-100">Blog Matinal</a>
                </li>
              </ul>
            {/block}
            {hook h='displayNav2'}
          </nav>
          
          {* Icônes du panier et utilisateur *}
          
      </div>
  
      {* Menu mobile *}
      <div class="md:hidden hidden" id="mobile-menu">
        {block name='header_mobile_menu'}
          <ul class="space-y-4 py-4 px-6">
            <li><a href="#cereals" class="block text-lg">Nos céréales</a></li>
            <li><a href="#melange-magique" class="block text-lg">Mélange Magique</a></li>
            <li><a href="#equipements" class="block text-lg">Équipements</a></li>
            <li><a href="#a-propos" class="block text-lg">À propos</a></li>
            <li><a href="http://localhost:8080/en/blog" class="block text-lg">Blog Matinal</a></li>
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
  
  