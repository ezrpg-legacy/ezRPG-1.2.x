<?php
defined('IN_EZRPG') or exit;

//Header hook to check for new logs, default priority (5)
$hooks->add_hook('header', 'new_logs');

function hook_new_logs(&$db, &$tpl, &$player, $args = 0)
{
    if (LOGGED_IN == true)
        $tpl->assign('new_logs', checkLog($player->id, $db));
    
    return $args;
}
?>