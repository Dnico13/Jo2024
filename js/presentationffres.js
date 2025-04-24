async function fetchOffres() {
    try {
        const response = await fetch('/api/offres');
        const offres = await response.json();
        return offres;
    } catch (error) {
        console.error('Erreur lors de la récupération des offres :', error);
    }
}
