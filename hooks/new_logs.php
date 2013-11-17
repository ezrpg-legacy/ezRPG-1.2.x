<?php

defined('IN_EZRPG') or exit;

//Header hook to check for new logs, default priority (5)
$app['hooks']->add_hook('header', 'new_logs');

function hook_new_logs($app, $args = 0)
{
	$db = $app['db']; $tpl=$app['tpl']; $player=$args['player'];
	if ( LOGGED_IN == true )
        $app['tpl']->assign('new_logs', checkLog($player->id, $app['db']));

    return $args;
}

?>