<!-- Section Newsletter -->
<section class="bg-black py-12 text-white">
    <div class="container mx-auto px-4 text-center">
      <!-- Titre -->
      <h2 class="text-2xl font-semibold mb-4">Restez informé !</h2>
  
      <!-- Description -->
      <p class="text-sm mb-6">
        Abonnez-vous à notre newsletter pour recevoir des mises à jour, des offres exclusives et bien plus directement dans votre boîte mail.
      </p>
  
      <!-- Formulaire d'inscription -->
      {hook h='displayFooterBefore'}
  
      <!-- Lien politique de confidentialité -->
      <p class="mt-4 text-xs text-gray-300">
        En vous inscrivant, vous acceptez notre 
        <a href="{$urls.index}content/2-legal-notice" class="text-yellow-200 hover:text-yellow-100">politique de confidentialité</a>.
      </p>
    </div>
  </section>
  
  <!-- Footer Principal -->
  <footer class="bg-black py-12 text-white">
    <div class="container mx-auto px-4">
      <!-- Icônes des réseaux sociaux -->
      <div class="flex justify-center gap-6 mb-8">
        <a href="#" class="text-white text-2xl hover:text-yellow-300 transition">
          <i class="fab fa-facebook"></i>
        </a>
        <a href="#" class="text-white text-2xl hover:text-yellow-300 transition">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="#" class="text-white text-2xl hover:text-yellow-300 transition">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="#" class="text-white text-2xl hover:text-yellow-300 transition">
          <i class="fab fa-linkedin"></i>
        </a>
        <a href="#" class="text-white text-2xl hover:text-yellow-300 transition">
          <i class="fab fa-youtube"></i>
        </a>
      </div>
  
      <!-- Moyens de paiement -->
      <div class="mb-8">
        <p class="text-center mb-4 text-sm">Paiements sécurisés :</p>
        <div class="flex justify-center gap-6">
          <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="h-8">
          <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa" class="h-8">
          <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="Mastercard" class="h-8">
          <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_Pay_logo.svg" alt="Apple Pay" class="h-8">
        </div>
      </div>
  
      <!-- Moyens de livraison -->
      <div class="mb-8">
        <p class="text-center mb-4 text-sm">Méthodes de livraison :</p>
        <div class="flex justify-center gap-6">
          <img src="{$urls.child_img_url}ups.png" alt="UPS" class="h-8">
          <img src="{$urls.child_img_url}laposte.png" alt="La Poste" class="h-8">
          <img src="{$urls.child_img_url}DHL.png" alt="DHL" class="h-8">
        </div>
      </div>
  
      <!-- Logo et contact -->
      <div class="text-center mb-8">
        <p>Contact : 
          <a href="mailto:behh5011@gmail.com" class="text-yellow-200 hover:text-yellow-100">behh5011@gmail.com</a>
        </p>
        <img src="{$urls.child_img_url}BEHH.png" class="h-16 mx-auto my-4" alt="Logo" />
      </div>
  
      <!-- Lien vers les politiques -->
      <div class="flex flex-wrap justify-center gap-4 text-gray-300 text-xs mb-8">
        <a href="{$urls.index}content/2-legal-notice" class="hover:text-yellow-200">Politique de confidentialité</a>
        <a href="{$urls.index}content/2-legal-notice" class="hover:text-yellow-200">Mentions légales</a>
        <a href="{$urls.index}content/1-delivery" class="hover:text-yellow-200">CGV</a>
        <a href="{$urls.index}content/3-terms-and-conditions-of-use" class="hover:text-yellow-200">Conditions d'utilisation</a>
      </div>
  
      <!-- Copyright -->
      <div class="text-center text-xs text-gray-300">
        BEHH© 2024 | <a href="https://www.prestashop-project.org/" class="text-yellow-200 hover:text-yellow-100">Ecommerce software by PrestaShop™</a>
      </div>
    </div>
  </footer>
  
  <!-- Ajouter Font Awesome -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  