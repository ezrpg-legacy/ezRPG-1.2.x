<?php
defined('IN_EZRPG') or exit;

$hooks->add_hook('player', 'level_up', 2);

function hook_level_up($db, &$tpl, $player, $args = 0)
{
    //No player data
    if ($args === 0 || LOGGED_IN == false)
       return $args;

    //Check if player has leveled up
    if ($args->exp >= $args->max_exp)
    {
        //Update the current player variable ($args)
        $args->exp = $args->exp - $args->max_exp;
        $args->level += 1;
        $args->stat_points += 2;
        $args->max_exp += 20;
        $args->max_energy += 1;
        $args->energy += 1;
        $args->hp += 5;
        $args->max_hp += 5;
        
        //Update the database
        $db->execute('UPDATE `<ezrpg>players_meta` SET `exp`=?, `level`=level+1, `stat_points`=stat_points+2, `max_exp`=max_exp+20, `energy`=energy+1, `max_energy`=max_energy+1, `hp`=hp+5, `max_hp`=max_hp+5 WHERE `pid`=?', array(intval($args->exp), intval($args->id)));
        
        //Add event log
        $msg = 'You have leveled up! You gained +2 stat points and +1 max energy!';
        addLog(intval($args->id), $msg, $db);
    }
    
    return $args;
}
?>
