<?php

namespace ezrpg\Install;
use ezrpg\core\Application,
    ezrpg\core\ModuleFactory;

$rootPath = dirname(dirname(__DIR__));

// Traverse back one directory
chdir($rootPath);

define('IN_EZRPG', true);
define('CUR_DIR', realpath(__DIR__));
define('ROOT_DIR', realpath($rootPath));
define('CORE_DIR', ROOT_DIR . "/core");
define('MOD_DIR', CUR_DIR . '/modules');
define('CACHE_DIR', ROOT_DIR . '/cache/');
define('EXT_DIR', CORE_DIR . '/ext');
define('HOOKS_DIR', ROOT_DIR . '/hooks');

require_once CORE_DIR . '/src/Base_Module.php';
require_once CORE_DIR . '/const.errors.php';
require_once CORE_DIR . '/src/database/DatabaseFactory.php';
require_once CORE_DIR . '/functions/modules.php';
require_once CUR_DIR . '/core/InstallerFactory.php';

$_SESSION['in_installer'] = true;

if (file_exists(ROOT_DIR . '/vendor/autoload.php')) {
    require ROOT_DIR . '/vendor/autoload.php';
} else {
    die('You must initialize composer!');
}

function ezrpg_Autoloader($pClassName)
{
    $class = str_replace("ezrpg\\core\\", "", $pClassName);
    if (file_exists(CORE_DIR . "/" . $class . ".php")) {
        include(CORE_DIR . "/" . $class . ".php");
    }
}
spl_autoload_register("ezRPG\\Install\\ezrpg_Autoloader");

$container = new \Pimple\Container;
$ezrpg = new Application($container);
$ezrpg->getHooks();

$installer = new InstallerFactory($ezrpg->container);
$default_mod = 'Index';

$module_name = ( (isset($_GET['step']) && ctype_alnum($_GET['step'])) ? $_GET['step'] : $default_mod );

//Begin module
$module = InstallerFactory::module($ezrpg->container, $installer, $module_name);
$module->start();
?>
