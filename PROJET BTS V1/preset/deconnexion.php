<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte']) {
    // Détruire la session
    session_destroy();

    // Rediriger vers la page d'accueil ou toute autre page de votre choix
    header('Location: ../index.php');
    exit;
} else {
    // L'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header('Location: ../connexion.php');
    exit;
}
?>
