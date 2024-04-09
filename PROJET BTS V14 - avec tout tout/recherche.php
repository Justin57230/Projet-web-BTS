<?php
include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauteam - Recherche d'utilisateurs</title>
    <link rel="stylesheet" href="./css/color.css">
    <link rel="stylesheet" href="./css/rechercher.css">
    <link rel="icon" href="./media/logo.png">
</head>

<body>
    <?php include('./preset/header.php'); // Inclure votre en-tête ?>

    <div class="container">
        <h1>Recherche d'utilisateurs</h1>
        <form method="GET" action="">
            <label for="search">Rechercher un utilisateur :</label>
            <input type="text" id="search" name="search" placeholder="Entrez le pseudo ou le nom">
            <button type="submit">Rechercher</button>
        </form>
        <hr>
        <?php
        // Traitement de la recherche d'utilisateur
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            // Préparer la requête SQL
            $sql = "SELECT * FROM utilisateurs WHERE pseudo LIKE '%$search%' OR nom LIKE '%$search%'";
            // Exécuter la requête
            $result = $conn->query($sql);
            // Vérifier s'il y a des résultats
            if ($result->num_rows > 0) {
                echo "<h2>Résultats de la recherche :</h2>";
                // Afficher les utilisateurs trouvés
                while ($row = $result->fetch_assoc()) {
                    $pseudoPosteur = $row['pseudo'];
                    $photoProfil = $row['photo_profil'];
                    // Déterminer le lien de redirection en fonction de la session
                    $redirectLink = ($_SESSION['utilisateur_id'] == $row['id_users']) ? 'profil.php?id=' . $row['id_users'] : 'profil2.php?id=' . $row['id_users'];
                    // Afficher le pseudo de l'utilisateur et sa photo de profil avec le lien de redirection approprié
                    echo '<div class="post">';
                    echo '<div class="post-header post-info">';
                    echo '<a href="' . $redirectLink . '"><img src="' . $photoProfil . '" alt="Photo de Profil de ' . $pseudoPosteur . '" class="post-picture profile-picture"></a>';
                    echo '<div class="post-owner-info">';
                    echo '<a href="' . $redirectLink . '" class="pseudo profile-username">' . $pseudoPosteur . '</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>Aucun utilisateur trouvé.</p>";
            }
        }
        ?>
    </div>

    <?php include('./preset/footer.php'); // Inclure votre pied de page ?>
</body>

</html>
