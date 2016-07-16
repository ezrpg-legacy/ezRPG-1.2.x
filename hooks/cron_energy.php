<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('cron_1hr', 'cron_energy');

function hook_cron_energy($container, $args = 0)
{
    $query = "UPDATE <ezrpg>players_meta SET energy = energy + 1 WHERE energy < max_energy";
    $container['db']->execute($query);
    return $args;
}

?>