<?php

if (!defined('IN_EZRPG')) {
    exit;
}

/*
  Title: Module functions
  This file contains functions used for security pruposes.
 */

/*
  Function: LoadModuleCache
  Loads the Module Cache from /cache

  Paramaters:

  Returns:
  Array - The Cache.
 */

function loadModuleCache($admin = false)
{
    global $container;
    if(file_exists(CACHE_DIR . '/module_cache')){
        $cache = file_get_contents(CACHE_DIR . '/module_cache');
        $plugins = unserialize($cache);
        if(!$admin)
            return $plugins['public'];
        else
            return $plugins['admin'];
    }else{
        $mod = new \ezrpg\core\ModuleFactory();
        $pluginManager= $mod::adminFactory($container, 'Plugins');
        $array = $pluginManager->setModuleCache();
    }

    return $array;
}

/*
  Function: killModuleCache
  Wipes the Module Cache from /cache

  Paramaters:

  Returns:
  TRUE
 */

function killModuleCache()
{
    if (file_exists(CACHE_DIR . 'module_cache')) {
        $cache = CACHE_DIR . 'module_cache';
        unlink($cache);
        loadModuleCache();
        $_SESSION['status_messages']['Admin_Message'] = array('GOOD' => 'Module Cache Cleaned');
    }

    return true;
}

/*
  Function: killMenuCache
  Wipes the Menu Cache from /cache

  Paramaters:

  Returns:
  TRUE
 */

function killMenuCache()
{
    $query = 'SELECT * FROM `<ezrpg>menu` WHERE active = 1 ORDER BY `pos`';
    $cache_file = md5($query);
    if (file_exists(CACHE_DIR . $cache_file)) {
        unlink(CACHE_DIR . $cache_file);
        $_SESSION['status_messages']['Admin_Message'] = array('GOOD' => 'Menu Cache Cleaned');
    }

    return true;
}

/*
  Function: killSettingsCache
  Wipes the Settings Cache from /cache

  Paramaters:

  Returns:
  TRUE

  DEPRECATED
 */

function killSettingsCache()
{
    //$query = 'SELECT * FROM `<ezrpg>settings`';
    //$cache_file = md5($query);
    //if (file_exists(CACHE_DIR . $cache_file)) {
    //    unlink(CACHE_DIR . $cache_file);
        $_SESSION['status_messages']['Admin_Message'] = array('WARN' => '`killSettingsCache` is Deprecated and not in use.');
    //}

    return true;
}

/*
  Function: killPlayerCache
  Wipes the Player Cache from /cache

  Paramaters:
  $id = User's id whose cache is wiped
  Returns:
  TRUE
 */

function killPlayerCache($id)
{
    $query = 'SELECT id, username, 
			email, rank, registered
			FROM `<ezrpg>players` WHERE id = ' . $id;
    $cache_file = md5($query);
    if (file_exists(CACHE_DIR . $cache_file)) {
        unlink(CACHE_DIR . $cache_file);
    }
    $_SESSION['status_messages']['Admin_Message'] = array('GOOD' => 'Player Cache Cleaned');
    loadMetaCache(1, $id);

    return true;
}

/*
  Function: isModuleActive
  checks if module is in Module array

  Paramaters:
  $name - title of the module checking
  $modules - optional param for the modulecache

  Returns:
  BOOL - TRUE/FALSE.
 */

function isModuleActive($name, $modules = 0)
{
    if ($modules == 0) {
        $modules = (array)loadModuleCache();
    }
    foreach ($modules as $key => $item) {
        if (in_array($name, (array)$item)) {
            if($key == $name)
                return $item['active'];
        }
    }

    return false;
}

function setMenuActive($name)
{
    global $db;
    $modules = (array)loadModuleCache();
    foreach ($modules as $key => $item) {
        if (in_array($name, (array)$item)) {
            $mod = (array)$item;
            $mod_id = $mod['id'];
            $db->execute('UPDATE `<ezrpg>menu` SET `active`=1 WHERE `module_id`=?', array($mod_id));
            killMenuCache();

            return true;
        }
    }

    return false;
}

?>
