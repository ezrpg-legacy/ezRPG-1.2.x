<?php

defined('IN_EZRPG') or exit;

$app['hooks']->add_hook('player', 'check_stats', 2);

function hook_check_stats($app, $args = 0)
{
    if ( $args === 0 || LOGGED_IN == false )
        return $args;
		
    $changed = false;
    //Check if player's stats are above the limit
    if ( $args->hp > $args->max_hp )
    {
        $args->hp = $args->max_hp;
        $changed = true;
    }

    if ( $args->energy > $args->max_energy )
    {
        $args->energy = $args->max_energy;
        $changed = true;
    }

    if ( $changed === true )
    {
        $app['db']->execute('UPDATE `<ezrpg>players_meta` SET `energy`=?, `hp`=? WHERE `pid`=?', array( $args->energy, $args->hp, $args->id ));
    }
	
	$app['debugTimer']['check_stat hook Loaded:'] = microtime(1);
    return $args;
}

?>
