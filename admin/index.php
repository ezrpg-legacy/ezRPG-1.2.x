<?php

namespace ezRPG\admin;

define('IN_EZRPG', true);
define('IN_ADMIN', true);
session_start();
require_once '../init.php';

$container = new \Pimple\Container;
$ezrpg = new \ezRPG\lib\Application($container);

$ezrpg->getConfig(CUR_DIR . '/config.php');
// Database
$ezrpg->setDatabase();

$debugTimer['DB Loaded:'] = microtime(1);
// Settings
$ezrpg->getSettings();
$debugTimer['Settings Loaded:'] = microtime(1);
// Smarty
$tpl = $ezrpg->container['tpl'] = new \Smarty();
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

/*
// Create a hooks object
$hooks = new Hooks($db, $tpl, $player);
$debugTimer['Hooks Initiated:'] = microtime(1);
// Include all hook files
$hook_files = scandir(HOOKS_DIR);
foreach ( $hook_files as $hook_file )
{
    $path_parts = pathinfo(HOOKS_DIR . '/' . $hook_file);
    if ( $path_parts['extension'] == 'php' && $path_parts['basename'] != 'index.php' )
    {
        include_once (HOOKS_DIR . '/' . $hook_file);
    }
}
*/

$container['hooks'] = $hooks = $ezrpg->getHooks();

$debugTimer['Hooks Loaded :'] = microtime(1);
// Run login hooks on player variable
$ezrpg->setPlayer($hooks->run_hooks('player', 0));

$debugTimer['Player-Hooks loaded:'] = microtime(1);
// Create the Menu object
$ezrpg->container['menu'] = new \ezRPG\lib\Menu($container);
$debugTimer['Menus Initiated:'] = microtime(1);
$ezrpg->container['menu']->get_menus();
$debugTimer['Menus retrieved:'] = microtime(1);
$players = new \ezRPG\lib\Players($container);

// Check player exists
if ($container['player'] == '0') {
    header('Location: ../index.php');
    exit;
}

//Require admin rank
if ($container['player']->rank < 5) {
    header('Location: ../index.php');
    exit;
}

$default_mod = 'Index';

$module_name = ((isset($_GET['mod']) && ctype_alnum($_GET['mod'])) ? $_GET['mod'] : $default_mod);

//Admin header hook
$module_name = $hooks->run_hooks('admin_header', $module_name);
//Begin module
$module = \ezRPG\lib\ModuleFactory::adminFactory($container, $module_name);
$module->start();

//Admin footer hook
$hooks->run_hooks('admin_footer', $module_name);
?>
