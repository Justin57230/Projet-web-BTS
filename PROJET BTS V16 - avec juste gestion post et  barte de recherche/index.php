<?php
    // Démarrer la session sur chaque page où vous avez besoin d'accéder à $_SESSION
    session_start();

    // Vérifier si l'utilisateur est connecté
    $utilisateur_connecte = false; // Par défaut, l'utilisateur n'est pas connecté

    if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte'] === true) {
        $utilisateur_connecte = true; // Mettez à jour la variable si l'utilisateur est connecté
    }
    ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CommunauTeam - Accueil</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="icon" href="./media/logo.png">

</head>

<body>
    <!-- Header -->
    <?php
        include('./preset/header.php');
    ?>

    <!-- Bienvenue Section -->
    <div class="grande-partie">
    <div class="welcome-section">
        <h1>Bienvenue sur CommunauTeam - Où la Communauté s'épanouit !</h1>
        <p>Bienvenue sur CommunauTeam, l'endroit où la connexion, la créativité et l'échange d'idées se rencontrent.
            Nous sommes plus qu'un simple site web ; nous sommes une communauté florissante, un espace conçu pour réunir
            des esprits passionnés et curieux de tous horizons.</p>
    </div>

    <!-- Présentation Section -->
    <div class="presentation-section">
        <h2>Qui Sommes-Nous ?</h2>
        <p>À CommunauTeam, nous croyons en la force de la communauté. Notre plateforme a été créée avec la vision de
            fournir un espace accueillant où chacun peut partager ses passions, découvrir de nouvelles idées, et tisser
            des liens significatifs avec d'autres membres partageant les mêmes centres d'intérêt.</p>
    </div>

    <!-- Fonctionnalités Section -->
    <div class="features-section">
        <h2>Nos principales fonctionnalités</h2>
        <h3>🌐 Fil d'Actualité Personnalisé</h3>
        <p>Explorez un fil d'actualité dynamique, conçu selon vos préférences. Découvrez du contenu qui vous intéresse,
            des discussions stimulantes et des médias captivants.</p>

        <h3>💬 Forums et Discussions</h3>
        <p>Participez à des conversations enrichissantes sur des forums spécialisés. Créez votre propre espace de
            discussion ou rejoignez des communautés existantes pour échanger des idées avec des personnes partageant vos
            passions.</p>

        <h3>💌 Messagerie Instantanée</h3>
        <p>Connectez-vous instantanément avec d'autres membres grâce à notre système de messagerie privée. Créez des
            groupes, partagez des moments, et construisez des relations solides.</p>
    </div>

    <!-- Rejoignez-nous Aujourd'hui (si l'utilisateur n'est pas connecté) -->
    <?php if ($utilisateur_connecte == false) : ?>
        <div class="join-section">
            <h2>Rejoignez-nous Aujourd'hui !</h2>
            <p>Que vous soyez passionné par la musique, les arts, la technologie, ou tout autre domaine, CommunauTeam est
                l'endroit idéal pour rencontrer des personnes partageant vos intérêts. Inscrivez-vous dès maintenant, créez
                votre profil unique, et plongez dans une expérience communautaire qui éveillera votre enthousiasme et
                élargira vos horizons.</p>

            <div class="join-btn">
                <h4>Rejoignez CommunauTeam dès maintenant et faites partie de quelque chose de grand ! <a class="btn-inscription" href="Inscription.php">Jm'inscris !</a></h4>
            </div>
        </div>
    <?php endif; ?>

    <!-- News Section -->
    <div class="news-section">
        <h2>News du site :</h2>
        <p>Blah blah blah...</p>
        <!-- Ajoutez d'autres actualités si nécessaire -->
    </div>
    </div>

    <!-- Footer -->
    <?php
        include('./preset/footer.php');
    ?>

</body>

</html>
