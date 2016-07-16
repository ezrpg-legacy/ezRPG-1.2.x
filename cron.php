<?php

/*
  Name: Index.php
  URI: http://ezrpgproject.net/
  Author: Zeggy, Booher, UAKTags
  Package: ezRPG-Core
 */

namespace ezRPG;
use ezRPG\lib\Application,
    ezRPG\lib\ModuleFactory;

// Define IN_EZRPG as TRUE
define('IN_EZRPG', true);

$rootPath = dirname(__DIR__);

if(!file_exists(__DIR__ . '/vendor/autoload.php'))
    die('You must initialize composer!');

// Check for config and if it has data. @since 1.2RC
if ( !file_exists('config.php') OR filesize('config.php') == 0 )
{
    exit(1);
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

    if(isset($params['act'])) {
        if($params['act'] == "hour") {
            $cron = new \ezRPG\lib\HourlyCron($container);
            $cron->start();
        }elseif($params['act']== "halfhour"){
            $cron = new \ezRPG\lib\HalfHourCron($container);
            $cron->start();
        }elseif($params['act'] == "daily"){
            $cron = new \ezRPG\lib\DailyCron($container);
            $cron->start();
        }else{
            die('Unknown argument');
        }
    }else{
        die("There weren't any arguments!");
    }
}catch (\Exception $ex)
{
    die($ex->getMessage());
}

?>
