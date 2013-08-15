<?php

defined('IN_EZRPG') or exit;

//Add hook to update the last active value for the player, default priority (5)
$hooks->add_hook('header', 'last_active');

function hook_last_active($db, $tpl, $player, $args = 0)
{
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