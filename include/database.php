
<?php

$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "inzu_burundi";

$connexion = new mysqli(
    $serveur,
    $utilisateur,
    $mot_de_passe,
    $base_de_donnees
);

if ($connexion->connect_error) {
    die("Erreur de connexion : " . $connexion->connect_error);
}

$connexion->set_charset("utf8mb4");

?>
