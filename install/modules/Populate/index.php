<?php

class Install_Populate extends InstallerFactory
{

    function start()
    {
        if ( !file_exists('../config.php') OR filesize('../config.php') == 0 )
        {
            $this->header();
            echo "<h2>There's been an error!</h2><br />";
            echo "<p>Config.php still was blank.<br />";
            echo "<p>Please use the back button and follow the instructions again.<br />";
            echo "<p>Installation can not move on until that's complete!</p>";
            echo "<script>
				document.write('<a href=' + document.referrer + '>Go Back</a>');
			</script>";
            $this->footer();
            die;
        }
        require_once "../config.php";
		try
        {
            $db = DbFactory::factory($config_driver, $config_server, $config_username, $config_password, $config_dbname, $config_port);
        }
        catch ( DbException $e )
        {
            $e->__toString();
        }
		
        $structure1 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>players` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(30) default NULL,
  `password` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `pass_method` tinyint(3) default NULL,
  `secret_key` text,
  `rank` smallint(5) unsigned NOT NULL default '1',
  `registered` int(11) unsigned default NULL,
  `force_cache` int(11) unsigned default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;
        $db->execute($structure1);


        $structure2 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>players_meta` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `pid` int(11) unsigned NOT NULL,
  `last_active` int(11) unsigned default '0',
  `last_login` int(11) unsigned default '0',
  `money` int(11) unsigned default '100',
  `level` int(11) unsigned default '1',
  `stat_points` int(11) unsigned default '10',
  `exp` int(11) unsigned default '0',
  `max_exp` int(11) unsigned default '10',
  `hp` int(11) unsigned default '20',
  `max_hp` int(11) unsigned default '20',
  `energy` int(11) unsigned NOT NULL default '10',
  `max_energy` int(11) unsigned NOT NULL default '10',
  `strength` int(11) unsigned default '5',
  `vitality` int(11) unsigned default '5',
  `agility` int(11) unsigned default '5',
  `dexterity` int(11) unsigned default '5',
  `damage` int(11) unsigned default '0',
  `kills` int(11) unsigned NOT NULL default '0',
  `deaths` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;
        $db->execute($structure2);

        $structure3 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>player_log` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `player` int(11) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `player_log` (`player`,`time`),
  KEY `new_logs` (`player`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
QUERY;
        $db->execute($structure3);

        $structure4 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `module_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `AltTitle` varchar(255) DEFAULT NULL,
  `uri` varchar(255) NOT NULL,
  `pos` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;
        $db->execute($structure4);

     
        $structure5 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>settings` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `title` varchar(120) NOT NULL,
  `description` text NULL DEFAULT NULL,
  `optionscode` text,
  `value` text,
  `disporder` smallint(5) NOT NULL DEFAULT '0',
  `gid` smallint(5) NOT NULL DEFAULT '0',
  `isdefault` int(1) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;

        $db->execute($structure5);

        $structure6 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `dir` text NOT NULL,
  `enabled` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;
        $db->execute($structure6);

     
        $data1 = <<<QUERY
INSERT INTO `<ezrpg>settings` (`id`, `name`, `title`, `description`, `optionscode`, `value`, `disporder`, `gid`, `isdefault`,`visible`) VALUES
(1, 'general', 'General Configuration', 'This section contains varius engine related settings',NULL, NULL, 0, 0, 1, 1),
(2, 'game_name', 'Game Title', 'The title for your game', 'text', 'ezRPG 1.2.1', 0, 1, 1, 1),
(3, 'pass_encryption', 'Password Encryption', 'Determine the type of password encryption to use for User Logins.','select', 4, 0, 1, 1, 1),
(4, 'legacy', 'ezRPG Legacy', 'ezRPG Legacy Encryption method','option', 1, 0, 3, 1, 1),
(5, 'pbkdf2', 'PBKDF2 Method', 'PBKDF2','option', 2, 0, 3, 1, 1),
(6, 'bcrypt', 'BCrypt Method', 'BCRYPT','option', 3, 0, 3, 1, 1),
(7, 'validation', 'Validation Settings', 'Set the specifics for the ezRPG Validation functions.', NULL, 1, 1, 0, 1, 1),
(8, 'passLenMin', 'Password Minimum Length', 'Set the minimum length for the password', 'text', '6', 1, 7, 1, 1),
(9, 'passLenMax', 'Password Maximum Length (Optional)', 'Maximum length that a password can be', 'text', '18', 2, 7, 0, 1),
(10, 'passLens', 'Password Lengths', 'Determine what lengths the password may be.', 'select', '11', 0, 7, 1, 1),
(11, 'passMin', 'Minimum Length', '', 'option', 'min', 0, 10, 1, 1),
(12, 'passMinMax', 'Minimum & Maximum Length', 'Check against both a Min and Max', 'option', 'minmax', 0, 10, 1, 1),
(13, 'version', 'Game Version', '', 'text', '1.2.1.1', 0, 1, 1, 0);
QUERY;

        $db->execute($data1);
		
		killSettingsCache();
		$this->header();
		echo "<h2>The database has been populated.</h2>\n";
		echo "<a href=\"index.php?step=Plugins\">Continue to next step</a>";
		$this->footer();
		
    }

}

?>
