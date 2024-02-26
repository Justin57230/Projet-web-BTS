<?php
session_start(); // Démarrer la session
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilisateur_id = $_SESSION['utilisateur_id'];
    $contenu = $_POST["contenu"];

    // Initialisation de $media_path à NULL
    $media_path = null;

    // Traitement du téléchargement de fichier (image ou vidéo)
    if ($_FILES["media"]["error"] == 0) {
        $media_name = $_FILES["media"]["name"];
        $media_tmp = $_FILES["media"]["tmp_name"];

        // Utilisation de l'ID du post comme nom de fichier
        $media_path = "publication_media/" . uniqid() . '_' . basename($media_name);

        move_uploaded_file($media_tmp, $media_path);
    }

    // Utilisation de la fonction COALESCE pour définir la valeur à NULL si $media_path est vide
    $media_path = $media_path ? "'$media_path'" : 'NULL';

    // Insertion des données dans la base de données
    $sql = "INSERT INTO posts (utilisateur_id, contenu, media_path) VALUES ('$utilisateur_id', '$contenu', $media_path)";

    if ($conn->query($sql) === TRUE) {
        // Redirection vers index.html après la publication réussie
        header("Location: index.php");
        exit(); // Assure que le script s'arrête ici pour éviter toute exécution supplémentaire
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Post</title>
</head>
<body>

<h2>Créer un Post</h2>

<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
    <!-- L'ID de l'utilisateur sera récupéré automatiquement à partir de la session -->
    <input type="hidden" name="utilisateur_id" value="<?php echo $_SESSION['utilisateur_id']; ?>">

    <label for="contenu">Contenu:</label>
    <textarea name="contenu" required></textarea><br>

    <!-- Champ de téléchargement de fichiers pour l'image ou la vidéo -->
    <label for="media">Image ou Vidéo:</label>
    <input type="file" name="media" accept="image/*,video/*"><br>

    <input type="submit" value="Créer Post">
</form>

</body>
</html>
