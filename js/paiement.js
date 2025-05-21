/* affichage de la page suivant si l'utilisateur est authentifié ou non */
const authentification = JSON.parse(sessionStorage.getItem("identification"));


/*Test local*/
//const authentification = true;
//sessionStorage.setItem("id", JSON.stringify(1)); // Stocke l'ID en tant que string JSON
//const userId = JSON.parse(sessionStorage.getItem("id")); // Récupère et convertit en entier
//console.log(userId); // Vérifie si l'ID est bien récupéré
/* fin test local*/


if (!authentification) {
        document.getElementById("formPaiement").classList.add("d-none");
        
    } else {
        
        document.getElementById("formAuthent").classList.add("d-none");
    }


/* ----------Récuperation du code js de login.js pour l'authentification et de la creation d'un compte modifie au niveau du href---------- */


// traitement du formulaire d'authentification

const API_URL = `${window.location.origin}/backend/api/LoginApi.php`;
console.log(API_URL);


 /* autentification avec envoi de mail */
document.getElementById("formConnexion").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch(API_URL, {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) 
    .then(data => {  
        
        
        if (data.success) {  
            
            sessionStorage.setItem("id", data.id);
            sessionStorage.setItem("roles", data.roles);
            
            

            let verificationCode = prompt("📩 Un code de vérification a été envoyé à votre email.\nVeuillez entrer le code :"); 
            
            if (verificationCode) {  
                validateVerificationCode(verificationCode);  
            } else {  
                alert("⚠️ Vous devez entrer un code pour continuer.");
            }
        } else {  
            alert("❌ Échec de l'authentification : " + data.message);
        }
    })  
    .catch(error => console.error("Erreur JavaScript :", error));  
});

/* Validation du code de vérification */
function validateVerificationCode(code) {
    let formData = new FormData();
    formData.append("verif_code", code);
    formData.append("email", document.getElementById("floatingInput").value);

    //  Récupération des données stockées en session
    let userId = sessionStorage.getItem("id");
    let userRoles = sessionStorage.getItem("roles");

   

    formData.append("id", userId);
    formData.append("roles", userRoles);

    fetch("/backend/api/VerifyCode.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        

        if (data.success) {  
            sessionStorage.setItem("identification", true);
            alert("✅ Connexion validée !");

                window.location.href = "/paiement"; 
            
        } else {  
            alert("❌ Code invalide ou expiré. Réessayez !");
            
            //  Sécurité : Suppression de  ID et rôle si la validation échoue
            sessionStorage.removeItem("id");
            sessionStorage.removeItem("roles");
        }
    })
    .catch(error => console.error("Erreur :", error));
}

/* Partie inscription d'un nouvel utilisateur */

document
  .getElementById("formInscription")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Empêche l'envoi classique du formulaire

    // Générer une clé aléatoire à 6 chiffres
    let clefAleatoire = Math.floor(100000 + Math.random() * 900000);

    // Mise à jour du champ caché dans le formulaire
    document.getElementById("clef1").value = clefAleatoire;

    // Récupération des données du formulaire
    let formData = {
      prenom2: document.getElementById("prenom2").value.trim(),
      nom2: document.getElementById("nom2").value.trim(),
      email2: document.getElementById("email2").value.trim(),
      password2: document.getElementById("password2").value.trim(),
      clef1: document.getElementById("clef1").value, // Récupération de la clé depuis l'input caché
      roles: document.getElementById("roles").value,
    };

    // Envoi des données au serveur
    fetch("/backend/api/Inscription.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Inscription réussie, vous pouvez desormais vous identifier.");
        } else {
          alert("Erreur : " + data.message);
        }
      })
      .catch((error) => {
        alert("Une erreur est survenue, veuillez réessayer.");
      });
  });

/*-------------------- fin de la recuperation du code Js de login.js -----------------------*/

/*-------------------alerte sur paiement refusé---------------------------------------------*/
document.getElementById("refusPaiement").addEventListener("click", function() {
    alert("Oups ! Il semblerait que le paiement n'ait pas abouti. Pas de panique, cela peut arriver pour plusieurs raisons. Vérifiez vos informations bancaires et réessayez, ou essayez un autre moyen de paiement. Besoin d'aide ? Nous sommes là pour vous !");
     window.location.href = "/paiement"; 

  });



/*-------------------alerte sur paiement accepté---------------------------------------------*/

   // alert("Tout est parfait ! 🎉 Votre paiement a bien été validé. Merci pour ta confiance ! Ton commande est en route, et nous avons hâte de te faire plaisir. À très bientôt !");
/*--------------code pour envoyer en bdd------------------*/   



document.getElementById("acceptPaiement").addEventListener("click", function(event) {
  event.preventDefault(); // Empêche la redirection
  
  
  const API_URL_cde = `${window.location.origin}/backend/api/commande.php`;
  
  
   // Récupérer l'ID et le panier depuis sessionStorage
    let id = parseInt(sessionStorage.getItem("id"), 10);
    let panier = sessionStorage.getItem("panier"); 

/* **************** code pour test en local ****************** */
    //let id = 1;

    //let id = 9;
    //let panierb=[{"id":"3","formule":"Formule Duo","prix":70,"quantite":1}]
/******************  fin du code pour test en local ********** */
  
    try {
        
        let parsedPanier = JSON.parse(panier);
        
        if (typeof parsedPanier === 'object' && parsedPanier !== null) {
            panier = JSON.stringify(parsedPanier);
        } else {
        }
    } catch (e) {
        console.error("Erreur de parsing du panier depuis sessionStorage. Vérifiez son format initial.", e);
        alert("Erreur de format du panier. Veuillez réessayer.");
        return; 
    }
    

let formData = new FormData();
formData.append("id", id);
formData.append("panier", panier);


fetch(API_URL_cde, {
    method: "POST",
    body: formData
})
.then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || 'Erreur inconnue du serveur.');
            }).catch(() => {
                return response.text().then(text => { throw new Error(text || 'Erreur réseau ou du serveur sans message spécifique.'); });
            });
        }
        return response.json();
    })
    .then(data => {
        
        if (data.status === "success") {
            
            alert("Paiement réussi ! Vous pouvez récupérer vos billets et les imprimer.");
            
            // Suppression des valeurs de session
            sessionStorage.removeItem("panier");
            sessionStorage.removeItem("id");
            sessionStorage.removeItem("roles");
            sessionStorage.removeItem("identification"); 


            if (data.cle_finale && data.cle_finale.length > 0) {
                 window.location.href = "/backend/billet.php?cle_finale=" + encodeURIComponent(data.cle_finale[0]);
            } else {
                 window.location.href = "/backend/billet.php"; // Rediriger sans clé si aucune n'est disponible
            }


            
        } else {
            alert("❌ Échec de la commande : " + data.message);
        }
    })
    .catch(error => {
        console.error("Erreur lors de l'envoi ou du traitement de la commande :", error.message);
        alert("Problème technique lors de la commande : " + error.message);
    });
});



/****************************fin du code envoi en bdd*************** */
