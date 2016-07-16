<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('cron_1hr', 'cron_heal');

function hook_cron_heal($container, $args = 0)
{
    $query = "UPDATE <ezrpg>players_meta SET hp = hp + 1 WHERE hp < max_hp";
    $container['db']->execute($query);

    return $args;
}

?>