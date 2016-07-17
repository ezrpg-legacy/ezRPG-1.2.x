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

// Traverse back one directory
//chdir($rootPath);

if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die('You must initialize composer!');
}

// Start the Debug Timer. @since 1.2RC
$debugTimer['ezRPG start'] = microtime(1);

// Check for config and if it has data. @since 1.2RC
if (!file_exists('config.php') OR filesize('config.php') == 0) {
    //Redirect to installer @since 1.x
    header('Location: install/index.php');
    exit(1);
}
session_start();
// Load init.php
require_once 'init.php';

try {

    $container = new \Pimple\Container;
    $ezrpg = new Application($container);
    $ezrpg->getConfig(CUR_DIR . '/config.php');
// Database
    $ezrpg->setDatabase();
    $debugTimer['DB Loaded:'] = microtime(1);

    // Database password no longer needed, unset variable
    unset($config_password);

    // Settings
    $ezrpg->getSettings();
    $debugTimer['Settings Loaded:'] = microtime(1);

    // Smarty
    $tpl = $ezrpg->container['tpl'] = new \Smarty();
    $tpl->addPluginsDir(LIB_DIR . '/ext/smarty');
    $tpl->caching = 0;
    $tpl->assign('GAMESETTINGS', $ezrpg->container['settings']->setting['general']);
    if (DEBUG_MODE) {
        echo 'GAMESETTINGS Smarty Variable is being deprecated. Use {settings g=\'general\' n=\'Setting_Name\'} for your GameSettings needs.';
    }

    $tpl->addTemplateDir(array(
        'admin' => THEME_DIR . 'themes/admin/',
        'default' => THEME_DIR . 'themes/default/'
    ));
    $debugTimer['Smarty Loaded:'] = microtime(1);
    $tpl->compile_dir = $tpl->cache_dir = CACHE_DIR . 'templates/';

// Themes
    $ezrpg->container['themes'] = $ezrpg->getThemes();

// Initialize $player
    $ezrpg->container['player'] = 0;

    $container['hooks'] = $hooks = $ezrpg->getHooks();

    $debugTimer['Hooks Loaded :'] = microtime(1);
// Run login hooks on player variable
    $ezrpg->setPlayer($hooks->run_hooks('player', 0));

    $debugTimer['Player-Hooks loaded:'] = microtime(1);
// Create the Menu object
    $menu = new \ezRPG\lib\Menu($container);
    $debugTimer['Menus Initiated:'] = microtime(1);
    $menu->get_menus();
    $debugTimer['Menus retrieved:'] = microtime(1);
    $players = new \ezRPG\lib\Players($container);
} catch (\Exception $ex) {
    $ex->getMessage();
}

// Start the Debug Timer. @since 1.2RC
$debugTimer['init.php Loaded:'] = microtime(1);

//Set Default module and check if Module is selected in URI
$default_mod = $container['settings']->setting['general']['default_module']['value'];

$module_name = ((isset($_GET['mod']) && ctype_alnum($_GET['mod'])/* && isModuleActive($_GET['mod'])*/) ? $_GET['mod'] : $default_mod);
$container['tpl']->assign('module_name', $module_name);
//Init Hooks - Runs before Header
$container['hooks']->run_hooks('init');
$debugTimer['Init-hooks Loaded:'] = microtime(1);

//Header hooks
$module_name = $container['hooks']->run_hooks('header', $module_name);
$debugTimer['header-hooks Loaded:'] = microtime(1);

//Begin module
$module = ModuleFactory::factory($container, $module_name, $menu);
if (isset($_GET['act'])) {
    if (method_exists($module, $_GET['act'])) {
        $reflection = new ReflectionMethod($module, $_GET['act']);
        if ($reflection->isPublic()) {
            $module->$_GET['act']();
        } else {
            $module->start();
        }
    } else {
        $module->start();
    }
} else {
    $module->start();
}
$debugTimer[$module_name . 'Loaded'] = microtime(1);

//Footer hooks
$container['hooks']->run_hooks('footer', $module_name);
$debugTimer['footer-hooks'] = microtime(1);

// DEBUG_INFO with Timer @since 1.2RC
if (DEBUG_MODE) {
    if (is_object($container['player']) && $container['player']->rank > 5) {
        echo "<pre><table border=1><tr><td>name</td><td>Total Time</td><td>Step Time</td><td>%</td></tr>";
        reset($debugTimer);
        $start = $prev = current($debugTimer);
        $total = end($debugTimer) - $start;
        foreach ($debugTimer as $name => $value) {
            $sofar = round($value - $start, 4);
            $delta = round($value - $prev, 4);
            $percent = round($delta / $total * 100);
            echo "<tr><td>$name</td><td>$sofar</td><td>$delta</td><td>$percent</td></tr>";
            $prev = $value;
        }
        echo "</table>";
        $mem = memory_get_usage();
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        echo "Memory Used: " . round($mem / pow(1024, ($i = floor(log($mem, 1024)))), 2) . ' ' . $unit[$i] . "</pre>";
    }
}
?>
