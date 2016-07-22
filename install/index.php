<?php

namespace ezRPG\Install;
use ezRPG\lib\Application,
    ezRPG\lib\ModuleFactory;

define('IN_EZRPG', true);
define('LIB_DIR', realpath("../lib"));
define('CUR_DIR', realpath(dirname(__FILE__)));
define('MOD_DIR', CUR_DIR . '/modules');
define('CACHE_DIR', '../cache/');
define('EXT_DIR', '../lib/ext');
define('ROOT_DIR' , dirname(CUR_DIR));

require_once '../lib/Base_Module.php';
require_once '../lib/DbException.php';
require_once '../lib/const.errors.php';
require_once '../lib/DbFactory.php';
require_once '../lib/functions/func.modules.php';
require_once CUR_DIR . '/lib/InstallerFactory.php';

$_SESSION['in_installer'] = true;

if (file_exists(ROOT_DIR . '/vendor/autoload.php')) {
    require ROOT_DIR . '/vendor/autoload.php';
} else {
    die('You must initialize composer!');
}

function ezrpg_Autoloader($pClassName)
{
    $class = str_replace("ezRPG\\lib\\", "", $pClassName);
    if (file_exists(ROOT_DIR . "/lib/" . $class . ".php")) {
        include(ROOT_DIR . "/lib/" . $class . ".php");
    }
}
spl_autoload_register("ezRPG\\Install\\ezrpg_Autoloader");

$container = new \Pimple\Container;
$ezrpg = new Application($container);

$installer = new InstallerFactory($container);
$default_mod = 'Index';

$module_name = ( (isset($_GET['step']) && ctype_alnum($_GET['step'])) ? $_GET['step'] : $default_mod );

//Begin module
$module = InstallerFactory::module($container, $installer, $module_name);
$module->start();
?>