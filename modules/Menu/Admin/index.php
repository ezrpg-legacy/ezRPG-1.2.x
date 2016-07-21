<?php

namespace ezRPG\Modules\Menu\Admin;

use \ezRPG\lib\Base_Module;

defined('IN_EZRPG') or exit;

/*
  Class: Admin_Plugins
  Admin page for managing plugins and modules
 */

class Admin_Menu extends Base_Module
{
    /*
      Function: start
      Displays the list of modules/plugins.
     */

    public function start()
    {

        if (isset($_GET['act'])) {
            switch ($_GET['act']) {
                case 'install' :
                    $this->install_manager(); //Completed:Install Manager
                    break;
                case 'list' :
                    $this->list_menus(); //Completed:Lists Menus
                    break;
                case 'add' :
                    $this->add_menus();
                    break; //Completed:Add Menus.
                case 'remove' :
                    $this->remove_menus();
                    break; //Completed:Remove Menus.
                case 'edit' :
                    $this->edit_menus();
                    break; //Completed:Edit Menus.
            }
        } elseif (isset($_POST['act'])) {
            switch ($_POST['act']) {
                case 'add' :
                    $this->add_menus();
                    break;
                case 'edit' :
                    $this->edit_menus();
                    break;
            }
        } else {
            $this->list_menus();
        }
    }

    private function list_menus()
    {
        $query = $this->db->execute('select * from <ezrpg>menu where parent_id <> 0');
        $menu = $this->db->fetchAll($query);
        $query1 = $this->db->execute('select * from <ezrpg>menu where parent_id = 0');
        $groups = $this->db->fetchAll($query1);
        $query2 = $this->db->execute('select `title`, `id` from <ezrpg>plugins');
        $plugins = $this->db->fetchAll($query2);
        $this->tpl->assign("groups", $groups);
        $this->tpl->assign("menus", $menu);
        $this->tpl->assign('plugins', $plugins);
        $this->loadView('menus.tpl');
    }

    private function remove_menus()
    {
        if (isset($_GET['mid']) && !isset($_GET['confirm'])) {
            if (!$this->menu->has_children($_GET['mid'])) {
                $query = $this->db->execute('select id, title from <ezrpg>menu where id = ' . $_GET['mid']);
                $menu = $this->db->fetchAll($query);
                foreach ($menu as $item => $key) {
                    $this->tpl->assign('menuname', $key->title);
                    $this->tpl->assign('menuid', $key->id);
                }
                $this->tpl->assign('error', false);
                $this->tpl->assign('page', 'delete');
                $this->loadView('menus-manage.tpl');
            } else {
                $this->tpl->assign('error', true);
                $this->tpl->assign('page', 'delete');
                $this->loadView('menus-manage.tpl');
            }
        }
        if (isset($_GET['confirm']) && isset($_GET['mid'])) {
            if ($_GET['confirm'] == 1) {
                $this->menu->delete_menu($_GET['mid']);
                $this->list_menus();
                exit;
            }
        }
    }

    private function add_menus()
    {
        $error = 0;
        if (!isset($_POST['submit'])) {
            $query = $this->db->execute('select * from <ezrpg>menu ORDER By `parent_id` DESC');
            $menu = $this->db->fetchAll($query);
            $query1 = $this->db->execute('select * from <ezrpg>menu where parent_id = 0');
            $groups = $this->db->fetchAll($query1);
            $this->tpl->assign("groups", $groups);
            $this->tpl->assign('menus', $menu);
            $this->tpl->assign('mname', 'CHANGEME');
            $this->tpl->assign('mpid', '0');
            $this->tpl->assign('mtitle', '');
            $this->tpl->assign('malt', '');
            $this->tpl->assign('muri', 'index.php?mod=CHANGEME');
            $this->tpl->assign('mpos', '0');
            $this->tpl->assign('modid', '0');
            $this->tpl->assign('page', 'add');
            $this->tpl->assign('error', $error);
            $this->loadView('menus-manage.tpl');
        } else {
            $insert = Array();
            $insert['uri'] = preg_replace("[^-A-Za-z0-9+&@#/%?=~_|!:,.;\(\)]", "", $_POST['muri']);
            if (!isClean($_POST['mname'])) {
                $error = 1;
                $errmsg = "Name is not clean";
            }
            if (!isClean($_POST['mtitle']) && isset($_POST['mtitle'])) {
                $error = 2;
                $errmsg = "Title is not clean";
            }
            if (!isClean($_POST['malt'])) {
                $error = 3;
                $errmsg = "Alternate Title is not clean";
            }
            if (!isClean($_POST['mpos']) && isset($_POST['mpos'])) {
                $error = 4;
                $errmsg = "Position ID is not clean";
            }else{
                $_POST['malt'] = '';
            }
            if (!isClean($_POST['mpid']) && isset($_POST['mpid'])) {
                $error = 5;
                $errmsg = "Parent ID is not clean";
            }
            if (!isClean($_POST['modid'])){
                $error = 6;
                $errmsg = "Module ID is not clean";
            }
            if ($error == 0) {
                //$pid = 0, $name, $title = '', $alttitle = null, $uri = '', $pos = 0, $mod_id = 0
                $mid = $this->menu->add_menu($_POST['mpid'], $_POST['mname'], $_POST['mtitle'], $_POST['malt'],
                    $_POST['muri'], $_POST['mpos'], $_POST['modid']);
                $this->tpl->assign('GET_MSG', 'You must activate the menu before it becomes accessible!');
                $msg = 'You must first activate this plugin before it can be used!';
                $this->setMessage($msg);
                killMenuCache();
                header('Location: index.php?mod=Menu');
                exit;
            } else {
                $query = $this->db->execute('select * from <ezrpg>menu');
                $menu = $this->db->fetchAll($query);
                $this->tpl->assign('menus', $menu);
                $this->tpl->assign('mname', $_POST['mname']);
                $this->tpl->assign('mpid', $_POST['mpid']);
                $this->tpl->assign('mtitle', $_POST['mtitle']);
                $this->tpl->assign('malt', $_POST['malt']);
                $this->tpl->assign('muri', preg_replace("[^-A-Za-z0-9+&@#/%?=~_|!:,.;\(\)]", "", $_POST['muri']));
                $this->tpl->assign('mpos', $_POST['mpos']);
                $this->tpl->assign('page', 'add');
                $this->tpl->assign('errormsg', $errmsg);
                $this->tpl->assign('error', $error);
                $this->loadView('menus-manage.tpl');
            }
        }
    }

    private function edit_menus()
    {
        if ($this->container['menu']->isMenu($_REQUEST['mid'])) {
            if (!isset($_POST['submit'])) {
                $query = $this->db->execute('select * from <ezrpg>menu where id = ' . $_GET['mid']);
                $menu =  $this->db->fetch($query);
                $this->tpl->assign('mitem', $menu);
                $query1 = $this->db->execute('select * from <ezrpg>menu');
                $menu = $this->db->fetchAll($query1);
                $query2 = $this->db->execute('select `title`, `id` from <ezrpg>plugins');
                $plugins = $this->db->fetchAll($query2);
                $this->tpl->assign('menubox', $menu);
                $this->tpl->assign('plugins', $plugins);
                $this->tpl->assign('errormsg', "");
                $this->tpl->assign('error', 0);
                $this->tpl->assign('page', 'edit');
                $this->loadView('menus-manage.tpl');
            } else {
                $error = '0';
                $errmsg = "";
                if (!isClean($_POST['mname'])) {
                    $error = '1';
                    $errmsg = "The MenuName is not clean";
                }
                if (!isClean($_POST['mtitle']) && isset($_POST['mname'])) {
                    $error = '2';
                    $errmsg = "The Title is not clean";
                }
                if (!isClean($_POST['malt'])) {

                    if ($_POST['malt'] == null) {
                        $_POST['malt'] = '';
                    } else {
                        $error = '3';
                        $errmsg = "The Alt Title is not clean";
                    }
                }
                if (!isClean($_POST['mpos'])) {
                    $error = '4';
                    $errmsg = "The Position entered is not clean";
                }
                if (!isClean($_POST['mpid'])) {
                    $error = '5';
                    $errmsg = "The ParentID is not clean";
                }

                if (!isClean($_POST['modid'])){
                    $error = '6';
                    $errmsg = "The Module ID is not clean";
                }

                if ($error == '0') {
                    //edit_menu($mid = '', $pid = 0, $name, $title = '', $alttitle = null, $uri = '', $pos = '', $active = '')
                    $this->container['menu']->edit_menu($_POST['mid'], $_POST['mpid'], $_POST['mname'], $_POST['mtitle'],
                        $_POST['malt'], $_POST['muri'], $_POST['mpos'], $_POST['mactive'], $_POST['modid']);
                    killMenuCache();
                    $this->setMessage('Successfully updated menu');
                    header('Location: index.php?mod=Menu');
                    exit;
                } else {
                    $query = $this->db->execute('select * from <ezrpg>menu where id = ' . $_POST['mid']);
                    $menu =  $this->db->fetch($query);
                    $this->tpl->assign('mitem', $menu);
                    $query1 = $this->db->execute('select * from <ezrpg>menu');
                    $menu = $this->db->fetchAll($query1);
                    $query2 = $this->db->execute('select `title`, `id` from <ezrpg>plugins');
                    $plugins = $this->db->fetchAll($query2);
                    $this->tpl->assign('menubox', $menu);
                    $this->tpl->assign('plugins', $plugins);
                    $this->tpl->assign('errormsg', $errmsg);
                    $this->tpl->assign('error', $error);
                    $this->tpl->assign('page', 'edit');
                    $this->loadView('menus-manage.tpl');
                }
            }
        } else {
            $this->tpl->assign('errormsg', "The selected id was incorrect!");
            $this->tpl->assign('error', 1);
            $this->tpl->assign('page', 'edit');
            $this->list_menus();
        }
    }

}
