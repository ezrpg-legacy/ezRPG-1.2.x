<?php

defined('IN_EZRPG') or exit;

$app['hooks']->add_hook('header', 'player_cache');
$app['hooks']->add_hook('admin_header', 'player_cache', 1);

function hook_player_cache($app, $args = 0)
{
	$dbase = $app['db']; $tpl=$app['tpl']; $player=$args['player'];
    $sql = $dbase->execute('SELECT * FROM `<ezrpg>players` WHERE `force_cache` = 1');
    $query = $dbase->fetchAll($sql);
    if ( $query )
    {
        foreach ( $query as $item )
        {
            killPlayerCache($item->id);
            $dbase->execute('UPDATE <ezrpg>players SET force_cache = 0 WHERE id=?', array( $item->id ));
        }
    }
	$app['debugTimer']['player_cache hook Loaded:'] = microtime(1);
    return $args;
}

?>