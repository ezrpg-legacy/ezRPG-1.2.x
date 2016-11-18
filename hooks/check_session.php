<?php

defined('IN_EZRPG') or exit;

//Add a player object hook - check the user session, priority 0
$hooks->add_hook('player', 'check_session', 0);

//Player hook to check the session and get player data
function hook_check_session($container, $args = 0)
{
    global $debugTimer;
    // we follow a "guilty" until proven otherwise approach.
    $authenticated = false;

    if (array_key_exists('userid', $_SESSION) && array_key_exists('hash', $_SESSION)) {
        // The client has prompted that they have authorization details.
        // Validate they they are indeed valid: this will be in the for
        if (compareSignature($_SESSION['hash'])) {
            //Select player details from Cache
            $query = 'SELECT id, username,
			email, rank, registered
			FROM `<ezrpg>players` WHERE id = ' . $_SESSION['userid'];
            $player_base = $container['db']->fetchRow($query);
            $player_meta = $container['db']->fetchRow('SELECT * FROM `<ezrpg>players_meta` WHERE pid = ' . $_SESSION['userid']);
            $player = (object)array_merge((array)$player_base, (array)$player_meta);
            $container['tpl']->assign('player', $player);
            $container['player'] = $player;

            // Set logged-in flag
            $authenticated = true;

            $container['tpl']->assign('ADMIN', ($player->rank > 5) ? 'TRUE' : 'FALSE');
            // check the last time the user was active.
            // if they weren't active for a certain time period, prompt for password again.
            // Changed to 10Minutes
            $mins = $container['config']['session']['lifetime'];
            $secs = $mins * 60;
            if ($_SESSION['last_active'] < (time() - $secs)) {
                if (isset($_GET['mod'])) {
                    if (!in_array($_GET['mod'], array('Logout'))) {
                        session_destroy();
                        session_start();
                        $_SESSION['last_page'] = $_SERVER['REQUEST_URI'];
                        $_SESSION['status_messages']['Session_Loggedout'] = array('INFO' => 'You have been logged out due to inactivity!');
                        header('location: index.php');
                        exit;
                    }
                } else {
                    $_SESSION['last_active'] = time();
                }
            }
        } else {
            session_destroy();
        }
    }

    define('LOGGED_IN', $authenticated);

    $container['tpl']->assign('LOGGED_IN', (LOGGED_IN === true) ? 'TRUE' : 'FALSE');

    return $container['player'];
}

?>
