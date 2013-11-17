<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_EventLog
  The module is the player's event log, which keeps track of everything that happens to the user.
 */

class EventLog extends Base_Module
{
    /*
      Function: start()
      Grabs the log data, assigns it to the template and displays the page.
     */

    public function start()
    {
        //Require the user to be logged in
        requireLogin();

        if ( isset($_GET['act']) && $_GET['act'] == 'clear' )
        {
            $this->clear();
        }
        else
        {
            //Retrieve all log messages
            $query = $this->db->execute('SELECT `time`, `message`, `status` FROM `<ezrpg>player_log` WHERE `player`=? ORDER BY `time` DESC LIMIT 10', array( $this->player->id ));
            $logs = $this->db->fetchAll($query);

            //Update log message statuses to old/read (status value: 1)
            $this->db->execute('UPDATE `<ezrpg>player_log` SET `status`=1 WHERE `player`=?', array( $this->player->id ));

            $this->tpl->assign('logs', $logs);
            $this->loadView('log.tpl', 'Log');
        }
    }

    /*
      Function: clear
      Deletes all log entries belonging to the player.
     */

    private function clear()
    {
        $query = $this->db->execute('DELETE FROM `<ezrpg>player_log` WHERE `player`=?', array( $this->player->id ));

        $msg = 'You have cleared your event log!';
        $this->setMessage($msg);
        header('Location: index.php?mod=EventLog');
        exit;
    }

}

?>
