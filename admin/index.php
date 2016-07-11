<?php

namespace ezRPG\admin;

define('IN_EZRPG', true);
define('IN_ADMIN', true);

require_once '../init.php';

// Check player exists
if ( $container['player'] == '0' )
{
    header('Location: ../index.php');
    exit;
}

//Require admin rank
if ( $container['player']->rank < 5 )
{
    header('Location: ../index.php');
    exit;
}

$default_mod = 'Index';

$module_name = ( (isset($_GET['mod']) && ctype_alnum($_GET['mod'])) ? $_GET['mod'] : $default_mod );

//Admin header hook
$module_name = $hooks->run_hooks('admin_header', $module_name);
//Begin module
$module = \ezRPG\lib\ModuleFactory::adminFactory($container, $module_name, $menu);
$module->start();

//Admin footer hook
$hooks->run_hooks('admin_footer', $module_name);
?>
