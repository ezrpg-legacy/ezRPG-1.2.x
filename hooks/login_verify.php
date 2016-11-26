<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('login_funcs', 'login_verify', 5);

function hook_login_verify($container, $args = 0)
{
    if(is_bool($args))
        return $args;

    return password_verify($args['post'], $args['player']->password);
}

?>