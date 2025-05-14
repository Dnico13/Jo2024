import Route from "./Route.js";

//Définition de toutes les routes de l'application
export const allRoutes = [
    new Route("/", "Bienvenue sur le site des JO Paris 2024", "./Pages/home.html"),
    new Route("/accueil", "Presentation des manisfestations des JO de Paris", "Pages/accueil.html", "js/accueil.js"),
    new Route("/mentions", "Presentation des mentions légales du site", "Pages/mentions.html"),
    new Route("/presentationOffres", "Presentation des Offres", "Pages/presentationOffres.html", "js/presentationffres.js"),
    new Route("/login", "Page de Login", "Pages/login.html", "js/login.js"),
    new Route("/panier", "Visualiser son panier", "Pages/panier.html", "js/panier.js"),
    new Route("/recapitulatifCommande", "Voici le recapitulatif de votre commande", "Pages/recapitulatif.html", "js/recapitulatif.js"),
    new Route("/tests", "tests sur affichage offres", "Pages/tests.html", "js/tests.js"),
   
    

];

//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "JO 2024 :";