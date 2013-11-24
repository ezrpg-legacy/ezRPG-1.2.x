<?php

defined('IN_EZRPG') or exit;

$app['hooks']->add_hook('header', 'admin_controls', 1);
$app['hooks']->add_hook('admin_header', 'admin_controls', 1);

function hook_admin_controls($app, $args = 0)
{
	$dbase = $app['db']; $tpl=$app['tpl']; $player=$args['player'];
	if(isset($_GET['admin']))
	{
		if(isAdmin($player)){
			switch($_GET['admin'])
			{
				case 'flushSettings':
					killSettingsCache();
					break;
				case 'flushModules':
					killModuleCache();
					break;
				case 'flushMenus':
					killMenuCache();
					break;
				case 'flushPlayer':
					killPlayerCache($player->id);
					break;
				case 'flushCaches':
					killSettingsCache();
					killModuleCache();
					killMenuCache();
					killPlayerCache($player->id);
					break;
			}
		}
	}
	$app['debugTimer']['admin_controls hook Loaded:'] = microtime(1);
    return $args;
}

?>