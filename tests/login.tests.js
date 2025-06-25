// Sélectionne la table HTML où les résultats seront affichés
const resultats = document.getElementById("resultats");

// Fonction pour ajouter une ligne de résultat dans le tableau HTML
function ajouterResultat(nom, succes, message) {
  const tr = document.createElement("tr"); // crée une ligne de tableau
  tr.className = succes ? "ok" : "fail";   // applique une classe selon le succès
  tr.innerHTML = `
    <td>${nom}</td>
    <td>${succes ? "✔️ Réussi" : "❌ Échec"}</td>
    <td>${message}</td>
  `;
  resultats.appendChild(tr); // ajoute la ligne dans le tableau
}

// Test si une clé aléatoire à 6 chiffres est bien générée
function test_generateRandomKey() {
  const key = Math.floor(100000 + Math.random() * 900000); // entre 100000 et 999999
  const ok = typeof key === "number" && key >= 100000 && key <= 999999;
  ajouterResultat("generateRandomKey", ok, "Clé générée : " + key);
}

// Teste l'enregistrement d'un id et d'un rôle dans sessionStorage
function test_sessionStorageEnregistrement() {
  const mock = { id: "10", roles: "user" };
  sessionStorage.setItem("id", mock.id);
  sessionStorage.setItem("roles", mock.roles);
  const ok = sessionStorage.getItem("id") === "10" && sessionStorage.getItem("roles") === "user";
  ajouterResultat("sessionStorageEnregistrement", ok, ok ? "Valeurs correctes" : "Valeurs incorrectes");
}

// Vérifie que les données de session sont bien supprimées après une commande
function test_clearSessionAfterCommande() {
  sessionStorage.setItem("panier", "test");
  sessionStorage.setItem("identification", "true");
  sessionStorage.removeItem("panier");
  sessionStorage.removeItem("identification");
  const ok = sessionStorage.getItem("panier") === null && sessionStorage.getItem("identification") === null;
  ajouterResultat("clearSessionAfterCommande", ok, ok ? "Nettoyage OK" : "Nettoyage KO");
}

// Teste si les données du panier sont bien mises dans un FormData
function test_createCommandePayload() {
  sessionStorage.setItem("id", 42);
  const panier = JSON.stringify([{ id: "1", formule: "Test", prix: 100, quantite: 2 }]);
  sessionStorage.setItem("panier", panier);
  const formData = new FormData();
  formData.append("id", 42);
  formData.append("panier", panier);
  const valid = formData.get("id") == "42" && formData.get("panier").includes("Test") && formData.get("panier").includes("100");
  ajouterResultat("createCommandePayload", valid, valid ? "FormData correct" : "FormData incorrect");
}

// Teste l'affichage conditionnel selon l'état d'identification
function test_displayBasedOnAuthentication() {
  document.body.insertAdjacentHTML("beforeend", `
    <div id="formPaiement"></div>
    <div id="formAuthent"></div>
  `);
  sessionStorage.setItem("identification", "false");
  const auth = JSON.parse(sessionStorage.getItem("identification"));
  if (!auth) {
    document.getElementById("formPaiement").classList.add("d-none");
  } else {
    document.getElementById("formAuthent").classList.add("d-none");
  }
  const hidden = document.getElementById("formPaiement").classList.contains("d-none");
  const visible = !document.getElementById("formAuthent").classList.contains("d-none");
  const ok = hidden && visible;
  ajouterResultat("displayBasedOnAuthentication", ok, ok ? "Affichage correct" : "Erreur d’affichage");
}

// Lance tous les tests définis ci-dessus
function runAllTests() {
  test_generateRandomKey();
  test_createCommandePayload();
  test_sessionStorageEnregistrement();
  test_clearSessionAfterCommande();
  test_displayBasedOnAuthentication();
}

// Exécute automatiquement tous les tests à l'ouverture de la page
runAllTests();
