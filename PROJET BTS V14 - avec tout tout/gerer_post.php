<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer Posts</title>
    <link rel="stylesheet" href="./css/color.css">
    <link rel="stylesheet" href="./css/gerer_post.css">
</head>

<body>
    <?php 
    session_start();
    include('./preset/header.php')?>
    <div class="container">
        <h1>Gérer Posts</h1>
        <!-- Tableau pour afficher les posts -->
        <table class="post-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Contenu</th>
                    <th>Média</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include database connection
                include 'db.php';

                // Vérifiez s'il y a une action à effectuer
                if (isset($_GET['action']) && isset($_GET['id'])) {
                    $action = $_GET['action'];
                    $postId = $_GET['id'];

                    // Vérifiez quelle action est demandée
                    switch ($action) {
                        case 'supprimer_post':
                            // Exécutez la requête SQL pour supprimer le post
                            $deleteQuery = "DELETE FROM posts WHERE id_posts = $postId";
                            if ($conn->query($deleteQuery) === TRUE) {
                                echo "Le post a été supprimé avec succès.";
                            } else {
                                echo "Erreur lors de la suppression du post: " . $conn->error;
                            }
                            break;
                        default:
                            echo "Action non reconnue.";
                    }
                }

                // Function to get all posts
                function getAllPosts() {
                    global $conn;
                    $posts = array();

                    $query = "SELECT posts.id_posts, utilisateurs.pseudo, posts.contenu, posts.media_path FROM posts INNER JOIN utilisateurs ON posts.utilisateur_id = utilisateurs.id_users";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $posts[] = $row;
                        }
                    }

                    return $posts;
                }

                // Get all posts
                $posts = getAllPosts();

                // Display posts in HTML table
                foreach ($posts as $post) {
                    echo "<tr>";
                    echo "<td>{$post['pseudo']}</td>"; // Pseudo de l'utilisateur
                    echo "<td>{$post['contenu']}</td>"; // Contenu du post

                    // Affichage du media s'il existe
                    if (!empty($post['media_path'])) {
                        echo "<td><a href='{$post['media_path']}'>Voir Media</a></td>";
                    } else {
                        echo "<td>Aucun media</td>";
                    }

                    echo "<td>";
                    echo "<a href='gerer_post.php?action=supprimer_post&id={$post['id_posts']}' class='button'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
