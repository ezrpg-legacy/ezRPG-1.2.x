<?php

defined('IN_EZRPG') or exit;
/*
  Class: Admin_Plugins
  Admin page for managing plugins and modules
 */

class Admin_Settings extends Base_Module
{
    /*
      Function: start
      Displays the list of modules/plugins.
     */

    public function start()
    {

        if ( isset($_GET['act']) && isset($_GET['gid']) )
        {
            switch ( $_GET['act'] )
            {
                case 'getGroup':
                    $this->getGroupPage($_GET['gid']);
            }
        }
        elseif ( isset($_POST['act']) )
        {
            switch ( $_POST['act'] )
            {
                case 'save' :
                    $this->save_settings();
            }
        }
        else
        {
            $this->list_settings();
        }
    }

    private function list_settings()
    {
        $query1 = $this->db->execute('select * from <ezrpg>settings where gid = 0 ORDER BY disporder');
        $groups = $this->db->fetchAll($query1);
        $this->tpl->assign("groups", $groups);
        $this->loadView('settings.tpl');
    }

    private function getGroupPage($id)
    {
        $query1 = $this->db->execute('select title from <ezrpg>settings where id = ' . $id);
        $this->tpl->assign('GROUP', $this->db->fetch($query1)->title);
        $query2 = $this->db->execute('select * from <ezrpg>settings where gid = ' . $id . ' ORDER BY disporder');
        $settings = $this->db->fetchAll($query2);
        $query3 = $this->db->execute('select * from <ezrpg>settings');
        $allSettings = $this->db->fetchAll($query3);
        $this->tpl->assign('allSettings', $allSettings);
        $this->tpl->assign('settings', $settings);
        $this->loadView('settings_page.tpl');
    }

    private function save_settings()
    {
        $settings = array( );
        foreach ( $_POST as $item => $val )
        {
            if ( $item != 'act' and $item != 'save' )
            {
                if ( strpos($item, 'sid') === 0 )
                {
                    $settings[preg_replace('/sid/', '', $item)] = $val;
                }
                if ( strpos($item, 'sgid') === 0 )
                {
                    $settings[preg_replace('/sgid/', '', $item)] = $val;
                }
            }
        }
        foreach ( $settings as $item => $val )
        {
            $update = array( );
            $update['value'] = $val;
            $this->db->update("<ezrpg>settings", $update, 'id=' . $item);
        }
        killSettingsCache();
        $this->list_settings();
    }

}
