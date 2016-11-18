<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('player', 'level_up', 2);
global $debugTimer;

function hook_level_up($container, $args = 0)
{
    global $debugTimer;
    //No player data
    if ($args === 0 || LOGGED_IN == false) {
        return $args;
    }
    //Check if player has leveled up
    if ($args->exp >= $args->max_exp) {
        //Update the current player variable ($args)
        $args->exp = $args->exp - $args->max_exp;  //Figured it out, we're setting Exp back to 0 + however much above it we were.... Leveling

        $args->level += 1;
        $args->stat_points += 2;
        $args->max_exp += 20;
        $args->max_energy += 1;
        $args->energy += 1;
        $args->hp += 5;
        $args->max_hp += 5;
        //Update the database
        $container['db']->execute('UPDATE `<ezrpg>players_meta` SET `level`=?, `stat_points`=?, `max_exp`=?, `energy`=?, `max_energy`=?, `hp`=?, `max_hp`=?, `exp`=? WHERE `pid`=?',
                                                        array($args->level, $args->stat_points, $args->max_exp, $args->energy, $args->max_energy, $args->hp, $args->max_hp, $args->exp, intval($args->id)));
        $debugTimer['UPDATE Stats'] = microtime(1);
        //Add event log
        $msg = 'You have leveled up! You gained +2 stat points and +1 max energy!';
        addLog(intval($args->id), $msg, $container['db']);
        $debugTimer['Add Log'] = microtime(1);
    }

    return $args;
}

?>
