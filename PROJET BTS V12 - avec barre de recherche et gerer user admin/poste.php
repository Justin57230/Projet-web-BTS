<?php
// Démarrer la session
session_start();

// Inclure le fichier de connexion à la base de données
include 'db.php';

// Récupérer l'ID de l'utilisateur à partir de la session, s'il existe
$utilisateur_id = isset($_SESSION['utilisateur_id']) ? $_SESSION['utilisateur_id'] : '';

// Vérifier si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le champ 'contenu' est défini dans le tableau $_POST
    $contenu = isset($_POST["contenu"]) ? $_POST["contenu"] : '';

    // Debugging: Afficher les valeurs pour vérification
    echo "utilisateur_id: $utilisateur_id<br>";
    echo "contenu: $contenu<br>";

    // Initialiser $media_path à NULL
    $media_path = null;

    // Traitement du téléchargement de fichier (image ou vidéo) s'il est défini
    if (isset($_FILES["media"]) && $_FILES["media"]["error"] == 0) {
        $allowed_extensions = ['jpeg', 'jpg', 'png', 'mp4', 'mkv', 'mpeg'];
        
        // Obtenir l'extension du fichier téléchargé et la convertir en minuscules
        $media_extension = strtolower(pathinfo($_FILES["media"]["name"], PATHINFO_EXTENSION));

        // Vérifier si l'extension est autorisée
        if (in_array($media_extension, $allowed_extensions)) {
            $media_name = $_FILES["media"]["name"];
            $media_tmp = $_FILES["media"]["tmp_name"];

            // Utiliser l'ID du post comme nom de fichier pour éviter les conflits
            $media_path = "publication_media/" . uniqid() . '_' . basename($media_name);

            // Déplacer le fichier téléchargé vers le répertoire de stockage
            move_uploaded_file($media_tmp, $media_path);
        } else {
            // Afficher une erreur si le format de fichier n'est pas pris en charge
            echo "Erreur : Format de fichier non supporté. Veuillez télécharger une image (JPEG, PNG, JPG) ou une vidéo (MP4, MKV, MPEG).";
            exit(); // Arrêter le script si le type de fichier n'est pas pris en charge
        }
    }

    // Debugging: Afficher media_path pour vérification
    echo "media_path: $media_path<br>";

    // Utiliser la fonction COALESCE pour définir la valeur à NULL si $media_path est vide
    $media_path = !empty($media_path) ? "'$media_path'" : 'NULL';

    // Vérifier si l'ID de l'utilisateur et le contenu sont définis
    if ($utilisateur_id !== '' && $contenu !== '') {
        // Insérer les données dans la base de données
        $sql = "INSERT INTO posts (utilisateur_id, contenu, media_path) VALUES ('$utilisateur_id', '$contenu', $media_path)";

        // Exécuter la requête SQL
        if ($conn->query($sql) === TRUE) {
            // Rediriger vers index.php après la publication réussie
            header("Location: index.php");
            exit(); // Assurez-vous que le script s'arrête ici pour éviter toute exécution supplémentaire
        } else {
            // Afficher une erreur si la requête échoue
            echo "Erreur : " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Afficher une erreur si l'ID de l'utilisateur ou le contenu n'est pas défini
        echo "Erreur : utilisateur_id ou contenu non défini.";
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauteam - Nouveau poste</title>
    <link rel="icon" href="./media/logo.png">
</head>

<body>
    <?php include './preset/header.php'; ?>

    <h2 class="post-title">Créer un Post</h2>

    <form class="post-form" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
        <!-- L'ID de l'utilisateur sera récupéré automatiquement à partir de la session -->
        <input type="hidden" name="utilisateur_id"
            value="<?php echo isset($_SESSION['utilisateur_id']) ? $_SESSION['utilisateur_id'] : ''; ?>">

        <label for="contenu" class="post-label">Contenu:</label>
        <textarea name="contenu" class="post-content" required></textarea><br>

        <!-- Champ de téléchargement de fichiers pour l'image ou la vidéo -->
        <label for="media" class="post-label">Image ou Vidéo:</label>
        <input type="file" name="media" class="post-media"><br>

        <input type="submit" value="Créer Post" class="post-button">
    </form>

    <?php include './preset/footer.php'; ?>
</body>

</html>
