const API_URL = `${window.location.origin}/backend/api/VisuOffres.php`;
const identification = JSON.parse(sessionStorage.getItem("identification"));


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

    const button = card.querySelector(".selection-button");
        button.addEventListener("click", () => ajouterAuPanier(offre));

    // Ajout de la carte générée au conteneur principal
    offresContainer.appendChild(card);
});
  });

const localStoragePanier = localStorage.getItem("panier");




  // --- 2eme partie -----***** Ajout d'une offre sélectionnée au panier ---


  
function ajouterAuPanier(offre) {
  const panierBody = document.querySelector(".table tbody");

  // Sélectionner le point d'insertion précis
  const pointInsertion = document.querySelector(".insertion");

  let existant = document.querySelector(`#panier-${offre.nom.replace(/\s+/g, '')}`);

  if (!existant) {
    const row = document.createElement("tr");

    row.dataset.id = offre.id; 

    row.innerHTML = `
      <td></td>
      <td>${offre.nom}</td>
      <td>${offre.prix}</td>
      <td></td>
      <td><input id="panier-${offre.nom.replace(/\s+/g, '')}" type="number" min="0" max="10" value="1" aria-label="quantité"/></td>
    `;

    // Insérer la ligne directement à l’endroit prévu
    panierBody.insertBefore(row, pointInsertion);
  } else {
    existant.value = parseInt(existant.value) + 1;
  }
}


// --- Calcul du montant total de la commande ---
function additionner() {
  let totalHT = 0;
  document.querySelectorAll("tbody tr").forEach(row => {
    const prix = parseFloat(row.cells[2]?.textContent) || 0;
    const quantite = parseInt(row.querySelector("input")?.value) || 0;
    totalHT += prix * quantite;
  });

  const tva = totalHT * 0.2;
  const totalTTC = totalHT + tva;

  document.getElementById("HT").textContent = totalHT.toFixed(2);
  document.getElementById("TVA").textContent = tva.toFixed(2);
  document.getElementById("TTC").textContent = totalTTC.toFixed(2);
}

/* ---- Fonction pour recuperer le panier cree et l envoyer pour la page recap---- */

function sauvegarderEtRediriger() {
    // Récupérer les produits du tableau
    const panier = [];

    document.querySelectorAll("tbody tr:not(:nth-last-child(-n+4))").forEach(row => {
        const cellules = row.querySelectorAll("td");
        if (cellules.length < 5) return; 

        const quantiteInput = cellules[4].querySelector("input"); // Sélectionner l'input dans la cellule
        const quantite = quantiteInput ? parseInt(quantiteInput.value) : 0; // ternaire 

        // Vérifier si le produit a une quantité valide
        if (quantite > 0) {
            const produit = {
                id: row.dataset.id,
                formule: cellules[1].textContent.trim(),
                prix: parseFloat(cellules[2].textContent),
                quantite: quantite
            };
            panier.push(produit);
        }
    });

    // Vérifier si le panier contient des éléments
    if (panier.length === 0) {
        alert("Votre panier est vide !");
        return;
    }

    // recuperation des montants 
    const totalHT = parseFloat(document.getElementById("HT").textContent);
    const tva = parseFloat(document.getElementById("TVA").textContent);
    const totalTTC = parseFloat(document.getElementById("TTC").textContent);


    // Sauvegarde du panier 
    sessionStorage.setItem("panier", JSON.stringify(panier));
    // Ajout des montants 
    sessionStorage.setItem("montants", JSON.stringify({ HT: totalHT, TVA: tva, TTC: totalTTC }));

    // Redirection après stockage
    window.location.href = "/recapitulatifCommande";
}