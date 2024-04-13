<?php
session_start();
include('db.php');

// Récupérer l'ID du profil à afficher, soit depuis la requête GET, soit depuis la session
$idProfil = isset($_GET['id']) ? $_GET['id'] : $_SESSION['utilisateur_id'];

$estMonProfil = false;
$monPseudo = $monProfil = $photoProfil = $pseudoProfil = $profilDemande = null;

// Vérifier si c'est le profil de l'utilisateur actuel
if ($_SESSION['utilisateur_id'] == $idProfil) {
    $estMonProfil = true;
    // Récupérer les informations de l'utilisateur actuel depuis la base de données
    $resultat = $conn->query("SELECT * FROM utilisateurs WHERE id_users = " . $_SESSION['utilisateur_id']);
    $monProfil = $resultat->fetch_assoc();
    $monPseudo = $monProfil['pseudo'];
    $photoProfil = $monProfil['photo_profil'];
    // Récupérer tous les posts de l'utilisateur actuel
    $resultatPosts = $conn->query("SELECT * FROM posts WHERE utilisateur_id = " . $_SESSION['utilisateur_id'] . " ORDER BY created_at DESC");
    $mesPosts = $resultatPosts->fetch_all(MYSQLI_ASSOC);
} else {
    // Récupérer les informations du profil demandé depuis la base de données
    $resultatProfil = $conn->query("SELECT * FROM utilisateurs WHERE id_users = " . $idProfil);
    $profilDemande = $resultatProfil->fetch_assoc();
    $pseudoProfil = $profilDemande['pseudo'];
    $photoProfil = $profilDemande['photo_profil'];
    // Récupérer tous les posts du profil demandé
    $resultatPostsProfil = $conn->query("SELECT * FROM posts WHERE utilisateur_id = " . $idProfil . " ORDER BY created_at DESC");
    $mesPosts = $resultatPostsProfil->fetch_all(MYSQLI_ASSOC);
}

// Récupérer le nombre d'abonnés du compte
$nbAbonnes = $conn->query("SELECT COUNT(*) AS nb_abonnes FROM abonnements WHERE id_abonnement_a = " . $idProfil)->fetch_assoc()['nb_abonnes'];

// Récupérer le nombre d'abonnements du compte
$nbAbonnements = $conn->query("SELECT COUNT(*) AS nb_abonnements FROM abonnements WHERE id_abonne = " . $idProfil)->fetch_assoc()['nb_abonnements'];

// Vérifier si l'utilisateur actuel est déjà abonné au profil affiché
$estAbonne = false;
if (!$estMonProfil && isset($_SESSION['utilisateur_id'])) {
    $resultatAbonnement = $conn->query("SELECT * FROM abonnements WHERE id_abonne = " . $_SESSION['utilisateur_id'] . " AND id_abonnement_a = " . $idProfil);
    if ($resultatAbonnement->num_rows > 0) {
        $estAbonne = true;
    }
}

// Gérer l'abonnement ou la désinscription
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'sabonner') {
        // S'abonner au profil
        $conn->query("INSERT INTO abonnements (id_abonne, id_abonnement_a) VALUES (" . $_SESSION['utilisateur_id'] . ", " . $idProfil . ")");
        header('Location: profil2.php?id=' . $idProfil);
        exit();
    } elseif ($_POST['action'] === 'desabonner') {
        // Se désabonner du profil
        $conn->query("DELETE FROM abonnements WHERE id_abonne = " . $_SESSION['utilisateur_id'] . " AND id_abonnement_a = " . $idProfil);
        header('Location: profil2.php?id=' . $idProfil);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauteam - Profil utilisateur</title>
    <link rel="stylesheet" href="./css/color.css">
    <link rel="stylesheet" href="./css/profil.css">                
    <link rel="stylesheet" href="./css/profil2.css">    
    <link rel="icon" href="./media/logo.png">            
</head>

<body>

    <?php include('./preset/header.php'); ?>

    <div class="contenair-profil">
        <?php if ($estMonProfil) : ?>
        <!-- Afficher les informations du profil de l'utilisateur actuel -->
        <div class="post-header profile-header">
            <img src="<?php echo $photoProfil; ?>" alt="Ma Photo de Profil" class="profile-picture">
            <div class="droite">
                <span class="profile-username"><?php echo $monPseudo; ?></span>
                <span class="abonne-info"><?php echo $nbAbonnes; ?> Abonnés | <?php echo $nbAbonnements; ?> Abonnements</span>
            </div>
        </div>
        <?php else : ?>
        <!-- Afficher les informations du profil demandé -->
        <div class="post-header profile-header">
            <img src="<?php echo $photoProfil; ?>" alt="Photo de Profil de <?php echo $pseudoProfil; ?>" class="profile-picture">
            <div class="post-owner-info">
                <span class="pseudo"><?php echo $pseudoProfil; ?><br><br></span>
                <span class="abonne-info"><?php echo $nbAbonnes; ?> Abonnés | <?php echo $nbAbonnements; ?> Abonnements <br><br></span>
                <form method="post">
                    <?php if ($estAbonne) : ?>
                    <!-- Bouton pour se désabonner -->
                    <button type="submit" class="subscribe-btn" name="action" value="desabonner">Se désabonner</button>
                    <?php else : ?>
                    <!-- Bouton pour s'abonner -->
                    <button type="submit" class="subscribe-btn" name="action" value="sabonner">S'abonner</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="profile-container">
        <?php if (isset($mesPosts) && !empty($mesPosts)) : ?>
        <!-- Afficher tous les posts du profil -->
        <?php foreach ($mesPosts as $post) : ?>
        <?php
            // Récupérer les informations de l'utilisateur qui a posté ce message
            $resultatUtilisateur = $conn->query("SELECT * FROM utilisateurs WHERE id_users = " . $post['utilisateur_id']);
            $infoUtilisateur = $resultatUtilisateur->fetch_assoc();
            $pseudoPosteur = $infoUtilisateur['pseudo'];
        ?>
        <div class="post">
            <!-- Afficher les informations du post -->
            <div class="post-header post-info">
                <img src="<?php echo $infoUtilisateur['photo_profil']; ?>" alt="Photo de Profil de <?php echo $pseudoPosteur; ?>" class="post-picture">
                <div class="post-owner-info">
                    <span class="pseudo"><?php echo $pseudoPosteur; ?></span>
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

            <!-- Afficher le nombre de likes et le bouton de like -->
            <?php
            $postId = $post['id_posts'];
            $resultatLikes = $conn->query("SELECT COUNT(*) AS nb_likes FROM likes WHERE post_id = $postId");
            $likes = $resultatLikes->fetch_assoc();
            ?>
            <div class="like-section">
                <button id="likeBtn<?php echo $postId; ?>" class="like-btn" onclick="likePost(<?php echo $postId; ?>)">
                    <?php echo $likes['nb_likes']; ?> Likes
                </button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else : ?>
        <!-- Afficher un message si aucun post n'est disponible -->
        <p>Aucun post disponible.</p>
        <?php endif; ?>
    </div>

    <?php include('./preset/footer.php'); ?>

</body>

</html>
