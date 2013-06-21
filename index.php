<?php
define('IN_EZRPG', true);

$TIMER['ezRPG start']=microtime(1);

if (!file_exists('config.php')) {
  header('Location: install/index.php');
	exit(1);
}
require_once 'init.php';
$TIMER['init.php Loaded:']=microtime(1);
$default_mod = 'Index';

$module_name = ( (isset($_GET['mod']) && ctype_alnum($_GET['mod'])) ? $_GET['mod'] : $default_mod );

//Header hooks
$module_name = $hooks->run_hooks('header', $module_name);
$TIMER['header-hooks Loaded:']=microtime(1);

//Begin module
$module = ModuleFactory::factory($db, $tpl, $player, $module_name, $menu, $settings);
$module->start();
$TIMER['modulefactory']=microtime(1);

//Footer hooks
$hooks->run_hooks('footer', $module_name);
$TIMER['footer-hooks']=microtime(1);

// DEBUG_INFO
if ( DEBUG_MODE == 1 ) {
echo "<table border=1><tr><td>name</td><td>Total Time</td><td>Step Time</td><td>%</td></tr>";
reset($TIMER);
$start=$prev=current($TIMER);
$total=end($TIMER)-$start;
foreach($TIMER as $name => $value) {
	$sofar=round($value-$start,3);
	$delta=round($value-$prev,3);
	$percent=round($delta/$total*100);
	echo "<tr><td>$name</td><td>$sofar</td><td>$delta</td><td>$percent</td></tr>";
	$prev=$value;
}
	echo "</table>";
}
?>
