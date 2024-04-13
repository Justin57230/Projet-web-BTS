<?php
// Démarrer une session PHP
session_start();

// Inclusion du fichier de connexion à la base de données
include('db.php');

// Vérification si la requête est de type GET et si le paramètre postId est défini dans l'URL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['postId'])) {
    // Récupération de l'identifiant du post à partir des données GET
    $postId = $_GET['postId'];
    // Récupération de l'identifiant de l'utilisateur à partir de la session
    $userId = $_SESSION['utilisateur_id'];

    // Vérifier si l'utilisateur a déjà aimé ce post en recherchant dans la table des likes
    $resultat = $conn->query("SELECT * FROM likes WHERE utilisateur_id = $userId AND post_id = $postId");
    
    // Vérification si aucun like n'existe pour cet utilisateur et ce post
    if ($resultat->num_rows == 0) {
        // Ajouter un nouveau like dans la table des likes
        $conn->query("INSERT INTO likes (utilisateur_id, post_id) VALUES ($userId, $postId)");

        // Récupérer le nombre total de likes pour ce post après l'ajout du nouveau like
        $resultatLikes = $conn->query("SELECT COUNT(*) AS nb_likes FROM likes WHERE post_id = $postId");
        $likes = $resultatLikes->fetch_assoc();
        // Afficher le nombre total de likes
        echo $likes['nb_likes'];
    } else {
        // L'utilisateur a déjà aimé ce post, donc nous devons supprimer le like existant
        $conn->query("DELETE FROM likes WHERE utilisateur_id = $userId AND post_id = $postId");
        
        // Récupérer le nombre total de likes pour ce post après suppression du like existant
        $resultatLikes = $conn->query("SELECT COUNT(*) AS nb_likes FROM likes WHERE post_id = $postId");
        $likes = $resultatLikes->fetch_assoc();
        // Afficher le nombre total de likes
        echo $likes['nb_likes'];
    }
} else {
    // Si la requête est incorrecte (par exemple, si elle n'est pas de type GET ou si le paramètre postId est manquant)
    echo 'Erreur: Requête incorrecte.';
}
?>
