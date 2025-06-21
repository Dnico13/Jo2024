<?php


$User= 'ndev2023_jo2024';
$Password = 'Studi2025@';


try {
    $pdo = new PDO('mysql:host=mysql-ndev2023.alwaysdata.net;dbname=ndev2023_jo2024ecf', $User, $Password);
   
} catch (PDOException $e) {
    echo " il y a erreur dans la connexion avec la Base De Donnée";
}
