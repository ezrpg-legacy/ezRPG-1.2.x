<?php

namespace ezrpg\Modules\Settings\Admin;

use \ezrpg\core\Base_Module;

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
        $this->rebuildConfigCache();
        if (isset($_GET['act']) && isset($_GET['group'])) {
            switch ($_GET['act']) {
                case 'getGroup':
                    $this->getGroupPage($_GET['group']);
            }
        } elseif (isset($_POST['act'])) {
            switch ($_POST['act']) {
                case 'save' :
                    $this->save_settings();
            }
        } else {
            $this->list_settings();
        }
    }

    private function list_settings()
    {
        $config = unserialize(file_get_contents(CUR_DIR . '/config.php'));
        $this->tpl->assign("groups", $config);
        $this->loadView('settings.tpl');
    }

    private function getGroupPage($id)
    {
        //$this->tpl->assign('allSettings', $allSettings);
        $config = unserialize(file_get_contents(CUR_DIR . '/config.php'));
        $this->tpl->assign('group', $id);
        //die(print("<pre>".print_r($config[$id],true)."</pre>"));
        $this->tpl->assign('settings', $config[$id]);
        $this->loadView('settings_page.tpl');
    }

    private function save_settings()
    {
        $settings = array();
        foreach ($_POST as $item => $val) {
            if ($item != 'act' and $item != 'save') {
                $setting = explode("_",$item);
                die(var_dump($this->container['config'][$setting[0]][$setting[1]]));
            }
        }
        foreach ($settings as $item => $val) {

        }

        $this->list_settings();
    }

    private function rebuildConfigCache(){
        if (!array_key_exists('config', $this->container)) {
            $config_paths = [
                'core/config/*.php',
                'modules/*/config.php',
                'config/*.php',
            ];
            $configLoader = new \ezrpg\core\config\ConfigLoader();
            $config = $configLoader->loadConfigFromPaths($config_paths);
            $serialized = serialize($config);
            file_put_contents(CUR_DIR . "/config.php", $serialized);
        }

    }
}
