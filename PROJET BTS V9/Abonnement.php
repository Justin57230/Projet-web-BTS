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
    <title>CommunauTeam - Abonnement</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/color.css">
</head>

<body>
    <!-- Header -->
    <?php
        include('./preset/header.php');
    ?>

    <!-- Abonnement Section -->
    <div class="grande-partie">
        <div class="abonnement-section">
            <h1>Rejoignez CommunauTeam Premium</h1>
            <p>Améliorez votre expérience CommunauTeam avec notre abonnement Premium. Profitez d'avantages exclusifs conçus pour enrichir votre participation à notre communauté.</p>
            <?php if ($utilisateur_connecte == false) : ?>
                <div class="join-btn">
                    <h4>Connectez-vous ou inscrivez-vous pour accéder à CommunauTeam Premium ! <a class="btn-inscription" href="Inscription.php">Jm'inscris !</a></h4>
                </div>
            <?php else : ?>
                <div class="premium-features">
                    <h2>Avantages du Premium :</h2>
                    <ul>
                        <li>Accès exclusif à des forums Premium</li>
                        <li>Messagerie prioritaire et support dédié</li>
                        <li>Contenus Premium et événements exclusifs</li>
                        <!-- Ajoutez d'autres avantages si nécessaire -->
                    </ul>
                    <div class="subscribe-btn">
                        <h4>Abonnez-vous dès maintenant pour profiter de ces avantages et plus encore ! <a class="btn-abonnement" href="Abonnement.php">S'abonner</a></h4>
                    </div>
                </div>
            <?php endif; ?>
        </div>

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
