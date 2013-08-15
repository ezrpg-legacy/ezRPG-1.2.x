<?php

defined('IN_EZRPG') or exit;

//Add a module object hook - check the module for install/enabled, priority 0
$hooks->add_hook('init', 'check_module', 0);

//Player hook to check the module for install/enabled
function hook_check_module($db, &$tpl, $player, $args = 0)
{
    //Select Module Cache
    $modules = loadModuleCache();

    return $player;
}

?>
