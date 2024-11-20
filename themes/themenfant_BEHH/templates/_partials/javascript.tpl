{* Logique pour le menu burger et les interactions du panier et utilisateur *}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Toggle menu burger (mobile)
    const menuButton = document.querySelector('#menu-toggle');
    const mobileMenu = document.querySelector('#mobile-menu');
    
    if (menuButton && mobileMenu) {
      menuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
      });
    }

    // Icône utilisateur
    const userMenuButton = document.querySelector('#user-menu-button');
    const userMenu = document.querySelector('#user-menu');

    if (userMenuButton && userMenu) {
      userMenuButton.addEventListener('click', function() {
        userMenu.classList.toggle('hidden');
      });
    }

    // Panier
    const cartButton = document.querySelector('#cart-button');
    const cartContent = document.querySelector('#cart-content');

    if (cartButton && cartContent) {
      cartButton.addEventListener('click', function() {
        cartContent.classList.toggle('hidden');
      });
    }
  });
</script>
