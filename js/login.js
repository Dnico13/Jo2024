

// fonction pour faire apparraitre le formulaire de creation de compte


document.getElementById("formCreation").onclick = function() {
    let form = document.getElementById("formInscription");
    form.classList.remove("d-none");
    form.classList.add("d-block");
    let form2 = document.getElementById("formConnexion");
            form2.classList.add("d-none");
        };


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
        console.log("Réponse du serveur :", data); // Vérification des données reçues
        
        if (data.success) {  
            // Affichage d'un prompt pour saisir le code de vérification
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

// Fonction pour valider le code de vérification
function validateVerificationCode(code) {
    let formData = new FormData();
    formData.append("verif_code", code);
    formData.append("email", document.getElementById("floatingInput").value); // Récupération de l'email

    fetch("/backend/api/VerifyCode.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Réponse de la validation du code :", data);
        if (data.success) {
            alert("✅ Connexion validée !");
            window.location.href = "/accueil"; // Redirection vers la page d'accueil
        } else {
            alert("❌ Code invalide ou expiré. Réessayez !");
        }
    })
    .catch(error => console.error("Erreur :", error));
}


/* Partie inscription d'un nouvel utilisateur */

document.getElementById("formInscription").addEventListener("submit", function(event) {
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
        roles: document.getElementById("roles").value
    };

    
    // Envoi des données au serveur
    fetch("/backend/api/Inscription.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
          
        if (data.success) {
            alert("Inscription réussie ! Clé générée : " + clefAleatoire);
        } else {
            alert("Erreur : " + data.message);
        }
    })
    .catch(error => {
        
        alert("Une erreur est survenue, veuillez réessayer.");
    });
});
