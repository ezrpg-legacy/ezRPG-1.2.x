<?php

define('IN_EZRPG', true);
define('LIB_DIR', realpath("../lib"));
define('CUR_DIR', realpath(dirname(__FILE__)));
define('MOD_DIR', CUR_DIR . '/modules');
define('CACHE_DIR', '../cache/');
define('EXT_DIR', '../lib/ext');

require_once '../lib/Base_Module.php';
require_once '../lib/exception.db.php';
require_once '../lib/const.errors.php';
require_once '../lib/DbFactory.php';
require_once '../lib/func.modules.php';
require_once CUR_DIR . '/lib/class.installerfactory.php';

$_SESSION['in_installer'] = true;

$installer = new InstallerFactory;
$default_mod = 'Index';

$module_name = ( (isset($_GET['step']) && ctype_alnum($_GET['step'])) ? $_GET['step'] : $default_mod );

//Begin module
$module = InstallerFactory::module($installer, $module_name);
$module->start();
?>