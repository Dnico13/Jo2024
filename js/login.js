

// fonction pour faire apparraitre le formulaire de creation de compte


document.getElementById("formCreation").onclick = function() {
    let form = document.getElementById("formInscription");
    form.classList.remove("d-none");
    form.classList.add("d-block");
    let form2 = document.getElementById("formConnexion");
            form2.classList.add("d-none");
        };
