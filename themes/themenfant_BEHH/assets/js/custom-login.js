//test
//document.querySelector(".no-account").innerHTML="on voit ce texte ????????????";
/*
code d'intégration
<script src="{$urls.child_js_url}/custom-login.js"></script>
*/

document.addEventListener("DOMContentLoaded", function () {
  console.log("je suis bien là");
  const panes = document.querySelectorAll(".pane");
  let activePaneIndex = 1; // Index du panneau actif initial

  // Ajout de l'écouteur d'événements 'click' pour chaque panneau
  panes.forEach((pane, index) => {
    pane.addEventListener("click", () => {
      // Suppression de la classe 'active' du panneau actuellement actif
      console.log("j'exécute'");
      panes[activePaneIndex].classList.remove("active");
      // Mise à jour de l'index du nouveau panneau actif
      activePaneIndex = index;
      // Ajout de la classe 'active' au panneau sélectionné
      panes[activePaneIndex].classList.add("active");
    });
  });
});
