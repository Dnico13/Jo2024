


const panier = JSON.parse(sessionStorage.getItem("panier")) || [];
const montants = JSON.parse(sessionStorage.getItem("montants")) || { HT: 0, TVA: 0, TTC: 0 };

// Vérifier  si le tableau est prêt
const checkTableReady = setInterval(() => {
    const panierBody = document.querySelector("table tbody");

    if (panierBody) {
        
        clearInterval(checkTableReady); // Stop la vérification une fois trouvé

        // Récupérer le panier depuis localStorage
        const panier = JSON.parse(sessionStorage.getItem("panier")) || [];
        const montants = JSON.parse(sessionStorage.getItem("montants")) || { HT: 0, TVA: 0, TTC: 0 };

       

        // Vérifier si le panier contient des éléments
        if (panier.length === 0) {
            const emptyRow = document.createElement("tr");
            emptyRow.innerHTML = `<td colspan="5" class="text-center">Votre panier est vide.</td>`;
            panierBody.appendChild(emptyRow);
            return;
        }
         // Sélectionner la ligne du total HT pour insérer les produits avant
        const totalHTRow = document.getElementById("HT").parentElement;
        // Insérer les produits dans le tableau
        panier.forEach(produit => {
          

            const row = document.createElement("tr");
            row.dataset.id = produit.id;
            row.innerHTML = `
                <td></td>
                <td>${produit.formule}</td>
                <td>${produit.prix.toFixed(2)} €</td>
                <td></td>
                <td>${produit.quantite}</td>
            `;
             panierBody.insertBefore(row, totalHTRow);
        });

        // Afficher les montants HT, TVA et TTC
        document.getElementById("HT").textContent = montants.HT.toFixed(2) + " €";
        document.getElementById("TVA").textContent = montants.TVA.toFixed(2) + " €";
        document.getElementById("TTC").textContent = montants.TTC.toFixed(2) + " €";
    }
}, 500);

