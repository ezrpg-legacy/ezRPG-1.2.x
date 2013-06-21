<?php
defined('IN_EZRPG') or exit;

$hooks->add_hook('header', 'header_msg', 1);
$hooks->add_hook('admin_header', 'header_msg', 1);

function hook_header_msg(&$db, &$tpl, &$player, $args = 0)
{
	// validate that there is something
	if (!array_key_exists('status_messages', $_SESSION)) 
		return $args;

	// loop through the SESSION variable and push it to the template
	foreach($_SESSION['status_messages'] as $key => $message) {
		if (strlen($message) > 0) {
			$tpl->assign('MSG_' . $key, $message);
		}
	}

	// remove the session
	unset($_SESSION['status_message']);
    
    return $args;
}
?>