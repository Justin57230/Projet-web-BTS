<?php
// Configuration pour afficher toutes les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion du fichier de connexion à la base de données
include 'db.php';

// Initialisation des variables
$message = ""; // Variable pour stocker le message d'erreur
$inscription_reussie = false; // Variable pour indiquer si l'inscription est réussie

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification si la case à cocher de la clause de consentement est cochée
    if (!isset($_POST['consent'])) {
        $message = "Veuillez accepter les Conditions d'utilisation, la Politique de confidentialité et la Politique relative aux cookies.";
    } else {
        // Récupération des données du formulaire
        $pseudo = $_POST['pseudo'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];
        $confirmation_mot_de_passe = $_POST['confirmation_mot_de_passe'];

        // Vérification si le mot de passe et la confirmation sont identiques
        if ($mot_de_passe !== $confirmation_mot_de_passe) {
            $message = "Les mots de passe ne correspondent pas.";
        }

        // Vérification si l'email est déjà lié à un compte
        $check_email_query = "SELECT * FROM utilisateurs WHERE LOWER(email) = LOWER('$email')";
        $check_email_result = $conn->query($check_email_query);

        // Vérification si la requête s'est exécutée avec succès
        if ($check_email_result !== false) {
            // Vérification s'il y a des lignes dans le jeu de résultats
            if ($check_email_result->num_rows > 0) {
                $message = "Cet email est déjà lié à un compte.";
            }
        } else {
            // Gestion du cas où l'exécution de la requête a échoué
            $message = "Erreur lors de la vérification de l'email.";
        }

        // Si aucune erreur, procéder à l'inscription
        if (empty($message)) {
            // Hashage du mot de passe
            $mot_de_passe_hash = hash('sha256', $mot_de_passe);

            // Définition de la photo de profil par défaut
            $photo_profil_default = "./media/default_pp.jpg";

            // Requête d'insertion de l'utilisateur dans la base de données
            $insert_user_query = "INSERT INTO utilisateurs (pseudo, nom, prenom, email, mot_de_passe, photo_profil) VALUES ('$pseudo', '$nom', '$prenom', '$email', '$mot_de_passe_hash', '$photo_profil_default')";

            // Exécution de la requête d'insertion
            if ($conn->query($insert_user_query) === TRUE) {
                $inscription_reussie = true;
            } else {
                $message = "Une erreur lors de l'inscription, veuillez réessayer ultérieurement.";
                $inscription_reussie = false; // Mettre à jour la variable en conséquence
            }
        }
    }
}
?>

<!-- *************************************HTML************************************** -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauteam - Inscription</title>
    <link rel="stylesheet" href="./css/inscription.css">
    <link rel="stylesheet" href="./css/color.css">
    <link rel="icon" href="./media/logo.png">
</head>

<body>
    <div class="container">
        <div class="titre">
            <h1>Je m'inscris !</h1>
        </div>
        
        <!-- Formulaire d'inscription -->
        <form class="form-group" method="post" action="inscription.php">
            <label for="pseudo">Pseudo</label>
            <input type="text" name="pseudo" id="pseudo" required>

            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" required>

            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe" required>

            <label for="confirmation_mot_de_passe">Confirmation du mot de passe</label>
            <input type="password" name="confirmation_mot_de_passe" id="confirmation_mot_de_passe" required>

            <!-- Clause de consentement -->
            <label class="consent-label">
                <div class="input">
                    <input type="checkbox" name="consent">
                    <span class="checkmark"></span>
                </div>
                <div class="condition">
                    En cochant cette, vous acceptez les
                    <a href="#" target="_blank">Conditions d’utilisation</a>,
                    la <a href="#" target="_blank">Politique de confidentialité</a>,
                    et la <a href="#" target="_blank">Politique relative aux cookies</a> de Communauteam.
                </div>
            </label>

            <!-- Bouton de soumission -->
            <input class="submit-button" type="submit" value="S'inscrire">
        </form>

        <!-- Message de succès -->
        <p class="success-message">
            <?php
            if ($inscription_reussie) {
                echo "Inscription réussie! Vous pouvez maintenant vous <a class='link' href='connexion.php'>connecter</a>.";
            }
            ?>
        </p>

        <!-- Lien pour la connexion -->
        <p class="link">Déjà inscrit ? <a href="connexion.php">Connectez-vous ici</a></p>

        <!-- Affichage du message d'erreur -->
        <?php
        if ($message !== "") {
            echo "<p class='error-message'>$message</p>";
        }
        ?>
    </div>
</body>

</html>
