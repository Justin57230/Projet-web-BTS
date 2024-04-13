<?php
// Démarrage de la session pour accéder aux variables de session
session_start();

// Inclusion du fichier de connexion à la base de données
include('db.php');

// Fonction pour gérer le désabonnement
function unsubscribe($idAbonnement) {
    global $conn;
    $idUtilisateur = $_SESSION['utilisateur_id'];
    // Suppression de l'abonnement dans la base de données
    $conn->query("DELETE FROM abonnements WHERE id_abonne = $idUtilisateur AND id_abonnement_a = $idAbonnement");
}

// Vérifier si le formulaire de désabonnement a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unsubscribe'])) {
    $idAbonnement = $_POST['unsubscribe'];
    unsubscribe($idAbonnement);
    // Redirection vers la même page après le désabonnement
    header("Location: abonnement.php");
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$idUtilisateur = $_SESSION['utilisateur_id'];

// Récupérer les ID des utilisateurs auxquels l'utilisateur connecté est abonné
$resultatAbonnements = $conn->query("SELECT id_abonnement_a FROM abonnements WHERE id_abonne = $idUtilisateur");
$idsAbonnements = [];
while ($row = $resultatAbonnements->fetch_assoc()) {
    $idsAbonnements[] = $row['id_abonnement_a'];
}

// Récupérer tous les posts des utilisateurs auxquels l'utilisateur connecté est abonné
$postsAbonnements = [];
foreach ($idsAbonnements as $idAbonnement) {
    $resultatPosts = $conn->query("SELECT * FROM posts WHERE utilisateur_id = $idAbonnement ORDER BY created_at DESC");
    while ($row = $resultatPosts->fetch_assoc()) {
        $postsAbonnements[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- On appel les fichiers CSS et définit titre et icon -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauteam - Mes abonnements</title>
    <link rel="stylesheet" href="./css/color.css">
    <link rel="stylesheet" href="./css/profil.css">
    <link rel="stylesheet" href="./css/profil2.css">
    <link rel="stylesheet" href="./css/abonnement.css">
    <link rel="icon" href="./media/logo.png">
</head>

<body>
    <!-- Appel du preset HEADER -->
    <?php include('./preset/header.php'); ?>

    <div class="profile-container">
        <!-- Affichage si l'utilisateur n'a aucun abonnement -->
        <?php if (empty($postsAbonnements)) : ?>
            <p>Aucun abonnement.</p>
        <?php else : ?>
            <!-- Affichage des posts des abonnements -->
            <?php foreach ($postsAbonnements as $post) : ?>
                <?php
                // Récupérer les informations de l'utilisateur qui a posté ce message
                $resultatUtilisateur = $conn->query("SELECT pseudo, photo_profil FROM utilisateurs WHERE id_users = " . $post['utilisateur_id']);
                $infoUtilisateur = $resultatUtilisateur->fetch_assoc();
                ?>
                <div class="post">
                    <!-- Afficher la photo du profil et le pseudo de l'utilisateur -->
                    <div class="post-header post-info">
                        <img src="<?php echo $infoUtilisateur['photo_profil']; ?>" alt="Photo de Profil de <?php echo $infoUtilisateur['pseudo']; ?>" class="post-picture">
                        <div class="post-owner-info">
                            <span class="pseudo"><?php echo $infoUtilisateur['pseudo']; ?></span>
                        </div>
                    </div>

                    <!-- Afficher le contenu du post -->
                    <p class="post-contenue"><?php echo $post['contenu']; ?></p>

                    <!-- Afficher le média (image ou vidéo) -->
                    <?php if (!empty($post['media_path'])) : ?>
                        <?php if (pathinfo($post['media_path'], PATHINFO_EXTENSION) === 'mp4') : ?>
                            <video controls class="post-media">
                                <source src="<?php echo $post['media_path']; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php else : ?>
                            <img src="<?php echo $post['media_path']; ?>" alt="Média du Post" class="post-media">
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Afficher le bouton de like -->
                    <div class="like-section">
                        <?php
                        $postId = $post['id_posts'];
                        // Récupérer le nombre de likes pour ce post
                        $resultatLikes = $conn->query("SELECT COUNT(*) AS nb_likes FROM likes WHERE post_id = $postId");
                        $likes = $resultatLikes->fetch_assoc();
                        ?>
                        <button id="likeBtn<?php echo $postId; ?>" class="like-btn" onclick="likePost(<?php echo $postId; ?>)">
                            <?php echo $likes['nb_likes']; ?> Likes
                        </button>
                        <!-- Afficher le bouton de désabonnement -->
                        <?php if ($post['utilisateur_id'] != $idUtilisateur) : ?>
                            <form method="post">
                                <button type="submit" class="unsubscribe-btn" name="unsubscribe" value="<?php echo $post['utilisateur_id']; ?>">Se désabonner</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include('./preset/footer.php'); ?>

    <script>
        // Fonction JavaScript pour gérer le like d'un post
        function likePost(postId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("likeBtn" + postId).innerHTML = this.responseText + ' Likes';
                }
            };
            xhr.open("GET", "like.php?postId=" + postId, true);
            xhr.send();
        }
    </script>

</body>

</html>
