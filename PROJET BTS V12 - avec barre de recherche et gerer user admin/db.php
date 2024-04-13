<?php
// Informations de connexion à la base de données
$servername = "localhost"; // Nom du serveur de la base de données
$username = "root"; // Nom d'utilisateur de la base de données
$password = "root"; // Mot de passe de la base de données
$dbname = "communauteam"; // Nom de la base de données à utiliser

// Création d'une nouvelle connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    // Si la connexion échoue, afficher un message d'erreur et arrêter l'exécution du script
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}
?>
