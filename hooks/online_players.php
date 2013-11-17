<?php

defined('IN_EZRPG') or exit;

$app['hooks']->add_hook('header', 'online_players');
$app['hooks']->add_hook('admin_header', 'online_players', 1);

function hook_online_players($app, $args = 0)
{
	$db = $app['db']; $tpl=$app['tpl']; $player=$args['player'];
    $query = $db->fetchRow('SELECT COUNT(`pid`) AS `count` FROM `<ezrpg>players_meta` WHERE `last_active`>?', array( time() - (60 * 5) ));
    $tpl->assign('ONLINE', $query->count);

    return $args;
}

?>