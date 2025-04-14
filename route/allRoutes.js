import Route from "./Route.js";

//Définition de toutes les routes de l'application
export const allRoutes = [
    new Route("/", "Accueil des JO Paris 2024", "./Pages/home.html"),
    new Route("/manifestations", "Presentation des manisfestations", "./Pages/offres.html"),
   
    

];

//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "JO 2024 :";