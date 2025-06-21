const tva = 0.2;
const pxt = 20.80;
const pxc = 18.40;
const pxb = 6.20; 

function additionner(){

    let tshirt = document.getElementById('Tshirt').value;
    let casquette = document.getElementById('casquette').value;
    let briquet = document.getElementById('briquet').value;


    let montant_tshirt = tshirt * pxt;
    let montant_casquette = casquette * pxc;
    let montant_briquet = briquet * pxb;

    let montant_ht = montant_tshirt + montant_casquette + montant_briquet;
    let montant_tva = montant_ht * tva;
    let montant_ttc = montant_ht + montant_tva;

    console.log(montant_ht);

     document.getElementById('HT').innerHTML = montant_ht.toFixed(2);
     document.getElementById('TVA').innerHTML = montant_tva.toFixed(2);
     document.getElementById('TTC').innerHTML = montant_ttc.toFixed(2);

}
