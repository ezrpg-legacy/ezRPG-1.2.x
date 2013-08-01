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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
QUERY;
        $db->execute($structure4);

        $structure5 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>plugins` (
   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `filename` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `installed` int(2) NOT NULL,
  `pid` bigint(20) NOT NULL DEFAULT '0',
  `second_installed` int(11) unsigned default '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;

        $db->execute($structure5);

        $structure6 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>settings` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `title` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `optionscode` text,
  `value` text,
  `disporder` smallint(5) NOT NULL DEFAULT '0',
  `gid` smallint(5) NOT NULL DEFAULT '0',
  `isdefault` int(1) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;

        $db->execute($structure6);

        $structure7 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `dir` text NOT NULL,
  `enabled` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;
        $db->execute($structure7);

        $structure8 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>plugins_meta` (
   `meta_id` int(11) NOT NULL,
  `plug_id` int(11) NOT NULL,
  `version` float NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `url` text NOT NULL,
  `uninstall` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;

        $db->execute($structure8);

        $data1 = <<<QUERY
INSERT INTO `<ezrpg>menu` (`id`, `parent_id`, `name`, `title`, `AltTitle`, `uri`, `pos`, `active`, `module_id`) VALUES
(1, 0, 'UserMenu', 'User Menu',NULL, '', 0, 1, 0),
(2, 1, 'EventLog', 'Event Log',NULL, 'index.php?mod=EventLog', 0, 1, 3),
(3, 1, 'City', 'City',NULL, 'index.php?mod=City', 1, 1, 4),
(4, 1, 'Members', 'Members',NULL, 'index.php?mod=Members', 2, 1, 6),
(5, 1, 'Account', 'Account',NULL, 'index.php?mod=AccountSettings', 3, 1, 5),
(6, 0, 'WorldMenu', 'World Menu',NULL, '', 0, 1, 0),
(7, 6, 'Members', 'Members',NULL, 'index.php?mod=Members', 0, 1, 6),
(8, 0, 'AdminMenu', 'Admin Menu',NULL, '', 0, 1, 0),
(9, 8, 'Members', 'Members','Member Management', 'index.php?mod=Members', 0, 1, 0),
(10, 8, 'Menus', 'Menus', 'Menu Management', 'index.php?mod=Menu', 0, 1, 0),
(11, 8, 'Themes', 'Themes', 'Themes Management', 'index.php?mod=Themes', 0, 1, 0),
(12, 8, 'Settings', 'Settings', 'Settings Management', 'index.php?mod=Settings', 0, 1, 0),
(13, 8, 'Plugins', 'Plugins', 'Plugin Management', 'index.php?mod=Plugins', 0, 1, 0);
QUERY;

        $db->execute($data1);

        $data2 = <<<QUERY
INSERT INTO `<ezrpg>settings` (`id`, `name`, `title`, `description`, `optionscode`, `value`, `disporder`, `gid`, `isdefault`) VALUES
(1, 'general', 'General Configuration', 'This section contains varius engine related settings',NULL, NULL, 0, 0, 1),
(2, 'game_name', 'Game Title', 'The title for your game', 'text', 'ezRPG 1.2.0', 0, 1, 1),
(3, 'pass_encryption', 'Password Encryption', 'Determine the type of password encryption to use for User Logins.','select', 4, 0, 1, 1),
(4, 'legacy', 'ezRPG Legacy', 'ezRPG Legacy Encryption method','option', 1, 0, 3, 1),
(5, 'pbkdf2', 'PBKDF2 Method', 'PBKDF2','option', 2, 0, 3, 1),
(6, 'bcrypt', 'BCrypt Method', 'BCRYPT','option', 3, 0, 3, 1);
QUERY;

        $db->execute($data2);

        $data3 = <<<QUERY
INSERT INTO `<ezrpg>plugins` (`id`, `title`, `active`, `filename`, `type`, `installed`, `pid`) VALUES
(1, 'ezRPGCore', 1, '', 'module', 1, 0),
(2, 'Login', 1, '', 'module', 1, 1),
(3, 'EventLog', 1, '', 'module', 1, 1),
(4, 'City', 1, '', 'module', 1, 1),
(5, 'AccountSettings', 1, '', 'module', 1, 1),
(6, 'Members', 1, '', 'module', 1, 1),
(7, 'Logout', 1, '', 'module', 1, 1);
QUERY;

        $db->execute($data3);

        $data4 = <<<QUERY
INSERT INTO `<ezrpg>plugins_meta` (`meta_id`, `plug_id`, `version`, `author`, `description`, `url`, `uninstall`) VALUES
(1, 2, 1.2, 'ezRPGTeam', 'Login Module - ezRPGCore', '', ''),
(2, 3, 1.2, 'ezRPGTeam', 'EventLog Module - ezRPGCore', '', ''),
(3, 4, 1.2, 'ezRPGTeam', 'City Module - ezRPGCore', '', ''),
(4, 5, 1.2, 'ezRPGTeam', 'AccountSettings - ezRPGCore', '', ''),
(5, 6, 1.2, 'ezRPGTeam', 'Members - ezRPGCore', '', ''),
(6, 7, 1.2, 'ezRPGTeam', 'Logout Module - ezRPGTeam', '', ''),
(7, 1, 1.2, 'ezRPGTeam', 'ezRPG Core. Disabling this will disable ALL core modules. Deleting with completely REMOVE ALL core modules', '', '');
QUERY;

        $db->execute($data4);
        $this->header();
        echo "<h2>The database has been populated.</h2>\n";
        echo "<a href=\"index.php?step=CreateAdmin\">Continue to next step</a>";
        $this->footer();
    }

}

?>
