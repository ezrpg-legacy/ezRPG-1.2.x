<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('header', 'online_players');
$hooks->add_hook('admin_header', 'online_players', 1);

function hook_online_players(&$db, &$tpl, &$player, $args = 0)
{
    $query = $db->fetchRow('SELECT COUNT(`pid`) AS `count` FROM `<ezrpg>players_meta` WHERE `last_active`>?', array( time() - (60 * 5) ));
    $tpl->assign('ONLINE', $query->count);

    return $args;
}

?>