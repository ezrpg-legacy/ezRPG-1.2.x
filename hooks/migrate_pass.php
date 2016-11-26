<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('login_funcs', 'migrate_pass', 0);

function hook_migrate_pass($container, $args = 0)
{
    if(!isset($args['player']->secret_key))
        return $args;

    if(sha1($args['player']->secret_key . $args['post'] . $container['config']['secret'])) {
        $new_pass = password_hash($args['post'], PASSWORD_BCRYPT);
        $container['db']->execute("UPDATE <ezrpg>players SET password='". $new_pass . "' WHERE id=". $args['player']->id);
        return true;
    }
    return $args;
}

?>