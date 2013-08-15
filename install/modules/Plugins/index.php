<?php

class Install_Plugins extends InstallerFactory
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
CREATE TABLE IF NOT EXISTS `<ezrpg>plugins` (
   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `filename` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `installed` int(2) NOT NULL,
  `pid` bigint(20) NOT NULL DEFAULT '0',
  `second_installed` int(11) unsigned default '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;

        $db->execute($structure1);


        $structure2 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>plugins_meta` (
   `meta_id` int(11) NOT NULL AUTO_INCREMENT,
  `plug_id` int(11) NOT NULL,
  `version` float NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `url` text NOT NULL,
  `uninstall` varchar(255) NOT NULL,
   PRIMARY KEY  (`meta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;

        $db->execute($structure2);

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
INSERT INTO `<ezrpg>plugins` (`id`, `title`, `active`, `filename`, `type`, `installed`, `pid`) VALUES
(1, 'ezRPGCore', 1, '', 'module', 1, 0),
(2, 'Login', 1, '', 'module', 1, 1),
(3, 'EventLog', 1, '', 'module', 1, 1),
(4, 'City', 1, '', 'module', 1, 1),
(5, 'AccountSettings', 1, '', 'module', 1, 1),
(6, 'Members', 1, '', 'module', 1, 1),
(7, 'Logout', 1, '', 'module', 1, 1),
(8, 'Register', 1, '', 'module', 1, 1),
(9, 'StatPoints', 1, '', 'module', 1, 1);
QUERY;

        $db->execute($data2);

        $data3 = <<<QUERY
INSERT INTO `<ezrpg>plugins_meta` (`meta_id`, `plug_id`, `version`, `author`, `description`, `url`, `uninstall`) VALUES
(1, 2, 1.2, 'ezRPGTeam', 'Login Module - ezRPGCore', '', ''),
(2, 3, 1.2, 'ezRPGTeam', 'EventLog Module - ezRPGCore', '', ''),
(3, 4, 1.2, 'ezRPGTeam', 'City Module - ezRPGCore', '', ''),
(4, 5, 1.2, 'ezRPGTeam', 'AccountSettings - ezRPGCore', '', ''),
(5, 6, 1.2, 'ezRPGTeam', 'Members - ezRPGCore', '', ''),
(6, 7, 1.2, 'ezRPGTeam', 'Logout Module - ezRPGCore', '', ''),
(7, 1, 1.2, 'ezRPGTeam', 'ezRPG Core. Disabling this will disable ALL core modules. Deleting with completely REMOVE ALL core modules', '', ''),
(8, 8, 1.2, 'ezRPGTeam', 'Register Module - ezRPGCore', '', ''),
(9, 8, 1.2, 'ezRPGTeam', 'StatPoints Module - ezRPGCore', '', '');
QUERY;

        $db->execute($data3);
		
		killSettingsCache();
		killModuleCache();
        $this->header();
        echo "<h2>ezRPGCore Plugins have been installed!</h2>\n";
        echo "<a href=\"index.php?step=CreateAdmin\">Continue to next step</a>";
        $this->footer();
    }

}

?>
