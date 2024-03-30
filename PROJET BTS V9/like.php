<?php
session_start();

include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['postId'])) {
    $postId = $_GET['postId'];
    $userId = $_SESSION['utilisateur_id'];

    // Vérifier si l'utilisateur a déjà aimé ce post
    $resultat = $conn->query("SELECT * FROM likes WHERE utilisateur_id = $userId AND post_id = $postId");
    if ($resultat->num_rows == 0) {
        // Ajouter un nouveau like
        $conn->query("INSERT INTO likes (utilisateur_id, post_id) VALUES ($userId, $postId)");

        // Récupérer le nombre total de likes pour ce post
        $resultatLikes = $conn->query("SELECT COUNT(*) AS nb_likes FROM likes WHERE post_id = $postId");
        $likes = $resultatLikes->fetch_assoc();
        echo $likes['nb_likes'];
    } else {
        // L'utilisateur a déjà aimé ce post
        // Supprimer le like existant
        $conn->query("DELETE FROM likes WHERE utilisateur_id = $userId AND post_id = $postId");

        // Récupérer le nombre total de likes pour ce post après suppression
        $resultatLikes = $conn->query("SELECT COUNT(*) AS nb_likes FROM likes WHERE post_id = $postId");
        $likes = $resultatLikes->fetch_assoc();
        echo $likes['nb_likes'];
    }
} else {
    // Requête incorrecte
    echo 'Erreur: Requête incorrecte.';
}
?>
