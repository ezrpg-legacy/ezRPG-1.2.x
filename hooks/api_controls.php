<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('header', 'api_controls', 1);

function hook_api_controls(&$db, &$tpl, &$player, $args = 0)
{
		
    

    return $args;
}

?>