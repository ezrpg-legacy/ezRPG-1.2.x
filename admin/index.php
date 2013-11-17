<?php

define('IN_EZRPG', true);
define('IN_ADMIN', true);

require_once '../init.php';

// Check player exists
if ( $app['player'] == '0' )
{
    header('Location: ../index.php');
    exit;
}

//Require admin rank
if ( $app['player']->rank < 5 )
{
    header('Location: ../index.php');
    exit;
}

$default_mod = 'Index';
$app['module_name'] = ( (isset($_GET['mod']) && ctype_alnum($_GET['mod'])) ? $_GET['mod'] : $default_mod );

//Admin header hook
$module_name = $app['hooks']->run_hooks('admin_header', $app);

//Begin module
$module = $app['module_name'];

if(class_exists($module))
	$module = new $module($app);
elseif(class_exists($module = 'Admin_'.$module))
	$module = new $module($app);
$module->start();


//Admin footer hook
$app['hooks']->run_hooks('admin_footer', $app);
?>
