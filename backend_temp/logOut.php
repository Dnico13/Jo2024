<?php
session_start();

$_SESSION = [];
session_destroy();
header("Location: http://www.jo2024.ndev2023.fr/accueil");
exit(); 
?>