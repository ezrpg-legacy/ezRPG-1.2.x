<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_StatPoints
  Handles distribution of stat points.
*/
class Module_StatPoints extends Base_Module
{
    /*
      Function: start
      Begins the stat points distribution page.
    */
    public function start()
    {
        //Require login
        requireLogin();
		
        //If the form was submitted, process it in register().
        if (isset($_POST['stat']) && $this->player->stat_points > 0)
        {
            $this->spend();
        }
        else if ($this->player->stat_points > 0) //Make sure they have stat points
        {
            $this->loadView('statpoints.tpl');
        }
        else //No more stat points, redirect to player home page
        {
            $msg = 'You don\'t have any stat points left!';
            header('Location: index.php?msg=' . urlencode($msg));
        }
    }
    
    /*
      Function: spend
      This function removes stat points and increases stats and other player details.
	
      After the query is executed, the player is redirected back to the StatPoints module homepage.
    */
    private function spend()
    {
        $msg = '';
        switch($_POST['stat'])
        {
          case 'Strength':
              //Add to weight limit
              $query = $this->db->execute('UPDATE <ezrpg>players SET
				stat_points=stat_points-1,
				strength=strength+1
				WHERE id=?', array($this->player->id));
              $msg = 'You have increased your strength!';
              break;
          case 'Vitality':
              //Add to hp and max_hp
              $query = $this->db->execute('UPDATE <ezrpg>players SET
				stat_points=stat_points-1,
				vitality=vitality+1,
				hp=hp+5,
				max_hp=max_hp+5
				WHERE id=?', array($this->player->id));
              
              $msg = 'You have increased your vitality!';
              break;
          case 'Agility':
              $query = $this->db->execute('UPDATE <ezrpg>players SET
				stat_points=stat_points-1,
				agility=agility+1
				WHERE id=?', array($this->player->id));
              
              $msg = 'You have increased your agility!';
              break;
          case 'Dexterity':
              $query = $this->db->execute('UPDATE <ezrpg>players SET
				stat_points=stat_points-1,
				dexterity=dexterity+1
				WHERE id=?', array($this->player->id));
              
              $msg = 'You have increased your dexterity!';
              break;
          default:
              break;
        }
	
        //Player has just used up their last stat point
        if ($this->player->stat_points <= 1)
            header('Location: index.php?msg=' . urlencode($msg));
        else
            header('Location: index.php?mod=StatPoints&msg=' . urlencode($msg));
	
        exit;
    }
}
?>
