<?php

/*
  Name: Index.php
  URI: http://ezrpgproject.net/
  Author: Zeggy, Booher, UAKTags
  Package: ezRPG-Core
 */

namespace ezrpg;
use ezrpg\core\Application,
    ezrpg\core\ModuleFactory;


// Define IN_EZRPG as TRUE
define('IN_EZRPG', true);

if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die('You must initialize composer!');
}

// Check for config and if it has data. @since 1.2RC
if (!file_exists(__DIR__ . '/config.php') OR filesize(__DIR__.'/config.php') == 0) {
   die('Config not found!'); 
}

// Load init.php
require_once 'init.php';

try {

    $container = new \Pimple\Container;
    $ezrpg = new Application($container);
    $ezrpg->getConfig(CUR_DIR . '/config.php');
// Database
    $ezrpg->setDatabase();

    // Database password no longer needed, unset variable
    unset($config_password);

    // Settings
    $ezrpg->getSettings();

    $container['hooks'] = $hooks = $ezrpg->getHooks();

    parse_str($argv[1], $params);

    if (isset($params['act'])) {
        if ($params['act'] == "hour") {
            $cron = new \ezrpg\core\HourlyCron($container);
            $cron->start();
            echo date("M-d-y @ H:i:s") . "Executed Hourly \n";
        } elseif ($params['act'] == "halfhour") {
            $cron = new \ezrpg\core\HalfHourCron($container);
            $cron->start();
            echo date("M-d-y @ H:i:s") . "Executed HalfHour \n";
        } elseif ($params['act'] == "daily") {
            $cron = new \ezrpg\core\DailyCron($container);
            $cron->start();
            echo date("M-d-y @ H:i:s") . "Executed Daily";
        } else {
            die('Unknown argument');
        }
    } else {
        die("There weren't any arguments!");
    }
} catch (\Exception $ex) {
    echo "Error: ".$ex->getMessage();
}
