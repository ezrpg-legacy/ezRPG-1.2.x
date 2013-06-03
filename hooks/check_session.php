<?php
defined('IN_EZRPG') or exit;

//Add a player object hook - check the user session, priority 0
$hooks->add_hook('player', 'check_session', 0);

//Player hook to check the session and get player data
function hook_check_session($db, &$tpl, $player, $args = 0)
{
    if (isset($_SESSION['userid']) && isset($_SESSION['hash']))
    {
        //Check if user logged in
        $session_check = sha1($_SESSION['userid'] . $_SERVER['REMOTE_ADDR'] . SECRET_KEY);
        
        if ($_SESSION['hash'] == $session_check)
        {
            //Select player details
            $player = $db->fetchRow('SELECT <ezrpg>players.id, <ezrpg>players.username, 
			<ezrpg>players.email, <ezrpg>players.rank, <ezrpg>players.registered, 
			<ezrpg>players_meta.* FROM `<ezrpg>players` INNER JOIN `<ezrpg>players_meta` ON <ezrpg>players.id = <ezrpg>players_meta.pid WHERE `id`=?', array($_SESSION['userid']));
			$tpl->assign('player', $player);
            
            //Set logged-in flag
            define('LOGGED_IN', true);
            $tpl->assign('LOGGED_IN', 'TRUE');
        }
        else
        {
            if (isset($_SESSION['hash']))
                unset($_SESSION['hash']);
            if (isset($_SESSION['userid']))
                unset($_SESSION['userid']);
            
            define('LOGGED_IN', false);
        }
    }
    else
    {
        define('LOGGED_IN', false);
    }
    
    return $player;
}
?>
