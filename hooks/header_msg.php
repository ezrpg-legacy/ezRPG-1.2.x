<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('header', 'header_msg', 1);
$hooks->add_hook('admin_header', 'header_msg', 1);

function hook_header_msg(&$db, &$tpl, &$player, $args = 0)
{
    // validate that there is something
    if ( !array_key_exists('status_messages', $_SESSION) )
        return $args;

    // loop through the SESSION variable and push it to the template
    $status_messages = array( );
    foreach ( $_SESSION['status_messages'] as $key )
    {
        foreach ( $key as $level => $message )
        {
            if ( strlen($message) > 0 )
            {
                $status = array( $level => $message );
                array_push($status_messages, $status);
            }
        }
    }
    if ( empty($status_messages) )
        $status_messages = null;

    $tpl->assign('MSG', $status_messages);
    // remove the session
    unset($_SESSION['status_message']);

    return $args;
}

?>