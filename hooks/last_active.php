<?php

defined('IN_EZRPG') or exit;

//Add hook to update the last active value for the player, default priority (5)
$hooks->add_hook('header', 'last_active');

function hook_last_active($container, $args = 0)
{
    if ($container['player'] === 0 || LOGGED_IN == false) {
        return $args;
    }

    //Only update last active value if 5 minutes have passed since the last update
    if ($container['player']->last_active <= (time() - 300)) {
        $query = $container['db']->execute('UPDATE `<ezrpg>players_meta` SET `last_active`=? WHERE `pid`=?',
            array(time(), $container['player']->id));
        loadMetaCache(1);
    }

    return $args;
}

?>