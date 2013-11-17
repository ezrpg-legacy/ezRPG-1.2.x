<?php

defined('IN_EZRPG') or exit;

//Add a module object hook - check the module for install/enabled, priority 0
$app['hooks']->add_hook('init', 'check_module', 0);

//Player hook to check the module for install/enabled
function hook_check_module($app, $args = 0)
{
	$db = $app['db']; $tpl=$app['tpl'];
    //Select Module Cache
    $modules = loadModuleCache();
    return $modules;
}

?>
