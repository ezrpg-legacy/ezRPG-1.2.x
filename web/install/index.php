<?php

namespace ezRPG\Install;
use ezrpg\core\Application,
    ezrpg\core\ModuleFactory;

$rootPath = dirname(dirname(__DIR__));

// Traverse back one directory
chdir($rootPath);

define('IN_EZRPG', true);
define('CUR_DIR', realpath(__DIR__));
define('ROOT_DIR', realpath($rootPath));
define('LIB_DIR', ROOT_DIR . "/lib");
define('MOD_DIR', CUR_DIR . '/modules');
define('CACHE_DIR', ROOT_DIR . '/cache/');
define('EXT_DIR', LIB_DIR . '/ext');

require_once LIB_DIR . '/Base_Module.php';
require_once LIB_DIR . '/DbException.php';
require_once LIB_DIR . '/const.errors.php';
require_once LIB_DIR . '/DbFactory.php';
require_once LIB_DIR . '/functions/func.modules.php';
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
    if (file_exists(LIB_DIR . "/" . $class . ".php")) {
        include(LIB_DIR . "/" . $class . ".php");
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