<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_Example
  An example of a module. Use the <Module_Skeleton> module as a starting framework for writing new modules.

  For the most basic module possible, see the Index module.
*/
class Module_Example extends Base_Module
{
    /*
      Function: start
      Displays a page using another class method.
      
      See Also:
      - <render>
      - <boo>
    */
    public function start()
    {
        switch($_GET['act'])
        {
          case 'boo':
              $this->boo();
              break;
          default:
              $this->render();
              break;
        }
    }
	
    /*
      Function: render
      An example of using the passed variables of $tpl and $player, then renders index.tpl.
    */
    private function render()
    {
        $this->tpl->assign('player', $this->player);
        $this->loadView('index.tpl');
    }
	
    /*
      Function: boo
      This function/method is called when the user accessed the URL index.php?mod=Example&act=boo
    */
    private function boo()
    {
        //Do something here!
		
        //Such as query the database for the number of players!
        $c = $this->db->fetchRow('SELECT COUNT(`id`) AS `count` FROM `<ezrpg>players`');
		
        //Divide it by two!
        $c = intval($c / 2);
		
        //Then assign that variable to be displayed in a template!
        $this->tpl->assign('half_players', $c);
		
        //Display a template
        $this->loadView('index.tpl');
    }
}
?>