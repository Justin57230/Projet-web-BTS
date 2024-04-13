<!DOCTYPE html>
<?php
// Démarrage de la session
session_start()
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer Utilisateurs</title>
    <!-- Inclusion des fichiers CSS -->
    <link rel="stylesheet" href="./css/color.css">
    <link rel="stylesheet" href="./css/gerer_utilisateur.css">
</head>
<body>
<?php include("./preset/header.php") // Inclusion de l'en-tête ?>

    <div class="container">
        <h1>Gérer Utilisateurs</h1>
        <!-- Formulaire de recherche d'utilisateur -->
        <form action="" method="GET">
            <input type="text" name="search_term" placeholder="Entrez votre recherche..." class="input-text">
            <!-- Sélection de la catégorie de recherche -->
            <select name="search_category" class="input-text">
                <option value="nom">Nom</option>
                <option value="prenom">Prénom</option>
                <option value="pseudo">Pseudo</option>
                <option value="email">Email</option>
                <!-- Vous pouvez ajouter d'autres options selon vos besoins -->
            </select>
            <button type="submit" class="button">Rechercher</button>
        </form>
        <!-- Tableau pour afficher les utilisateurs -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo de Profil</th>
                    <th>Pseudo</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Inclusion de la connexion à la base de données
                include 'db.php';

                // Vérification s'il y a une action à effectuer
                if (isset($_GET['action']) && isset($_GET['id'])) {
                    $action = $_GET['action'];
                    $userId = $_GET['id'];

                    // Vérification de l'action demandée
                    switch ($action) {
                        case 'changer_role':
                            if (isset($_GET['role'])) {
                                $newRole = $_GET['role'];
                                // Exécution de la requête SQL pour mettre à jour le rôle de l'utilisateur
                                $updateQuery = "UPDATE utilisateurs SET role = '$newRole' WHERE id_users = $userId";
                                if ($conn->query($updateQuery) === TRUE) {
                                    echo "Le rôle de l'utilisateur a été mis à jour avec succès.";
                                } else {
                                    echo "Erreur lors de la mise à jour du rôle de l'utilisateur: " . $conn->error;
                                }
                            } else {
                                echo "Le rôle n'a pas été spécifié.";
                            }
                            break;
                        case 'supprimer_utilisateur':
                            // Exécution de la requête SQL pour supprimer l'utilisateur
                            $deleteQuery = "DELETE FROM utilisateurs WHERE id_users = $userId";
                            if ($conn->query($deleteQuery) === TRUE) {
                                echo "L'utilisateur a été supprimé avec succès.";
                            } else {
                                echo "Erreur lors de la suppression de l'utilisateur: " . $conn->error;
                            }
                            break;
                        default:
                            echo "Action non reconnue.";
                    }
                }

                // Fonction pour obtenir tous les utilisateurs
                function getAllUsers() {
                    global $conn;
                    $users = array();

                    $query = "SELECT * FROM utilisateurs";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $users[] = $row;
                        }
                    }

                    return $users;
                }

                // Fonction pour rechercher des utilisateurs
                function searchUsers($searchTerm, $searchCategory) {
                    global $conn;
                    $users = array();

                    // Construction de la requête SQL en fonction de la catégorie de recherche
                    $query = "SELECT * FROM utilisateurs WHERE $searchCategory LIKE '%$searchTerm%'";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $users[] = $row;
                        }
                    }

                    return $users;
                }

                // Récupération de tous les utilisateurs par défaut ou des utilisateurs filtrés si des paramètres de recherche sont fournis
                if (isset($_GET['search_term']) && isset($_GET['search_category'])) {
                    $searchTerm = $_GET['search_term'];
                    $searchCategory = $_GET['search_category'];
                    $users = searchUsers($searchTerm, $searchCategory);

                    // Affichage d'un message si aucun utilisateur n'est trouvé
                    if (empty($users)) {
                        echo "<tr><td colspan='6'>Aucun utilisateur trouvé.</td></tr>";
                    }
                } else {
                    $users = getAllUsers();
                }

                // Affichage des utilisateurs dans le tableau HTML
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>{$user['id_users']}</td>";
                    echo "<td><img src='{$user['photo_profil']}' alt='Photo de profil' class='profile-picture'></td>";
                    echo "<td>{$user['pseudo']}</td>";
                    echo "<td>{$user['nom']}</td>";
                    echo "<td>{$user['prenom']}</td>";
                    echo "<td>";
                    if ($user['role'] == 'user') {
                        echo "<a href='gerer_utilisateurs.php?action=changer_role&id={$user['id_users']}&role=admin' class='button'>Passer en Admin</a> | ";
                    } elseif ($user['role'] == 'admin') {
                        echo "<a href='gerer_utilisateurs.php?action=changer_role&id={$user['id_users']}&role=user' class='button'>Passer en Utilisateur</a> | ";
                    }
                    echo "<a href='gerer_utilisateurs.php?action=supprimer_utilisateur&id={$user['id_users']}' class='button'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
