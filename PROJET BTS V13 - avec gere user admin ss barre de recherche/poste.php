<?php
session_start(); // Démarrer la session
include 'db.php';

$utilisateur_id = isset($_SESSION['utilisateur_id']) ? $_SESSION['utilisateur_id'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'contenu' is set in the $_POST array
    $contenu = isset($_POST["contenu"]) ? $_POST["contenu"] : '';

    // Debugging: Print values for checking
    echo "utilisateur_id: $utilisateur_id<br>";
    echo "contenu: $contenu<br>";

    // Initialisation de $media_path à NULL
    $media_path = null;

// Traitement du téléchargement de fichier (image ou vidéo)
if (isset($_FILES["media"]) && $_FILES["media"]["error"] == 0) {
    $allowed_extensions = ['jpeg', 'jpg', 'png', 'mp4', 'mkv', 'mpeg'];
    
    $media_extension = strtolower(pathinfo($_FILES["media"]["name"], PATHINFO_EXTENSION));

    if (in_array($media_extension, $allowed_extensions)) {
        $media_name = $_FILES["media"]["name"];
        $media_tmp = $_FILES["media"]["tmp_name"];

        // Utilisation de l'ID du post comme nom de fichier
        $media_path = "publication_media/" . uniqid() . '_' . basename($media_name);

        move_uploaded_file($media_tmp, $media_path);
    } else {
        echo "Erreur : Format de fichier non supporté. Veuillez télécharger une image (JPEG, PNG, JPG) ou une vidéo (MP4, MKV, MPEG).";
        exit(); // Stop the script if the file type is not supported
    }
}

// Debugging: Print media_path for checking
    echo "media_path: $media_path<br>";

    // Utilisation de la fonction COALESCE pour définir la valeur à NULL si $media_path est vide
    $media_path = !empty($media_path) ? "'$media_path'" : 'NULL';

    if ($utilisateur_id !== '' && $contenu !== '') {
        // Insertion des données dans la base de données
        $sql = "INSERT INTO posts (utilisateur_id, contenu, media_path) VALUES ('$utilisateur_id', '$contenu', $media_path)";

        if ($conn->query($sql) === TRUE) {
            // Redirection vers index.html après la publication réussie
            header("Location: index.php");
            exit(); // Assure que le script s'arrête ici pour éviter toute exécution supplémentaire
        } else {
            echo "Erreur : " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Erreur : utilisateur_id ou contenu non défini.";
    }
}

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