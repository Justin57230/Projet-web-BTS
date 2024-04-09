<?php
session_start();
include 'db.php';

if (isset($_SESSION['utilisateur_id']) && isset($_POST['subscribe'])) {
    $idAbonnement = $_POST['subscribe'];
    $idUtilisateur = $_SESSION['utilisateur_id'];

    // Vérifier si l'utilisateur n'est pas déjà abonné
    $resultatAbonnement = $conn->query("SELECT * FROM abonnements WHERE id_abonne = $idUtilisateur AND id_abonnement_a = $idAbonnement");
    if ($resultatAbonnement->num_rows == 0) {
        // Ajouter l'abonnement à la base de données
        $conn->query("INSERT INTO abonnements (id_abonne, id_abonnement_a) VALUES ($idUtilisateur, $idAbonnement)");
    }
}

if (isset($_SESSION['utilisateur_id']) && isset($_POST['unsubscribe'])) {
    $idAbonnement = $_POST['unsubscribe'];
    $idUtilisateur = $_SESSION['utilisateur_id'];

    // Supprimer l'abonnement de la base de données
    $conn->query("DELETE FROM abonnements WHERE id_abonne = $idUtilisateur AND id_abonnement_a = $idAbonnement");
}

// Rediriger vers la page précédente après le désabonnement
header("Location: {$_SERVER['HTTP_REFERER']}");
exit();
?>
