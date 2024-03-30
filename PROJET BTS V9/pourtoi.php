<?php
// Inclure le fichier de connexion à la base de données
include 'db.php';
session_start();

include './preset/header.php'
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre titre</title>
    <link rel="stylesheet" href="./css/color.css">
    <link rel="stylesheet" href="./css/profil.css">
    <link rel="stylesheet" href="./css/pourtoi.css">
</head>
<body>
    <!-- Votre contenu PHP pour afficher les posts -->
    <div class="container">
        <?php
        // Sélectionner tous les posts de la base de données
        $result = $conn->query("SELECT * FROM posts");

        // Vérifier s'il y a des posts
        if ($result->num_rows > 0) {
            // Afficher les posts aléatoirement
            while ($row = $result->fetch_assoc()) {
                // Récupérer les informations de l'utilisateur qui a posté ce message
                $userId = $row['utilisateur_id'];
                $utilisateurQuery = "SELECT * FROM utilisateurs WHERE id_users = $userId";
                $resultatUtilisateur = $conn->query($utilisateurQuery);

                // Vérifier si l'utilisateur existe
                if ($resultatUtilisateur->num_rows > 0) {
                    $infoUtilisateur = $resultatUtilisateur->fetch_assoc();
                    $pseudoPosteur = $infoUtilisateur['pseudo'];
                    $photoProfil = $infoUtilisateur['photo_profil'];

                    // Afficher le pseudo de l'utilisateur et sa photo de profil
                    echo '<div class="post">';
                    echo '<div class="post-header post-info">';
                    echo '<img src="' . $photoProfil . '" alt="Photo de Profil de ' . $pseudoPosteur . '" class="post-picture profile-picture">';
                    echo '<div class="post-owner-info">';
                    echo '<span class="pseudo profile-username">' . $pseudoPosteur . '</span>';
                    echo '</div>';
                    echo '</div>';

                    // Afficher le post
                    echo '<div class="post-content post-contenue">';
                    echo '<p class="post-text">' . $row['contenu'] . '</p>';
                    
                    // Afficher le média (image ou vidéo)
                    if (!empty($row['media_path'])) {
                        if (pathinfo($row['media_path'], PATHINFO_EXTENSION) === 'mp4') {
                            echo '<video controls class="post-media">';
                            echo '<source src="' . $row['media_path'] . '" type="video/mp4">';
                            echo 'Your browser does not support the video tag.';
                            echo '</video>';
                        } else {
                            echo '<img src="' . $row['media_path'] . '" alt="Média du Post" class="post-media">';
                        }
                    }

                    // Afficher le nombre de likes et le bouton de like
                    $postId = $row['id_posts'];
                    $resultatLikes = $conn->query("SELECT COUNT(*) AS nb_likes FROM likes WHERE post_id = $postId");
                    $likes = $resultatLikes->fetch_assoc();
                    echo '<div class="like-section">';
                    echo '<button id="likeBtn' . $postId . '" class="like-btn" onclick="likePost(' . $postId . ')">' . $likes['nb_likes'] . ' Likes</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo "Utilisateur non trouvé.";
                }
            }
        } else {
            echo "Aucun post trouvé.";
        }

        // Fermer la connexion à la base de données
        $conn->close();
        ?>
    </div>

    <!-- Inclure vos fichiers JavaScript ici -->
    <script>
    // Votre script JavaScript pour gérer les likes
    function toggleLike(postId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'like.php?postId=' + postId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var likeBtn = document.querySelector('#likeBtn' + postId);
                likeBtn.textContent = xhr.responseText + ' Likes';
            }
        };
        xhr.send();
    }

    function likePost(postId) {
        var likeBtn = document.querySelector('#likeBtn' + postId);
        var hasLiked = likeBtn.classList.contains('liked');

        if (hasLiked) {
            toggleLike(postId);
            likeBtn.classList.remove('liked');
        } else {
            toggleLike(postId);
            likeBtn.classList.add('liked');
        }
    }
    </script>
</body>

<?php

include './preset/footer.php'

?>

</html>
