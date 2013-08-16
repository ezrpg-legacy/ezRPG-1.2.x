<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('header', 'admin_controls', 1);
$hooks->add_hook('admin_header', 'admin_controls', 1);

function hook_admin_controls(&$db, &$tpl, &$player, $args = 0)
{
    
    if(isset($_GET['admin']))
	{
		if(isAdmin($player)){
			switch($_GET['admin'])
			{
				case 'flushSettings':
					$tpl->assign('MSG', 'Flushed Settings Cache!');
					killSettingsCache();
					break;
				case 'flushModules':
					killModuleCache();
					break;
			}
		}
	}
    //$tpl->assign('MSG', $status_messages);

    return $args;
}

?>