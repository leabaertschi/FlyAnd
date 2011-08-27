--
-- Structure for table `film`
--

CREATE TABLE `film` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clip_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('vimeo','youtube','facebook') CHARACTER SET utf8 NOT NULL,
  `direct_url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Structure for table `vote`
--

CREATE TABLE `vote` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `film_id` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `film_id` (`film_id`),
  CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `film` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
