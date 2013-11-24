<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Title: Log Functions
  This file contains functions that may be used with the Event Log module.

  See Also:
  - <Module_EventLog>
 */

/*
  Function: addLog
  Adds a log message to the database for a single player.

  Parameters:
  $player - The ID number of the player (NOT the player object!).
  $msg - A string containing the message to be inserted.
  $dbase - The database object.

  Returns:
  The ID number of the newly inserted log entry.

  Example Usage:
  > $message = 'This is an example log message!';
  > $new_log = addLog($player->id, $message, $dbase);
 */

function addLog($player, $msg, &$dbase)
{
    $insert['player'] = $player;
    $insert['time'] = time();
    $insert['message'] = $msg;

    return $dbase->insert('<ezrpg>player_log', $insert);
}

/*
  Function: checkLog
  Checks the database for new log messages.

  Parameters:
  $player - The ID number of the player.
  $dbase - The database object.

  Returns:
  The number of new log messages.

  Example Usage:
  > echo 'New Log Events: ', checkLog($player->id, $dbase);
 */

function checkLog($player, &$dbase)
{
    $result = $dbase->fetchRow('SELECT COUNT(`id`) AS `count` FROM `<ezrpg>player_log` WHERE `player`=? AND `status`=0', array( intval($player) ));
    return $result->count;
}

?>
