<?php

/*
  Name: Index.php
  URI: http://ezrpgproject.net/
  Author: Zeggy, Booher, UAKTags
  Package: ezRPG-Core
 */

// Define IN_EZRPG as TRUE
define('IN_EZRPG', true);

// Start the Debug Timer. @since 1.2RC
$app['debugTimer']['ezRPG start'] = microtime(1);

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
$app['debugTimer']['init.php Loaded:'] = microtime(1);

//Set Default module and check if Module is selected in URI
$default_mod = $app['settings']->setting['general']['default_module']['value'];
$app['module_name'] = ( (isset($_GET['mod']) && ctype_alnum($_GET['mod'])) ? $_GET['mod'] : $default_mod );


//Init Hooks - Runs before Header
$app['hooks']->run_hooks('init');
$app['debugTimer']['Init-hooks Loaded:'] = microtime(1);
//Header hooks

$module_name = $app['hooks']->run_hooks('header', $app);

$app['debugTimer']['header-hooks Loaded:'] = microtime(1);
//Begin module
$module = $app['module_name'];
if(class_exists($module))
	$module = new $module($app);
elseif(class_exists($module = 'Module_'.$module))
	$module = new $module($app);
$module->start();
$app['debugTimer'][$app['module_name'] . 'Loaded'] = microtime(1);

//Footer hooks
$app['hooks']->run_hooks('footer', $app['module_name']);
$app['debugTimer']['footer-hooks'] = microtime(1);

// DEBUG_INFO with Timer @since 1.2RC
if ( DEBUG_MODE )
{
//if (is_object($player) && $player->rank > 5 ) {
    echo "<pre><table border=1><tr><td>name</td><td>Total Time</td><td>Step Time</td><td>%</td></tr>";
    reset($app['debugTimer']);
    $start = $prev = current($app['debugTimer']);
    $total = end($app['debugTimer']) - $start;
    foreach ( $app['debugTimer'] as $name => $value )
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
   // }
}
?>
