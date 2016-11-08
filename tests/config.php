<?php
/**
 * Created by PhpStorm.
 * User: tgarrity
 * Date: 11/8/2016
 * Time: 12:55 PM
 */

namespace ezrpg;
use \ezrpg\core\Application;

ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define IN_EZRPG as TRUE
define('IN_EZRPG', true);

$rootPath = dirname(__DIR__);

// Traverse back one directory
chdir($rootPath);

// Start the Debug Timer. @since 1.2RC
$debugTimer['ezRPG start'] = microtime(1);

session_start();
// Load init.php
require_once $rootPath .'/init.php';
$debugTimer['loaded init'] = microtime(1);

$container = new \Pimple\Container;
$debugTimer['initiated container'] = microtime(1);
$ezrpg = new Application($container);
$debugTimer['loaded ezrpg\\Application'] = microtime(1);

// Get Config for the game
$ezrpg->getConfig();
$debugTimer['loaded Config'] = microtime(1);

if(defined('SHOW_ERRORS'))
    echo "It's defined";

print("<pre>".print_r($ezrpg->container['config'],true)."</pre>");
$debugTimer['done'] = microtime(1);
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
