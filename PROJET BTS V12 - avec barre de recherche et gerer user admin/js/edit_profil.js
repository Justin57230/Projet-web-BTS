// Fonction pour envoyer les données de la nouvelle photo au serveur
function envoyerNouvellePhoto(ancienneImage, idUtilisateur, nouveauChemin) {
    // Création d'un objet FormData pour envoyer les données au serveur
    const formData = new FormData();
    formData.append('nouvellePhoto', ancienneImage);
    formData.append('idUtilisateur', idUtilisateur);
    formData.append('nouveauChemin', nouveauChemin);

    // Envoi des données au serveur via une requête AJAX
    fetch('/upload_photo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Afficher un message de succès ou gérer la réponse du serveur
        console.log(data.message);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

document.getElementById('fileInput').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Mettre à jour la source de la photo de profil
            document.getElementById('profilePic').setAttribute('src', e.target.result);
            
            // Stocker l'objet de fichier de la photo de profil
            const ancienneImage = file;
            
            // Récupérer l'identifiant de l'utilisateur
            const idUtilisateur = document.getElementById('idUtilisateur').value;

            // Récupérer le chemin de la nouvelle photo (vous devez remplacer 'nouveauChemin' par le chemin réel de la nouvelle photo)
            const nouveauChemin = 'chemin/vers/nouvelle/photo';

            // Envoyer les données de la nouvelle photo au serveur
            envoyerNouvellePhoto(ancienneImage, idUtilisateur, nouveauChemin);
        };
        reader.readAsDataURL(file);
    }
});
