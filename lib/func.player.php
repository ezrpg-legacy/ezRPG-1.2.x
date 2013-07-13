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
function requireLogin($msg='')
{
    if (!LOGGED_IN)
    {
        if (!empty($msg)){
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
    if (!isset($player) || $player->rank < 5)
    {
        header('Location: index.php');
        exit;
    }
}

function loadMetaCache($kill = 0)
{
	global $db, $debugTimer;
       //Select player details
			
			$query = 'SELECT * FROM `<ezrpg>players_meta` WHERE pid = ' . $_SESSION['userid'];
			$cache_file = md5($query);
			$cache = CACHE_DIR . $cache_file;
			if ($kill == 1) 
				unlink( $cache );
			
			if( file_exists( $cache ) )
			{
				if( filemtime( $cache ) > time( ) - 60 * 60 * 24 ) 
				{
					$array = unserialize( file_get_contents( $cache ) );
					if ( DEBUG_MODE == 1 ) {
					echo 'Loaded Player_Meta Cache! <br />';
					}
				} else {
					unlink( $cache );
					//$query1 = $db->execute($query);
					$array = $db->fetchRow($query);
					file_put_contents( CACHE_DIR . $cache_file, serialize( $array ) );
					if ( DEBUG_MODE == 1 ) {
						echo 'Created Player_Meta Cache! <br />';
					}
				}
			} else {
				//$query1 = $db->execute($query);
				$array = $db->fetchRow($query);
				file_put_contents( CACHE_DIR . $cache_file, serialize( $array ) );
				if ( DEBUG_MODE == 1 ) {
					echo 'Created Player_Meta Cache! <br />';
				}
			}
    return $array;
}
?>