<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('test', 'test_hook');

function hook_test_hook($container, $args = 0)
{
    if (is_string($args)) {
        $args .= ". <br /> Hook: Echoing from Test_Hook hook";

        return $args;
    } else {
        return "Echoing from a Test_Hook";
    }
}

?>