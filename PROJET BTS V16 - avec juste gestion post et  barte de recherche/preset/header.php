<?php
include 'db.php';

// Fonction pour récupérer le chemin de la photo de profil de l'utilisateur connecté
function getProfilePicturePath($userId) {
    global $conn;
    $query = "SELECT photo_profil FROM utilisateurs WHERE id_users = '$userId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['photo_profil'];
    }

    return null;
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte']) {
    $userId = $_SESSION['utilisateur_id'];
    $profilePicturePath = getProfilePicturePath($userId);
    $menuContent = '
        <a class="header-btn" href="profil.php">Mon Profil</a>
        <!--<a class="header-btn" href="parametres.php">Paramètres</a>-->
        <a class="header-btn" href="./preset/deconnexion.php">Déconnexion</a>
    ';
    
    // Vérifier si l'utilisateur est administrateur
    $query = "SELECT role FROM utilisateurs WHERE id_users = '$userId'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['role'] === 'admin') {
            // Utilisateur est un administrateur, afficher les sections supplémentaires
            $menuContent .= '
                <a class="header-btn" href="gerer_utilisateurs.php">Gèrer les utilisateurs</a>
                <a class="header-btn" href="gerer_post.php">Gèrer les posts</a>
            ';
        }
    }
} else {
    // L'utilisateur n'est pas connecté, afficher le bouton Se Connecter
    $menuContent = '<a class="se-connecter" href="connexion.php">Se Connecter</a>';
}
?>

<!-- html -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/poste.css">
</head>

<body>
    <header>
        <div class="logo-container">
            <img src="./media/logo.png" alt="Logo">
        </div>
        <nav>
            <ul>
                <li><a class="header-btn" href="index.php">Accueil</a></li>
                <?php
                if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte']) {
                    echo '<li><a class="header-btn" href="pourtoi.php">Pour Toi</a></li>';
                    echo '<li><a class="header-btn" href="abonnement.php">Abonnement</a></li>';
                    echo '<li><a class="header-btn" href="poste.php">Poster</a></li>';
                    echo '<li><a class="header-btn" href="recherche.php"><img src="./media/loupe.png" alt="Recherche"></a></li>';
                }
                ?>
            </ul>
        </nav>
        <div class="profil-menu">
            <?php
            if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte']) {
                echo '<div class="pp-container" onmouseover="afficherMenu()" onmouseout="cacherMenu()">
                        <img src="' . $profilePicturePath . '" alt="Photo de profil">
                        <div class="menu-deroulant" id="menuDeroulant">' . $menuContent . '</div>
                    </div>';
            } else {
                echo $menuContent;
            }
            ?>
        </div>
    </header>
</body>

<!-- JavaScript -->
<script>
    var timer;

    function afficherMenu() {
        clearTimeout(timer);
        document.getElementById("menuDeroulant").style.display = "block";
    }

    function cacherMenu() {
        timer = setTimeout(function() {
            document.getElementById("menuDeroulant").style.display = "none";
        }, 800);
    }
</script>


</html>
