<?php

defined('IN_EZRPG') or exit;

//Add hook to update the last active value for the player, default priority (5)
$app['hooks']->add_hook('header', 'last_active');

function hook_last_active($app, $args = 0)
{
	$db = $app['db']; $tpl=$app['tpl']; $player=$args['player'];
	if ( $player === 0 || LOGGED_IN == false )
        return $args;
	
    //Only update last active value if 5 minutes have passed since the last update
    if ( $player->last_active <= (time() - 300) )
    {
        $query = $db->execute('UPDATE `<ezrpg>players_meta` SET `last_active`=? WHERE `pid`=?', array( time(), $player->id ));
        loadMetaCache(1);
    }

    return $args;
}

?>