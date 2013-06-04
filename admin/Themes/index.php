<?php
defined('IN_EZRPG') or exit;
/*
  Class: Admin_Plugins
  Admin page for managing plugins and modules
*/
class Admin_Themes extends Base_Module
{
    /*
      Function: start
      Displays the list of modules/plugins.
    */
    public function start() {
  
        if(isset($_GET['act']) && isset($_GET['gid'])){
			switch ($_GET['act']) {
				case 'getTheme':
					$this->getThemePage($_GET['gid']);		
			}
		} elseif (isset($_POST['act'])) {
			switch ($_POST['act']){
				case 'save' :
					$this->save_settings();
			}
		}else {
		$this->list_settings();
		}
    }
	
	private function list_settings() {
		$query1 = $this->db->execute("select * from <ezrpg>themes WHERE name != 'admin'");
		$themes = $this->db->fetchAll($query1);
		$this->tpl->assign("groups", $themes);
		$this->loadView('themes.tpl');
	}
	
	private function getThemePage($id) {
		$query = $this->db->execute('select * from <ezrpg>themes where id = ' . $id  );
		$themes = $this->db->fetchAll($query);
		$this->tpl->assign('themes', $themes);
		$this->loadView('themes_page.tpl');
	}
	
	private function save_settings(){
		$settings = array();
		foreach ($_POST as $item => $val){
			if ($item != 'act' and $item != 'save'){
			if (strpos($item, 'sid') === 0){
			$settings[preg_replace('/sid/', '', $item)] = $val;
			}
			if (strpos($item, 'sgid') === 0){
			$settings[preg_replace('/sgid/', '', $item)] = $val;
			}
			}
		}
		foreach ($settings as $item => $val){
			$update= array();
			$update['value'] = $val;
			$this->db->update("<ezrpg>settings", $update, 'id='.$item);
		}
		$this->list_settings();
	}
}
