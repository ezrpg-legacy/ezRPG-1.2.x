<?php

namespace ezRPG\Install\Modules;
use ezRPG\Install\InstallerFactory;

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
        require_once ROOT_DIR . '/config.php';
        try
        {
            $this->container['app']->getConfig(ROOT_DIR . '/config.php');
            $db = \ezRPG\lib\DbFactory::factory($this->container['config']);
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
  `installed` tinyint(1) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;

        $db->execute($structure1);

        $data1 = <<<QUERY
INSERT INTO `<ezrpg>menu` (`id`, `parent_id`, `name`, `title`, `AltTitle`, `uri`, `pos`, `active`, `module_id`) VALUES
(1, 0, 'UserMenu', 'User Menu',NULL, '', 0, 1, 0),
(2, 1, 'EventLog', 'Event Log',NULL, 'index.php?mod=EventLog', 0, 1, 2),
(3, 1, 'City', 'City',NULL, 'index.php?mod=City', 1, 1, 3),
(4, 1, 'Members', 'Members',NULL, 'index.php?mod=Members', 2, 1, 5),
(5, 1, 'Account', 'Account',NULL, 'index.php?mod=AccountSettings', 3, 1, 4),
(6, 0, 'WorldMenu', 'World Menu',NULL, '', 0, 1, 0),
(7, 6, 'Members', 'Members',NULL, 'index.php?mod=Members', 0, 1, 5),
(8, 0, 'AdminMenu', 'Admin Menu',NULL, '', 0, 1, 0),
(9, 8, 'Members', 'Members','Member Management', 'index.php?mod=Members', 0, 1, 5),
(10, 8, 'Menus', 'Menus', 'Menu Management', 'index.php?mod=Menu', 1, 1, 10),
(11, 8, 'Themes', 'Themes', 'Themes Management', 'index.php?mod=Themes', 2, 1, 12),
(12, 8, 'Settings', 'Settings', 'Settings Management', 'index.php?mod=Settings', 3, 1, 13),
(13, 8, 'Plugins', 'Plugins', 'Plugin Management', 'index.php?mod=Plugins', 4, 1, 11),
(14, 8, 'Update', 'Update', 'ezRPG Updater', 'index.php?mod=Update', 5, 1, 14),
(15, 1, 'Test', 'Test', 'Test Module', 'index.php?mod=Test', 99, 0, 14);
QUERY;

        $db->execute($data1);


        $data2 = <<<QUERY
INSERT INTO `<ezrpg>plugins` (`id`, `title`, `active`, `installed`) VALUES
(1, 'Login', 1, 1),
(2, 'EventLog', 1, 1),
(3, 'City', 1, 1),
(4, 'AccountSettings', 1, 1),
(5, 'Members', 1, 1),
(6, 'Logout', 1, 1),
(7, 'Register', 1, 1),
(8, 'StatPoints', 1, 1),
(9, 'Index', 1, 1),
(10, 'Menu', 1, 1),
(11, 'Plugins', 1, 1),
(12, 'Themes', 1, 1),
(13, 'Settings', 1, 1),
(14, 'Update', 1, 1),
(15, 'Test', 0, 1);
QUERY;

        $db->execute($data2);
		
		killSettingsCache();
		killModuleCache();
        $this->header();
        echo "<h2>ezRPGCore Plugins have been installed!</h2>\n";
        echo "<a href=\"index.php?step=CreateAdmin\">Continue to next step</a>";
        $this->footer();
    }

}

?>
