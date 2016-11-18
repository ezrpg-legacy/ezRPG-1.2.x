<?php

namespace ezrpg\Modules\Themes\Admin;

use \ezrpg\core\Base_Module;

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

    public function start()
    {

        if (isset($_GET['act']) && isset($_GET['gid'])) {
            switch ($_GET['act']) {
                case 'getTheme':
                    $this->getThemePage($_GET['gid']);
            }
        } elseif (isset($_POST['act'])) {
            switch ($_POST['act']) {
                case 'save' :
                    $this->save_themes();
            }
        } else {
            $this->list_themes();
        }
    }

    private function list_themes()
    {
        $query1 = $this->db->execute("select * from <ezrpg>themes WHERE name != 'admin' AND type = 0");
        $themes = $this->db->fetchAll($query1);
        $this->tpl->assign("groups", $themes);
        $this->loadView('themes.tpl');
    }

    private function getThemePage($id)
    {
        $query = $this->db->execute('select * from <ezrpg>themes where id = ' . $id);
        $themes = $this->db->fetchAll($query);
        $this->tpl->assign('themes', $themes);
        $this->loadView('themes_page.tpl');
    }

    private function save_themes()
    {
        $update = array();
        $update['enabled'] = 0;
        $this->db->update("<ezrpg>themes", $update, 'enabled=1');
        $update['enabled'] = 1;
        $this->db->update("<ezrpg>themes", $update, 'id=' . $_POST['themes']);
        $this->list_themes();
    }

}
