<?php
session_start();

// Inclure votre connexion à la base de données
include('db.php');

// Récupérer les informations de l'utilisateur actuel ou du profil demandé
// ...

$idProfil = $_SESSION['utilisateur_id'];

// Définir des variables pour éviter les erreurs "Undefined variable"
$estMonProfil = false;
$monPseudo = $monProfil = $photoProfil = $pseudoProfil = $profilDemande = null;

// Si c'est le profil de l'utilisateur actuel
if ($_SESSION['utilisateur_id'] == $idProfil) {
    $estMonProfil = true;

    // Récupérer les informations de l'utilisateur actuel depuis la base de données
    $resultat = $conn->query("SELECT * FROM utilisateurs WHERE id_users = " . $_SESSION['utilisateur_id']);
    $monProfil = $resultat->fetch_assoc();
    $monPseudo = $monProfil['pseudo'];
    $photoProfil = $monProfil['photo_profil'];

    // Récupérer tous les posts de l'utilisateur actuel, triés du plus récent au plus ancien
    $resultatPosts = $conn->query("SELECT * FROM posts WHERE utilisateur_id = " . $_SESSION['utilisateur_id'] . " ORDER BY created_at DESC");
    $mesPosts = $resultatPosts->fetch_all(MYSQLI_ASSOC);

} else {
    // Récupérer les informations du profil demandé depuis la base de données
    $resultatProfil = $conn->query("SELECT * FROM utilisateurs WHERE id_users = " . $idProfil);
    $profilDemande = $resultatProfil->fetch_assoc();
    $pseudoProfil = $profilDemande['pseudo'];
    $photoProfil = $profilDemande['photo_profil'];

    // Récupérer tous les posts du profil demandé, triés du plus récent au plus ancien
    $resultatPostsProfil = $conn->query("SELECT * FROM posts WHERE utilisateur_id = " . $idProfil . " ORDER BY created_at DESC");
    $mesPosts = $resultatPostsProfil->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <!-- Inclure vos fichiers CSS et JS ici -->
    <style>
        .post {
            border: 1px solid #ccc;
            margin: 10px 0;
            padding: 10px;
        }

        .post img, .post video {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>

    <?php include('./preset/header.php'); // Inclure votre en-tête ?>

    <div class="profile-container">
        <?php if ($estMonProfil) : // Vérifier si c'est le profil de l'utilisateur actuel ?>
        <!-- Afficher la photo du profil, le pseudo, et la date de création du profil -->
        <div class="post-header">
            <img src="<?php echo $photoProfil; ?>" alt="Ma Photo de Profil">
            <span><?php echo $monPseudo; ?></span>
            <span><?php echo $monProfil['created_at']; ?></span>
        </div>
        <?php else : // Profil de quelqu'un d'autre ?>
        <!-- Afficher la photo du profil, le pseudo, et la date de création du profil du profil demandé -->
        <div class="post-header">
            <img src="<?php echo $photoProfil; ?>" alt="Photo de Profil de <?php echo $pseudoProfil; ?>">
            <span><?php echo $pseudoProfil; ?></span>
            <span><?php echo $profilDemande['created_at']; ?></span>
        </div>
        <?php endif; ?>

        <!-- Afficher tous les posts du profil -->
        <?php foreach ($mesPosts as $post) : ?>
        <div class="post">
            <!-- Afficher la photo du profil, le pseudo, et la date du post -->
            <div class="post-header">
                <img src="<?php echo $photoProfil; ?>" alt="Photo de Profil de <?php echo $pseudoProfil; ?>">
                <span><?php echo $pseudoProfil; ?></span>
                <span><?php echo $post['created_at']; ?></span>
            </div>
            
            <!-- Afficher le contenu du post -->
            <p><?php echo $post['contenu']; ?></p>

            <!-- Afficher le média (image ou vidéo) -->
            <?php if (!empty($post['media_path'])) : ?>
                <?php if (pathinfo($post['media_path'], PATHINFO_EXTENSION) === 'mp4') : ?>
                    <video controls>
                        <source src="<?php echo $post['media_path']; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else : ?>
                    <img src="<?php echo $post['media_path']; ?>" alt="Média du Post">
                <?php endif; ?>
            <?php endif; ?>

            <!-- Afficher le nombre de likes et le bouton de like -->
            <?php
                $postId = $post['id_posts'];
                $resultatLikes = $conn->query("SELECT COUNT(*) AS nb_likes FROM likes WHERE post_id = $postId");
                $likes = $resultatLikes->fetch_assoc();
            ?>
            <div class="like-section">
                <button class="like-btn"><?php echo $likes['nb_likes']; ?> Likes</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php include('./preset/footer.php'); // Inclure votre pied de page ?>

</body>

</html>
