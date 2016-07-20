<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('test', 'test_hook');
$hooks->add_hook('exceptionTest', 'exec_test');

function hook_test_hook($container, $args = 0)
{
    if (is_string($args)) {
        $args .= ". <br /> Hook: Echoing from Test_Hook hook";

        return $args;
    } else {
        return "Echoing from a Test_Hook";
    }
}

function hook_exec_test($container, $args = 0)
{
    if ($args == 'db') {
        throw new \ezRPG\lib\DbException("This is an db exception");
    }elseif($args == 'file'){
        throw new \ezRPG\lib\FileException('This is a file exception');
    }else{
        throw new \ezRPG\lib\EzException("This is a normal exception");
    }
}

?>