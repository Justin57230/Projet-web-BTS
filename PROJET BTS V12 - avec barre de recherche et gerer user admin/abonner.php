<?php
// Démarrage de la session pour accéder aux variables de session
session_start();
// Inclusion du fichier de connexion à la base de données
include 'db.php';

// Vérifier si l'utilisateur est connecté et a soumis le formulaire d'abonnement
if (isset($_SESSION['utilisateur_id']) && isset($_POST['subscribe'])) {
    $idAbonnement = $_POST['subscribe']; // Récupérer l'ID de l'utilisateur à suivre
    $idUtilisateur = $_SESSION['utilisateur_id']; // Récupérer l'ID de l'utilisateur connecté

    // Vérifier si l'utilisateur n'est pas déjà abonné à cet utilisateur
    $resultatAbonnement = $conn->query("SELECT * FROM abonnements WHERE id_abonne = $idUtilisateur AND id_abonnement_a = $idAbonnement");
    if ($resultatAbonnement->num_rows == 0) {
        // Ajouter l'abonnement à la base de données si l'utilisateur n'est pas déjà abonné
        $conn->query("INSERT INTO abonnements (id_abonne, id_abonnement_a) VALUES ($idUtilisateur, $idAbonnement)");
    }
}

// Vérifier si l'utilisateur est connecté et a soumis le formulaire de désabonnement
if (isset($_SESSION['utilisateur_id']) && isset($_POST['unsubscribe'])) {
    $idAbonnement = $_POST['unsubscribe']; // Récupérer l'ID de l'utilisateur dont on veut se désabonner
    $idUtilisateur = $_SESSION['utilisateur_id']; // Récupérer l'ID de l'utilisateur connecté

    // Supprimer l'abonnement de la base de données
    $conn->query("DELETE FROM abonnements WHERE id_abonne = $idUtilisateur AND id_abonnement_a = $idAbonnement");
}

// Rediriger vers la page précédente après l'abonnement ou le désabonnement
header("Location: {$_SERVER['HTTP_REFERER']}");
exit();
?>
