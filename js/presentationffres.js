


const API_URL = `${window.location.origin}/backend/api/VisuOffres.php`;
console.log(API_URL);

// --- Récupéreration du  conteneur  ---
const offresContainer = document.getElementById('offres-container');


// ---  Lancement de  l'appel API ---
fetch(API_URL)
  .then(response => response.json()) 
  .then(data => {
    // --- Récupéreration du  tableau des offres ---
    
    const offres = data;

    // --- Vider le conteneur avant d'ajouter les nouvelles cartes ---
    offresContainer.innerHTML = ''

offres.forEach(offre => {
    // Création d'un élément div pour représenter une offre
    const card = document.createElement('div');
   

    // Ajout du contenu HTML directement dans l'élément
    card.innerHTML = `
    <div class="col" >  
      <div class="card mb-4 rounded-3 shadow-sm">
        <div class="card-header py-3 text-bg-secondary border-primary">
          <h4 class="my-0 fw-normal offre-nom">${offre.nom}</h4>
        </div>
        <div class="card-body">
          <h1 class="card-title pricing-card-title offre-prix">${offre.prix} Euros</h1>
          <ul class="list-unstyled mt-3 mb-4 ">
            <li class="offre-description1">${offre.description1 || 'Pas de description'}</li>
            <li class="offre-description2">${offre.description2 || 'Pas de description'}</li>
            <li class="offre-description3">${offre.description3 || 'Pas de description'}</li>
          </ul>
          <button type="button" class="w-100 btn btn-lg btn-outline-primary selection-button">Sélectionnez cette offre.</button>
        </div>
      </div>
    </div>
    `;

    // Ajout de la carte générée au conteneur principal
    offresContainer.appendChild(card);
});
  });
