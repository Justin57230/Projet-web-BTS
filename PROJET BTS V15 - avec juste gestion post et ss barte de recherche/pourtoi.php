<?php
session_start();

if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte']) {
    include 'db.php';
    include './preset/header.php';

    // Gérer le désabonnement si le formulaire est soumis
    if (isset($_POST['unsubscribe'])) {
        $idAbonnement = $_POST['unsubscribe'];
        $idUtilisateur = $_SESSION['utilisateur_id'];
        // Supprimer l'abonnement de la base de données
        $conn->query("DELETE FROM abonnements WHERE id_abonne = $idUtilisateur AND id_abonnement_a = $idAbonnement");
        // Recharger la page pour refléter les changements
        header("Location: pourtoi.php");
        exit();
    }

    // Gérer l'abonnement si le formulaire est soumis
    if (isset($_POST['subscribe'])) {
        $idAbonnement = $_POST['subscribe'];
        $idUtilisateur = $_SESSION['utilisateur_id'];
        // Insérer l'abonnement dans la base de données
        $conn->query("INSERT INTO abonnements (id_abonne, id_abonnement_a) VALUES ($idUtilisateur, $idAbonnement)");
        // Recharger la page pour refléter les changements
        header("Location: pourtoi.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauteam - Pourtoi</title>
    <link rel="icon" href="./media/logo.png">
    <link rel="stylesheet" href="./css/color.css">
    <link rel="stylesheet" href="./css/profil.css">
    <link rel="stylesheet" href="./css/pourtoi.css">
    <link rel="stylesheet" href="./css/abonnement.css"> <!-- Nouveau fichier CSS pour le style des boutons d'abonnement -->
    <link rel="icon" href="./media/logo.png">
</head>

<body>
    <!-- Votre contenu PHP pour afficher les posts -->
    <div class="container">
        <?php
        // Sélectionner tous les posts de la base de données dans un ordre aléatoire
        $result = $conn->query("SELECT * FROM posts ORDER BY RAND()");

        // Vérifier s'il y a des posts
        if ($result->num_rows > 0) {
            // Afficher les posts
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

                    // Déterminer le lien de redirection en fonction de la session
                    $redirectLink = ($_SESSION['utilisateur_id'] == $userId) ? 'profil.php?id=' . $userId : 'profil2.php?id=' . $userId;

                    // Afficher le pseudo de l'utilisateur et sa photo de profil
                    echo '<div class="post">';
                    echo '<div class="post-header post-info">';
                    echo '<a href="' . $redirectLink . '"><img src="' . $photoProfil . '" alt="Photo de Profil de ' . $pseudoPosteur . '" class="post-picture profile-picture"></a>';
                    echo '<div class="post-owner-info">';
                    echo '<a href="' . $redirectLink . '" class="pseudo profile-username">' . $pseudoPosteur . '</a>';
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
                    // Bouton S'abonner / Se désabonner
                    $estAbonne = false;
                    if (isset($_SESSION['utilisateur_id'])) {
                        $resultatAbonnement = $conn->query("SELECT * FROM abonnements WHERE id_abonne = " . $_SESSION['utilisateur_id'] . " AND id_abonnement_a = " . $userId);
                        if ($resultatAbonnement->num_rows > 0) {
                            $estAbonne = true;
                        }
                    }
                    if ($userId != $_SESSION['utilisateur_id']) {
                        if ($estAbonne) {
                            echo '<form method="post">';
                            echo '<button type="submit" class="unsubscribe-btn" name="unsubscribe" value="' . $userId . '">Se désabonner</button>';
                            echo '</form>';
                        } else {
                            echo '<form method="post">';
                            echo '<button type="submit" class="subscribe-btn" name="subscribe" value="' . $userId . '">S\'abonner</button>';
                            echo '</form>';
                        }
                    }
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
