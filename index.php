<?php
define('IN_EZRPG', true);

$debugTimer['ezRPG start']=microtime(1);

if (!file_exists('config.php') OR filesize('config.php') == 0) {
  header('Location: install/index.php');
	exit(1);
}
require_once 'init.php';
$debugTimer['init.php Loaded:']=microtime(1);
$hooks->run_hooks('init');
$debugTimer['Init-hooks Loaded:']=microtime(1);
$default_mod = 'Index';

$module_name = ( (isset($_GET['mod']) && ctype_alnum($_GET['mod'])) ? $_GET['mod'] : $default_mod );
//Header hooks
$module_name = $hooks->run_hooks('header', $module_name);
$debugTimer['header-hooks Loaded:']=microtime(1);

//Begin module
$module = ModuleFactory::factory($db, $tpl, $player, $module_name, $menu, $settings);
$module->start();
$debugTimer['modulefactory']=microtime(1);

//Footer hooks
$hooks->run_hooks('footer', $module_name);
$debugTimer['footer-hooks']=microtime(1);

// DEBUG_INFO
if ( DEBUG_MODE == 1 ) {
//if ($player->rank > 5 ) {
echo "<table border=1><tr><td>name</td><td>Total Time</td><td>Step Time</td><td>%</td></tr>";
reset($debugTimer);
$start=$prev=current($debugTimer);
$total=end($debugTimer)-$start;
foreach($debugTimer as $name => $value) {
	$sofar=round($value-$start,3);
	$delta=round($value-$prev,3);
	$percent=round($delta/$total*100);
	echo "<tr><td>$name</td><td>$sofar</td><td>$delta</td><td>$percent</td></tr>";
	$prev=$value;
}
	echo "</table>";
	$mem = memory_get_usage();
	$unit=array('b','kb','mb','gb','tb','pb');
	echo "Memory Used: ". round($mem/pow(1024,($i=floor(log($mem,1024)))),2).' '.$unit[$i];
//}
}
?>
