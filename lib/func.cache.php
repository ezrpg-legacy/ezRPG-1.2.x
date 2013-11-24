<?php

if ( !defined('IN_EZRPG') )
    exit;

/*
  Title: Module functions
  This file contains functions used for security pruposes.
 */

/*
  Function: loadModuleCache
  Loads the Module Cache from /cache

  Paramaters:

  Returns:
  Array - The Cache.
 */

function loadModuleCache()
{
    global $app;
	$dbase = $app['db'];
    $query = 'SELECT <ezrpg>plugins.*, <ezrpg>plugins_meta.* FROM `<ezrpg>plugins` INNER JOIN <ezrpg>plugins_meta WHERE <ezrpg>plugins_meta.plug_id = <ezrpg>plugins.id';
    $cache_file = md5($query);
    $cache = CACHE_DIR . $cache_file;
    if ( file_exists($cache) )
    {
        if ( filemtime($cache) > time() - 60 * 60 * 24 )
        {
            $array = unserialize(file_get_contents($cache));
            if ( DEBUG_MODE )
            {
				$_SESSION['status_messages']['Admin_Message'] = array('GOOD' => 'Loaded Module Cache!');
            }
        }
        else
        {
            unlink($cache);
            $query1 = $dbase->execute($query);
            $array = $dbase->fetchAll($query1);
            file_put_contents(CACHE_DIR . $cache_file, serialize($array));
            if ( DEBUG_MODE )
            {
                $_SESSION['status_messages']['Admin_Message'] = array('GOOD' => 'Created Module Cache');
            }
        }
    }
    else
    {
        $query1 = $dbase->execute($query);
        $array = $dbase->fetchAll($query1);
        file_put_contents(CACHE_DIR . $cache_file, serialize($array));
        if ( DEBUG_MODE  )
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
    $query = 'SELECT <ezrpg>plugins.*, <ezrpg>plugins_meta.* FROM `<ezrpg>plugins` INNER JOIN <ezrpg>plugins_meta WHERE <ezrpg>plugins_meta.plug_id = <ezrpg>plugins.id';
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
?>