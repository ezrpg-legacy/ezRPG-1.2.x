<?php

defined('IN_EZRPG') or exit;

$app['hooks']->add_hook('header', 'api_controls', 1);

function hook_api_controls($app, $args = 0)
{
		
    

    return $args;
}

?>