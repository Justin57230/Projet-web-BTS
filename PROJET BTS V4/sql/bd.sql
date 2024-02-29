CREATE DATABASE communauteams
USE communauteams

-- Table des utilisateurs
CREATE TABLE utilisateurs (
    id_users INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    photo_profil VARCHAR(255), -- Chemin vers la photo de profil
    role ENUM('user', 'admin') DEFAULT 'user', -- Ajout du champ rôle
    abonnement TEXT, -- Champ pour stocker les id des utilisateurs suivis
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des posts
CREATE TABLE posts (
    id_posts INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    contenu TEXT,
    type_contenu ENUM('texte', 'image', 'video', 'son', 'mixte') NOT NULL,
    media_path VARCHAR(255), -- Chemin du fichier multimédia (image ou vidéo)
    parent_id INT, -- Pour indiquer le post parent s'il s'agit d'une réponse à un autre post
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id_users) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Table des commentaires
CREATE TABLE commentaires (
    id_commentaires INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    post_id INT,
    contenu TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id_users) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Table des likes
CREATE TABLE likes (
    id_likes INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    post_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id_users) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);
