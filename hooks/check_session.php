<?php

defined('IN_EZRPG') or exit;

//Add a player object hook - check the user session, priority 0
$hooks->add_hook('player', 'check_session', 0);

//Player hook to check the session and get player data
function hook_check_session($db, &$tpl, $player, $args = 0)
{
    global $debugTimer;
    // we follow a "guilty" until proven otherwise approach.
    $authenticated = false;

    if ( array_key_exists('userid', $_SESSION) && array_key_exists('hash', $_SESSION) )
    {
        // The client has prompted that they have authorization details.
        // Validate they they are indeed valid: this will be in the for 
        if ( compareSignature($_SESSION['hash']) )
        {
            //Select player details from Cache
            $query = 'SELECT id, username, 
			email, rank, registered
			FROM `<ezrpg>players` WHERE id = ' . $_SESSION['userid'];
            $cache_file = md5($query);
            $cache = CACHE_DIR . $cache_file;
            if ( file_exists($cache) )
            {
                if ( filemtime($cache) > time() - 60 * 60 * 24 )
                {
                    $array = unserialize(file_get_contents($cache));
                    if ( DEBUG_MODE == 1 )
                    {
                        echo 'Loaded Player Cache! <br />';
                    }
                }
                else
                {
                    unlink($cache);
                    //$query1 = $db->execute($query);
                    $array = $db->fetchRow($query);
                    file_put_contents(CACHE_DIR . $cache_file, serialize($array));
                    if ( DEBUG_MODE == 1 )
                    {
                        echo 'Created Player Cache! <br />';
                    }
                }
            }
            else
            {
                //$query1 = $db->execute($query);
                $array = $db->fetchRow($query);
                file_put_contents(CACHE_DIR . $cache_file, serialize($array));
                if ( DEBUG_MODE == 1 )
                {
                    echo 'Created Player Cache! <br />';
                }
            }
            $debugTimer['Loaded Player Cache'] = microtime(1);
            $player_meta = loadMetaCache();
            $debugTimer['Selected DB in Check_Session'] = microtime(1);
            $player = (object) array_merge((array) $array, (array) $player_meta);

            $tpl->assign('player', $player);

            // Set logged-in flag
            $authenticated = true;

            // check the last time the user was active.
            // if they weren't active for a certain time period, prompt for password again.
            if ( $_SESSION['last_active'] < (time() - 300) )
            {
                if ( !in_array($_GET['mod'], array( 'Logout' )) )
                {
                    session_destroy();
					session_start();
                    $_SESSION['last_page'] = $_SERVER['REQUEST_URI'];
					$_SESSION['status_messages']['Session_Loggedout'] = array('INFO' => 'You have been logged out due to inactivity!');
					header('location: index.php');
                    exit;
                }
            }
            else
            {
                $_SESSION['last_active'] = time();
            }
        }
        else
        {
            session_destroy();
        }
    }

    define('LOGGED_IN', $authenticated);
    $tpl->assign('LOGGED_IN', (LOGGED_IN === true) ? 'TRUE' : 'FALSE');

    return $player;
}

?>
