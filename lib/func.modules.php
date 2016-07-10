<?php

if ( !defined('IN_EZRPG') )
    exit;

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

function loadModuleCache()
{
    global $container;
    $db = $container['db'];
    $query = 'SELECT * FROM `<ezrpg>plugins` WHERE active = 1';
    $cache_file = md5($query);
    $cache = CACHE_DIR . $cache_file;
    if ( file_exists($cache) )
    {
        if ( filemtime($cache) > time() - 60 * 60 * 24 )
        {
            $array = unserialize(file_get_contents($cache));
            if ( DEBUG_MODE == 1 )
            {
				$_SESSION['status_messages']['Admin_Message'] = array('GOOD' => 'Admin: Loaded Module Cache!');
            }
        }
        else
        {
            unlink($cache);
            $query1 = $db->execute($query);
            $array = $db->fetchAll($query1);
            file_put_contents(CACHE_DIR . $cache_file, serialize($array));
            if ( DEBUG_MODE == 1 )
            {
                $_SESSION['status_messages']['Admin_Message'] = array('GOOD' => 'Created Module Cache');
            }
        }
    }
    else
    {
        $query1 = $db->execute($query);
        $array = $db->fetchAll($query1);
        file_put_contents(CACHE_DIR . $cache_file, serialize($array));
        if ( DEBUG_MODE == 1 )
        {
            $_SESSION['status_messages']['Admin_Message'] = array('GOOD' => 'Created Module Cache');
        }
    }
    $debugTimer['Loaded Module Cache'] = microtime(1);
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
    $query = 'SELECT * FROM `<ezrpg>plugins` WHERE active = 1';
    $cache_file = md5($query);
    if(file_exists( CACHE_DIR . $cache_file ) )
	{
		$cache = CACHE_DIR . $cache_file;
		unlink($cache);
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
    if(file_exists( CACHE_DIR . $cache_file ) )
	{
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
 */

function killSettingsCache()
{
    $query = 'SELECT * FROM `<ezrpg>settings`';
    $cache_file = md5($query);
	if(file_exists( CACHE_DIR . $cache_file ) )
	{
	    unlink(CACHE_DIR . $cache_file);
		$_SESSION['status_messages']['Admin_Message'] = array('GOOD' => 'Settings Cache Cleaned');
    }
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
    if ( file_exists(CACHE_DIR . $cache_file) )
    {
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
    if ( $modules == 0 )
        $modules = (array) loadModuleCache();
    foreach ( $modules as $key => $item )
    {
        if ( in_array($name, (array) $item) )
            return true;
    }
    return false;
}

function setModuleActive($name, $modules = 0)
{
    global $db;

    if ( $modules == 0 )
        $modules = (array) loadModuleCache();

    $db->execute('UPDATE `<ezrpg>plugins` SET `active`=1 WHERE `title`=?', array( $name ));
    killModuleCache();
    setMenuActive($name);
    return true;
}

function setModuleDeactive($name, $modules = 0)
{
    global $db;

    if ( $modules == 0 )
        $modules = (array) loadModuleCache();

    $db->execute('UPDATE `<ezrpg>plugins` SET  `active`=0 WHERE `title`=?', array( $name ));
    killModuleCache();
    setMenuActive($name);
    return true;
}

function secondaryInstallComplete($name, $modules = 0)
{
    global $db;

    if ( $modules == 0 )
        $modules = (array) loadModuleCache();

    $db->execute('UPDATE `<ezrpg>plugins` SET `second_installed`=1 WHERE `title`=?', array( $name ));
    killModuleCache();
    setMenuActive($name);
    return true;
}

function setMenuActive($name)
{
    global $db;
    $modules = (array) loadModuleCache();
    foreach ( $modules as $key => $item )
    {
        if ( in_array($name, (array) $item) )
        {
            $mod = (array) $item;
            $mod_id = $mod['id'];
            $db->execute('UPDATE `<ezrpg>menu` SET `active`=1 WHERE `module_id`=?', array( $mod_id ));
            killMenuCache();
            return true;
        }
    }
    return false;
}

?>
