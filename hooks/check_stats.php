<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('player', 'check_stats', 2);

function hook_check_stats($db, &$tpl, $player, $args = 0)
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
        $db->execute('UPDATE `<ezrpg>players_meta` SET `energy`=?, `hp`=? WHERE `pid`=?', array( $args->energy, $args->hp, $args->id ));
    }

    return $args;
}

?>
