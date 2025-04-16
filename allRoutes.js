import Route from "./Route.js";

//Définition de toutes les routes de l'application
export const allRoutes = [
    new Route("/", "Bienvenue sur le site des JO Paris 2024", "./Pages/home.html"),
    new Route("/accueil", "Presentation des manisfestations des JO de Paris", "Pages/accueil.html"),
    new Route("/mentions", "Presentation des mentions légales du site", "Pages/mentions.html"),
    new Route("/presentationOffres", "Presentation des Offres", "Pages/presentationOffres.html"),
    new Route("/login", "Page de Login", "Pages/login.html"),
    new Route("/panier", "Visualiser son panier", "Pages/panier.html"),
   
    

];

//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "JO 2024 :";