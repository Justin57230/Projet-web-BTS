<?php
include 'db.php';

session_start();

$message = "";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_connecte']) || !$_SESSION['utilisateur_connecte']) {
    header('Location: login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

// Changement de pseudo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['changer_pseudo'])) {
    $ancien_pseudo = $_POST['ancien_pseudo'];
    $nouveau_pseudo = $_POST['nouveau_pseudo'];
    $confirmation_pseudo = $_POST['confirmation_pseudo'];

    if ($nouveau_pseudo == $confirmation_pseudo) {
        // Vérifier l'ancien pseudo
        $result = $conn->query("SELECT pseudo FROM utilisateurs WHERE id_users = " . $_SESSION['utilisateur_id']);
        $row = $result->fetch_assoc();

        if ($ancien_pseudo == $row['pseudo']) {
            // Mettre à jour le pseudo dans la base de données
            $conn->query("UPDATE utilisateurs SET pseudo = '$nouveau_pseudo' WHERE id_users = " . $_SESSION['utilisateur_id']);
            $message = "Le pseudo a été changé avec succès.";
        } else {
            $message = "L'ancien pseudo est incorrect.";
        }
    } else {
        $message = "Veuillez vous assurer que les nouveaux pseudos correspondent.";
    }
}

// Changement de mot de passe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['changer_mdp'])) {
    $ancien_mdp = $_POST['ancien_mdp'];
    $nouveau_mdp = $_POST['nouveau_mdp'];
    $confirmation_mdp = $_POST['confirmation_mdp'];

    // Vérifier l'ancien mot de passe
    $result = $conn->query("SELECT mot_de_passe FROM utilisateurs WHERE id_users = " . $_SESSION['utilisateur_id']);
    $row = $result->fetch_assoc();
    $ancien_mdp_hash = hash('sha256', $ancien_mdp);

    if ($ancien_mdp_hash == $row['mot_de_passe']) {
        if ($nouveau_mdp == $confirmation_mdp) {
            // Mettre à jour le mot de passe dans la base de données
            $nouveau_mdp_hash = hash('sha256', $nouveau_mdp);
            $conn->query("UPDATE utilisateurs SET mot_de_passe = '$nouveau_mdp_hash' WHERE id_users = " . $_SESSION['utilisateur_id']);
            $message = "Le mot de passe a été changé avec succès.";
        } else {
            $message = "Veuillez vous assurer que les nouveaux mots de passe correspondent.";
        }
    } else {
        $message = "L'ancien mot de passe est incorrect.";
    }
}

// Suppression du compte
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer_compte'])) {
    $mdp_suppression = $_POST['mdp_suppression'];

    // Vérifier le mot de passe pour supprimer le compte
    $result = $conn->query("SELECT mot_de_passe FROM utilisateurs WHERE id_users = " . $_SESSION['utilisateur_id']);
    $row = $result->fetch_assoc();
    $mdp_suppression_hash = hash('sha256', $mdp_suppression);

    if ($mdp_suppression_hash == $row['mot_de_passe']) {
        // Supprimer le compte de la base de données
        $conn->query("DELETE FROM utilisateurs WHERE id_users = " . $_SESSION['utilisateur_id']);
        session_destroy(); // Détruire la session
        header('Location: index.php'); // Rediriger vers la page d'accueil après la suppression du compte
        exit;
    } else {
        $message = "Le mot de passe pour la suppression du compte est incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Édition du Profil</title>
    <!-- Ajoutez vos liens vers les fichiers CSS ici -->
</head>

<body>

    <?php include('./preset/header.php'); // Inclure votre en-tête ?>

    <div class="profile-container">
        <h2>Édition du Profil</h2>
        <?php if ($message != "") : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <!-- Formulaire de changement de pseudo -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h3>Changer de Pseudo</h3>
            <label for="ancien_pseudo">Ancien Pseudo :</label>
            <input type="text" name="ancien_pseudo" required>
            <label for="nouveau_pseudo">Nouveau Pseudo :</label>
            <input type="text" name="nouveau_pseudo" required>
            <label for="confirmation_pseudo">Confirmation du Nouveau Pseudo :</label>
            <input type="text" name="confirmation_pseudo" required>
            <button type="submit" name="changer_pseudo">Changer de Pseudo</button>
        </form>

        <!-- Formulaire de changement de mot de passe -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h3>Changer de Mot de Passe</h3>
            <label for="ancien_mdp">Ancien Mot de Passe :</label>
            <input type="password" name="ancien_mdp" required>
            <label for="nouveau_mdp">Nouveau Mot de Passe :</label>
            <input type="password" name="nouveau_mdp" required>
            <label for="confirmation_mdp">Confirmation du Nouveau Mot de Passe :</label>
            <input type="password" name="confirmation_mdp" required>
            <button type="submit" name="changer_mdp">Changer de Mot de Passe</button>
        </form>

        <!-- Formulaire de suppression du compte -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h3>Supprimer le Compte</h3>
            <label for="mdp_suppression">Mot de Passe pour la Suppression :</label>
            <input type="password" name="mdp_suppression" required>
            <button type="submit" name="supprimer_compte">Supprimer le Compte</button>
        </form>
    </div>

    <?php include('./preset/footer.php'); // Inclure votre pied de page ?>

</body>

</html>
