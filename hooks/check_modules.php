<?php

defined('IN_EZRPG') or exit;

//Add a module object hook - check the module for install/enabled, priority 0
$app['hooks']->add_hook('init', 'check_module', 0);
$app['hooks']->add_hook('get_plugins', 'check_module_fresh', 0);

//Player hook to check the module for install/enabled
function hook_check_module($app, $args = 0)
{
	$dbase = $app['db']; $tpl=$app['tpl'];
    //Select Module Cache
    $modules = loadModuleCache();
	$app['debugTimer']['check_module hook Loaded:'] = microtime(1);
    return $modules;
}

//Player hook to check the module for install/enabled
function hook_check_module_fresh($app, $args = 0)
{
	$dbase = $app['db']; $tpl=$app['tpl'];
    //Select Module Cache
	killModuleCache();
    $modules = loadModuleCache();
	$app['debugTimer']['check_module hook Loaded:'] = microtime(1);
    return $modules;
}

?>
