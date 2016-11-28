<?php

/*
  Name: Lib.php
  URI: http://ezrpgproject.net/
  Author: UAKTags
  Package: ezRPG-Core
 */

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

//Functions
$func = array(
    'log',
    'rand',
    'text',
    'player',
    'validate',
    'security',
    'modules'
);

foreach ($func as $item) {
    $filename = CORE_DIR . '/functions/' . $item . '.php';
    if (is_readable($filename)) {
        require_once($filename);
    }
}


//Constants
require_once(CORE_DIR . '/const.errors.php');

?>
