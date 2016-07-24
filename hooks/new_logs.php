<?php

defined('IN_EZRPG') or exit;

//Header hook to check for new logs, default priority (5)
$hooks->add_hook('header', 'new_logs');

function hook_new_logs($container, $args = 0)
{
    if (LOGGED_IN == true) {
        $container['tpl']->assign('new_logs', checkLog($container['player']->id, $container['db']));
    }
    return $args;
}

?>