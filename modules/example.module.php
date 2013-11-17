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
		  case 'install':
			  $this->install_example();
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
        $b = $this->db->fetchRow('SELECT COUNT(`id`) AS `count` FROM `<ezrpg>players`');
		
        //Divide it by two!
        $c = intval($b / 2);
		
        //Then assign that variable to be displayed in a template!
        $this->tpl->assign('half_players', $c);
		
		//Grab a setting from Settings DB and create a smarty variable for it.
		if ($this->settings->get_settings_by_cat_name('example') === false) {
			$this->install_example();
			break;
		} else {
			$this->tpl->assign('EXAMPLESETTINGS', $this->settings->get_settings_by_cat_name('example'));
		}
		
        //Display a template
        $this->loadView('index.tpl');
    }
	
	private function install_example()
	{
		// Create a Settings Group for your Module
		$insert = array();
		$insert['name'] = 'example';
		$insert['title'] = 'Example Module';
		$insert['description'] = 'Some great settings for your module';
		$example_id = $this->db->insert('<ezrpg>settings', $insert);
		unset($insert);
		
		// Add some settings
		$insert['name'] = 'test_text_setting';
		$insert['title'] = 'Test Text Setting';
		$insert['description'] = 'Creates an input box to accept data typed from the user';
		$insert['optionscode'] = 'text';
		$insert['value'] = 'Default Text';
		$insert['gid'] = $example_id;
		$this->db->insert('<ezrpg>settings', $insert);
		unset($insert);
		
		
		// Add some more settings
		$insert['name'] = 'test_select_setting';
		$insert['title'] = 'Test Select Setting';
		$insert['description'] = 'Creates an dropdown box to accept a choice from the user';
		$insert['optionscode'] = 'select';
		$insert['gid'] = $example_id;
		$select_group = $this->db->insert('<ezrpg>settings', $insert);
		unset($insert);
		
		$insert['name'] = 'test_select_setting_option1';
		$insert['title'] = 'Optional Title for Option1';
		$insert['optionscode'] = 'option';
		$insert['value'] = 'Value1';
		$insert['gid'] = $select_group;
		$select_value = $this->db->insert('<ezrpg>settings', $insert);
		unset($insert);
		
		$insert['name'] = 'test_select_setting_option2';
		$insert['optionscode'] = 'option';
		$insert['value'] = 'Value2';
		$insert['gid'] = $select_group;
		$this->db->insert('<ezrpg>settings', $insert);
		unset($insert);
		
		$insert['value'] = $select_value;
		$this->db->update('<ezrpg>settings', $insert, 'ID = '. $example_id); 
		
		//Complete. Load a view
		$this->loadView('index.tpl', 'Example');
	}
}
?>