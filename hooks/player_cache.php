<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('header', 'player_cache');
$hooks->add_hook('admin_header', 'player_cache', 1);

function hook_player_cache($container, $args = 0)
{
    $sql = $container['db']->execute('SELECT * FROM `<ezrpg>players` WHERE `force_cache` = 1');
    $query = $container['db']->fetchAll($sql);
    if ($query) {
        foreach ($query as $item) {
            killPlayerCache($item->id);
            $container['db']->execute('UPDATE <ezrpg>players SET force_cache = 0 WHERE id=?', array($item->id));
        }
    }

    return $args;
}

?>