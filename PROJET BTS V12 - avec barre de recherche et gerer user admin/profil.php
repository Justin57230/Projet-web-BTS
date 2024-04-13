<?php
// Démarrer la session
session_start();

// Inclure votre connexion à la base de données
include('db.php');

// Récupérer l'ID du profil à afficher
$idProfil = $_SESSION['utilisateur_id'];

// Définir des variables pour éviter les erreurs "Undefined variable"
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

// Récupérer le nombre d'abonnés
$resultatAbonnes = $conn->query("SELECT COUNT(*) AS nb_abonnes FROM abonnements WHERE id_abonnement_a = " . $idProfil);
$abonnes = $resultatAbonnes->fetch_assoc();

// Récupérer le nombre d'abonnements
$resultatAbonnements = $conn->query("SELECT COUNT(*) AS nb_abonnements FROM abonnements WHERE id_abonne = " . $idProfil);
$abonnements = $resultatAbonnements->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauteam - Mon Profil</title>
    <link rel="stylesheet" href="./css/color.css">
    <link rel="stylesheet" href="./css/profil.css">
    <link rel="icon" href="./media/logo.png">
</head>

<body>
    <?php include('./preset/header.php'); // Inclure votre en-tête ?>

    <div class="contenair-profil">
        <?php if ($estMonProfil) : // Vérifier si c'est le profil de l'utilisateur actuel ?>
        <!-- Afficher les informations du profil de l'utilisateur actuel -->
        <div class="post-header profile-header">
            <img src="<?php echo $photoProfil; ?>" alt="Ma Photo de Profil" class="profile-picture">
            <div class="droite">
                <span class="profile-username"><?php echo $monPseudo; ?></span>
                <!-- Afficher le nombre d'abonnés et d'abonnements -->
                <span class="abonne-info">Abonnés: <?php echo $abonnes['nb_abonnes']; ?></span>
                <span class="abonne-info">Abonnements: <?php echo $abonnements['nb_abonnements']; ?></span>
                <!-- Ajouter un bouton pour éditer le profil -->
                <button class="edit-profile-btn" onclick="editProfile()">Éditer le profil</button>
            </div>
        </div>
        <?php else : // Profil de quelqu'un d'autre ?>
        <!-- Afficher les informations du profil demandé -->
        <div class="post-header profile-header">
            <img src="<?php echo $photoProfil; ?>" alt="Photo de Profil de <?php echo $pseudoProfil; ?>"
                class="profile-picture">
            <div class="post-owner-info">
                <span class="pseudo"><?php echo $pseudoProfil; ?></span>
                <!-- Afficher le nombre d'abonnés et d'abonnements -->
                <span class="abonne-info">Abonnés: <?php echo $abonnes['nb_abonnes']; ?></span>
                <span class="abonne-info">Abonnements: <?php echo $abonnements['nb_abonnements']; ?></span>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="profile-container">
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
                <img src="<?php echo $infoUtilisateur['photo_profil']; ?>"
                    alt="Photo de Profil de <?php echo $pseudoPosteur; ?>" class="post-picture">
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
    </div>

    <?php include('./preset/footer.php'); // Inclure votre pied de page ?>

    <!-- Inclure vos fichiers JS ici -->
    <script>
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

    function editProfile() {
        window.location.href =
        'edit_profile.php'; 
    }
    </script>

</body>

</html>
