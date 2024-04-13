<?php
// Inclusion du fichier de connexion à la base de données
include 'db.php';

// Démarrage de la session
session_start();

$message = ""; // Variable pour stocker le message d'erreur

// Vérifier si le formulaire de connexion a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo_email = $_POST['pseudo_email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Requête pour récupérer les informations de l'utilisateur en fonction du pseudo ou de l'email
    $retrieve_user_query = "SELECT * FROM utilisateurs WHERE pseudo='$pseudo_email' OR email='$pseudo_email'";
    $result = $conn->query($retrieve_user_query);

    // Vérifier si des résultats ont été trouvés
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $mot_de_passe_hash = hash('sha256', $mot_de_passe); // Hasher le mot de passe pour le comparer

        // Vérifier si le mot de passe correspond à celui stocké dans la base de données
        if ($mot_de_passe_hash === $row['mot_de_passe']) {
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['utilisateur_connecte'] = true;
            $_SESSION['utilisateur_id'] = $row['id_users']; // Ajoutez d'autres informations si nécessaire
            header('Location: index.php'); // Redirection vers index.php
            exit; // Assurez-vous de terminer le script après la redirection
        } else {
            // Message d'erreur si le mot de passe est incorrect
            $message = "Le pseudo, l'email ou le mot de passe est incorrect.";
        }
    } else {
        // Message d'erreur si aucun utilisateur correspondant n'a été trouvé
        $message = "Le pseudo, l'email ou le mot de passe est incorrect.";
    }
}
?>

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
