<?php

/*
  Name: Index.php
  URI: http://ezrpgproject.net/
  Author: Zeggy, Booher, UAKTags
  Package: ezRPG-Core
 */

namespace ezRPG;
use ezRPG\lib\ModuleFactory;

// Define IN_EZRPG as TRUE
define('IN_EZRPG', true);

if(!file_exists(__DIR__ . '/vendor/autoload.php'))
    die('You must initialize composer!');

// Start the Debug Timer. @since 1.2RC
$debugTimer['ezRPG start'] = microtime(1);

// Check for config and if it has data. @since 1.2RC
if ( !file_exists('config.php') OR filesize('config.php') == 0 )
{
    //Redirect to installer @since 1.x
    header('Location: install/index.php');
    exit(1);
}

// Load init.php
require_once 'init.php';

// Start the Debug Timer. @since 1.2RC
$debugTimer['init.php Loaded:'] = microtime(1);

//Set Default module and check if Module is selected in URI
$default_mod = $container['settings']->setting['general']['default_module']['value'];
$module_name = ( (isset($_GET['mod']) && ctype_alnum($_GET['mod']) && isModuleActive($_GET['mod'])) ? $_GET['mod'] : $default_mod );

//Init Hooks - Runs before Header
$hooks->run_hooks('init');
$debugTimer['Init-hooks Loaded:'] = microtime(1);

//Header hooks
$module_name = $hooks->run_hooks('header', $module_name);
$debugTimer['header-hooks Loaded:'] = microtime(1);

//Begin module
$module = ModuleFactory::factory($container, $module_name, $menu);
if ( isset($_GET['act']))
{
		if (method_exists($module, $_GET['act']))
		{
			$reflection = new ReflectionMethod($module, $_GET['act']);
			if($reflection->isPublic())
				$module->$_GET['act']();
			else
				$module->start();
		}else{
			$module->start();
		}
}else{
	$module->start();
}
$debugTimer[$module_name . 'Loaded'] = microtime(1);

//Footer hooks
$hooks->run_hooks('footer', $module_name);
$debugTimer['footer-hooks'] = microtime(1);

// DEBUG_INFO with Timer @since 1.2RC
if ( DEBUG_MODE )
{
if (is_object($container['player']) && $container['player']->rank > 5 ) {
    echo "<pre><table border=1><tr><td>name</td><td>Total Time</td><td>Step Time</td><td>%</td></tr>";
    reset($debugTimer);
    $start = $prev = current($debugTimer);
    $total = end($debugTimer) - $start;
    foreach ( $debugTimer as $name => $value )
    {
        $sofar = round($value - $start, 4);
        $delta = round($value - $prev, 4);
        $percent = round($delta / $total * 100);
        echo "<tr><td>$name</td><td>$sofar</td><td>$delta</td><td>$percent</td></tr>";
        $prev = $value;
    }
    echo "</table>";
    $mem = memory_get_usage();
    $unit = array( 'b', 'kb', 'mb', 'gb', 'tb', 'pb' );
    echo "Memory Used: " . round($mem / pow(1024, ($i = floor(log($mem, 1024)))), 2) . ' ' . $unit[$i] . "</pre>";
    }
}
?>
