<?php

/*
  Name: Init.php
  URI: http://ezrpgproject.net/
  Author: Zeggy, UAKTags
  Package: ezRPG-Core
 */

namespace ezRPG;

use Pimple\Container;

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else {
    die('You must initialize composer!');
}

// This page cannot be viewed, it must be included
defined('IN_EZRPG') or exit;
global $debugTimer;
// Start Session
//session_start();

// Constants
define('CUR_DIR', realpath(dirname(__FILE__)));
define('MOD_DIR', CUR_DIR . '/modules/');
define('ADMIN_DIR', CUR_DIR . '/admin');
define('CORE_DIR', CUR_DIR . '/core');
define('EXT_DIR', CORE_DIR . '/ext');
define('HOOKS_DIR', CUR_DIR . '/hooks');
define('THEME_DIR', CUR_DIR . '/templates/');
define('CACHE_DIR', CUR_DIR . '/cache/');

if(file_exists(CUR_DIR . '/config.php'))
    require_once(CUR_DIR . '/config.php');
$debugTimer['Config Loaded:'] = microtime(1);

require_once(CUR_DIR . '/lib.php');
$debugTimer['Library Loaded:'] = microtime(1);
