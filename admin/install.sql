DROP TABLE IF EXISTS `#__spip2joomla_rubriques`;
DROP TABLE IF EXISTS `#__spip2joomla_auteurs`;
 
CREATE TABLE IF NOT EXISTS`#__spip2joomla_rubriques` (
  `id` int(11) NOT NULL auto_increment,
  `rubrique_id` int(11) NOT NULL,
  `joomla_container` ENUM('section','category'),
  `joomla_id` int(11) NOT NULL,
  `spip_url` varchar(25),
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS`#__spip2joomla_auteurs` (
  `id` int(11) NOT NULL auto_increment,
  `auteur_id` int(11) NOT NULL,
  `joomla_id` int(11) NOT NULL,
  `spip_url` varchar(25),
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
