<?php
define('IN_EZRPG', true);
define('LIB_DIR', realpath("../lib"));
define('CUR_DIR', realpath(dirname(__FILE__)));
define('MOD_DIR', CUR_DIR . '/modules');
define('EXT_DIR', '../lib/ext');

require_once '../lib/class.base_module.php';
require_once '../lib/exception.db.php';
require_once '../lib/const.errors.php';
require_once '../lib/class.dbfactory.php';
require_once CUR_DIR . '/lib/class.installerfactory.php';

$installer = new InstallerFactory;
$default_mod = 'Index';

$module_name = ( (isset($_GET['step']) && ctype_alnum($_GET['step'])) ? $_GET['step'] : $default_mod );

//Begin module
$module = InstallerFactory::module($installer, $module_name);
$module->start();
?>