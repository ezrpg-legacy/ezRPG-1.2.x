<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('header', 'admin_controls', 1);
$hooks->add_hook('admin_header', 'admin_controls', 1);

function hook_admin_controls($container, $args = 0)
{
    if (isset($_GET['admin'])) {
        if (isAdmin($container['player'])) {
            switch ($_GET['admin']) {
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
                    killPlayerCache($container['player']->id);
                    break;
                case 'flushCaches':
                    killSettingsCache();
                    killModuleCache();
                    killMenuCache();
                    killPlayerCache($container['player']->id);
                    break;
            }
        }
    }

    return $args;
}

?>