<?php

namespace ezrpg\Install\Modules;
use ezrpg\Install\InstallerFactory;

class Install_Populate extends InstallerFactory
{
    public function __construct($container)
    {
        parent::__construct($container);
    }

    function start()
    {
        if ( !file_exists(ROOT_DIR . '/config/database.php') OR filesize(ROOT_DIR . '/config/database.php') == 0 )
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
		try
        {
            $this->container['app']->getConfig(ROOT_DIR . '/config/database.php'); //this doesn't work yet. @todo add a variable to getConfig to set the configpath if applicable
            $db = \ezrpg\core\DbFactory::factory($this->container['config']);
        }
        catch ( DbException $e )
        {
            $e->__toString();
        }
		
        $structure1 = <<<QUERY
CREATE TABLE IF NOT EXISTS `<ezrpg>players` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) default NULL,
  `pass_method` tinyint(3) NOT NULL,
  `secret_key` text NOT NULL,
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
  `active` int(11) NOT NULL DEFAULT '1',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `module_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `AltTitle` varchar(255) DEFAULT NULL DEFAULT '',
  `uri` varchar(255) NOT NULL,
  `pos` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
QUERY;
        $db->execute($structure4);


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

		$this->header();
		echo "<h2>The database has been populated.</h2>\n";
		echo "<a href=\"index.php?step=Plugins\">Continue to next step</a>";
		$this->footer();
		
    }

}

?>
