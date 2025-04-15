import Route from "./Route.js";

//Définition de toutes les routes de l'application
export const allRoutes = [
    new Route("/", "Accueil des JO Paris 2024", "./Pages/home.html"),
    new Route("/accueil", "Presentation des manisfestations des JO de Paris", "./Pages/accueil.html"),
   
    

];

//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "JO 2024 :";