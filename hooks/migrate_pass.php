<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('login_funcs', 'migrate_pass', 0);

function hook_migrate_pass($container, $args = 0)
{
    $secret_col = $container['db']->execute("SHOW COLUMNS FROM <ezrpg>players LIKE 'secret_key'");
    if(!$container['db']->numRows($secret_col))
        return $args;

    $sql = $container['db']->execute('SELECT `secret_key` FROM <ezrpg>players WHERE id='. $args['player']->id . ' AND secret_key <> ""');
    $res = $container['db']->fetch($sql);
    $secret_key = $res->secret_key;

    if(secret_key == '')
        return $args;

    if(sha1($secret_key . $args['post'] . $container['config']['secret'])) {
        $new_pass = password_hash($args['post'], PASSWORD_BCRYPT);
        $container['db']->execute("UPDATE <ezrpg>players SET password='". $new_pass . "', secret_key='' WHERE id=". $args['player']->id);
        return true;
    }
    return $args;
}

?>