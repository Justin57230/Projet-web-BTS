<?php
include 'db.php';

// Démarrer la session
session_start();

$message = ""; // Variable pour stocker le message d'erreur

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo_email = $_POST['pseudo_email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Requête pour récupérer les informations de l'utilisateur
    $retrieve_user_query = "SELECT * FROM utilisateurs WHERE pseudo='$pseudo_email' OR email='$pseudo_email'";
    $result = $conn->query($retrieve_user_query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $mot_de_passe_hash = hash('sha256', $mot_de_passe);

        // Vérifier si le mot de passe correspond
        if ($mot_de_passe_hash === $row['mot_de_passe']) {
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['utilisateur_connecte'] = true;
            $_SESSION['utilisateur_id'] = $row['id_users']; // Ajoutez d'autres informations si nécessaire
            header('Location: index.php'); // Redirection vers index.php
            exit; // Assurez-vous de terminer le script après la redirection
        } else {
            $message = "Le pseudo, l'email ou le mot de passe est incorrect.";
        }
    } else {
        $message = "Le pseudo, l'email ou le mot de passe est incorrect.";
    }
}
?>

<!-- Reste du code reste inchangé -->

<!-- *************************************HTML************************************** -->



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauteam - Connexion</title>
    <link rel="stylesheet" href="./css/color.css">
    <link rel="stylesheet" href="./css/connexion.css">
    <link rel="icon" href="./media/logo.png">


</head>
<body>
    <div class="container">
        <div class="titre">
            <h1>Je me connecte !</h1>
        </div>
        <!-- Formulaire de connexion -->
        <form class="form-group" method="post" action="connexion.php">
            <label for="pseudo_email">Pseudo ou Email:</label>
            <input type="text" name="pseudo_email" id="pseudo_email" required>

            <label for="mot_de_passe">Mot de passe:</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe" required>

            <input class="submit-button" type="submit" value="Se connecter">
        </form>

        <p class="link">Pas encore de compte ? <a href="./inscription.php">Inscris toi ici</a></p>
    
        <!-- Affichage du message d'erreur -->
        <?php
        if ($message !== "") {
            echo "<p class='error-message'>$message</p>";
        }
        ?>
    </div>

</body>
</html>
