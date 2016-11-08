<?php

/*
  Name: Init.php
  URI: http://ezrpgproject.net/
  Author: Zeggy, UAKTags
  Package: ezRPG-Core
 */

namespace ezrpg;

use Pimple\Container;

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else {
    die('You must initialize composer!');
}

// This page cannot be viewed, it must be included
defined('IN_EZRPG') or exit;
global $debugTimer;

// Constants
define('CUR_DIR', realpath(dirname(__FILE__)));
define('MOD_DIR', CUR_DIR . '/modules/');
define('ADMIN_DIR', CUR_DIR . '/admin');
define('CORE_DIR', CUR_DIR . '/core');
define('EXT_DIR', CORE_DIR . '/ext');
define('HOOKS_DIR', CUR_DIR . '/hooks');
define('THEME_DIR', CUR_DIR . '/templates/');
define('CACHE_DIR', CUR_DIR . '/cache/');

//This is only a workaround until config is done
define('SECRET_KEY', 'd_(qC?+sG9J}4#z[.#u^-y2G');

define('DB_PREFIX', 'ezrpg_');
define('VERSION', '1.2.1.9');
define('SHOW_ERRORS', 0);
define('DEBUG_MODE', 0);
//end workaround

require_once(CUR_DIR . '/lib.php');
$debugTimer['Library Loaded:'] = microtime(1);
