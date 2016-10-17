<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Title: Player Functions
  This file contains functions that deal with players.
 */

/*
  Function: requireLogin
  Checks if the player is logged in. If not, the user is redirected back to the home page.

  Parameters:
  $msg - An optional message to show the user after they are redirected.

  Example Usage:
  Just call the requireLogin() function in a module's start() method *once*, if the module requires a user to be logged in.

  The function must be called before any output is made (so before templates have been displayed).
 */

function requireLogin($msg = '')
{
    if (!LOGGED_IN) {
        if (!empty($msg)) {
            $this->setMessage($msg);
            header('Location: index.php');
        } else {
            header('Location: index.php');
        }
        exit;
    }
}

/*
  Function: requireAdmin
  Checks if the player is an admin. If not, the user is redirected back to the home page.

  Parameters:
  $player - A player object.

  Example Usage:
  Just call the requireAdmin() function if you require the user to be an admin.

  The function must be called before any output is made.
 */

function requireAdmin($player = 0)
{
    if (!isset($player) || $player->rank < 5) {
        header('Location: index.php');
        exit;
    }
}

/*
  Function: isAdmin
  Checks if the player is an admin. Returns a boolean instead of redirect.

  Parameters:
  $player - A player object.
  
  Return:
  boolean (TRUE / FALSE)
  
  Example Usage:
  Just call the isAdmin() function if you require the user to be an admin.

  Use isAdmin if you need to just check for admin but don't need to redirect.
  Use requireAdmin if the page is ONLY for admins and you need to redirect out.
 */

function isAdmin($player = 0)
{
    if ($player->rank < 5) {
        return false;
    }

    return true;
}

/*
  Function: loadMetaCache
  Checks for and loads the Players_Meta cache file.

  Parameters:
  $kill - optional - Dictates if a new Cache should be made.

  Example Usage:
  Just call the loadMetaCache(1) function if you want to force a new Cache file.
 */

function loadMetaCache($kill = 0, $id = 0)
{
    global $container, $debugTimer;
    if ($id != 0) {
        $playerID = $id;
    } elseif (isset($_SESSION['userid'])) {
        $playerID = $_SESSION['userid'];
    } else {
        return;
    }
    $query = 'SELECT * FROM `<ezrpg>players_meta` WHERE pid = ' . $playerID;
    $cache_file = md5($query);
    $cache = CACHE_DIR . $cache_file;
    if (file_exists($cache) && $kill == 1) {
        unlink($cache);
    }
    if (file_exists($cache)) {
        if (filemtime($cache) > time() - 60 * 60 * 24) {
            $array = unserialize(file_get_contents($cache));
            if (DEBUG_MODE == 1) {
                echo 'Loaded Player_Meta Cache! <br />';
            }
        } else {
            unlink($cache);
            $array = $container['db']->fetchRow($query);
            file_put_contents(CACHE_DIR . $cache_file, serialize($array));
            if (DEBUG_MODE == 1) {
                echo 'Created Player_Meta Cache! <br />';
            }
        }
    } else {
        $array = $container['db']->fetchRow($query);
        file_put_contents(CACHE_DIR . $cache_file, serialize($array));
        if (DEBUG_MODE == 1) {
            echo 'Created Player_Meta Cache! <br />';
        }
    }

    return $array;
}

function forcePrunePlayerCache()
{
    global $container;
    $container['db']->execute('UPDATE <ezrpg>players SET force_cache = 1');

    return true;
}

?>