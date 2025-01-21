console.log("celui-ci marche bien");

 // Sélection de tous les éléments ayant la classe 'pane'
const panes = document.querySelectorAll('.pane');
let activePaneIndex = 1; // Index du panneau actif initial

// Ajout de l'écouteur d'événements 'click' pour chaque panneau
panes.forEach((pane, index) => {
  pane.addEventListener('click', () => {
    // Suppression de la classe 'active' du panneau actuellement actif
    panes[activePaneIndex].classList.remove('active');
    // Mise à jour de l'index du nouveau panneau actif
    activePaneIndex = index;
    // Ajout de la classe 'active' au panneau sélectionné
    panes[activePaneIndex].classList.add('active');
  });
}); 