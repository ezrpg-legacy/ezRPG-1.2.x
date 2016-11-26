<?php

if (!defined('IN_EZRPG')) {
    exit;
}

function generateSignature()
{

    $client = array_key_exists('userid', $_SESSION) ?
        $_SESSION['userid'] : 'guest';

    $config_paths = [
        'config/*.php'
    ];
    $configLoader = new \ezrpg\core\ConfigLoader();
    $config = $configLoader->loadConfigFromPaths($config_paths);

    $bits = array(
        'userid' => $client,
        'ip' => $_SERVER['REMOTE_ADDR'],
        'browser' => $_SERVER['HTTP_USER_AGENT'],
        'key' => $config['secret']
    );

    $signature = false;

    foreach ($bits as $key => $bit) {
        $signature .= $key . $bit;
    }

    return sha1($signature);
}

function compareSignature($origin)
{
    return $origin === generateSignature();
}
