--
-- Base de données :  `communauteam`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE IF NOT EXISTS `commentaires` (
`id_commentaires` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `contenu` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
`id_likes` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
`id_posts` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `contenu` text,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `media_path` varchar(255) DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
`id_users` int(11) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `photo_profil` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `abonnement` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
 ADD PRIMARY KEY (`id_commentaires`), ADD KEY `utilisateur_id` (`utilisateur_id`), ADD KEY `post_id` (`post_id`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
 ADD PRIMARY KEY (`id_likes`), ADD KEY `utilisateur_id` (`utilisateur_id`), ADD KEY `post_id` (`post_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
 ADD PRIMARY KEY (`id_posts`), ADD KEY `utilisateur_id` (`utilisateur_id`), ADD KEY `parent_id` (`parent_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
 ADD PRIMARY KEY (`id_users`), ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
MODIFY `id_commentaires` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
MODIFY `id_likes` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
MODIFY `id_posts` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
