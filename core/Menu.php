<?php

namespace ezrpg\core;
defined('IN_EZRPG') or exit;

/*
  Class: Menu
  A class to handle the menu system
 */

class Menu
{
    /*
      Variable: $db
      Contains the database object.
     */

    protected $db;

    /*
      Variable: $tpl
      The smarty template object.
     */
    protected $tpl;

    /*
      Variable: $player
      The currently logged in player. Value is 0 if no user is logged in.
     */
    protected $player;

    /*
      Variable: $menu
      An array of all menus.
     */
    protected $menu;

    protected $container;

    /*
      Function: __construct
      The constructor takes in database, template and player variables to pass onto any hook functions called.

      Parameters:
      $db - An instance of the database class.
      $tpl - A smarty object.
      $player - A player result set from the database, or 0 if not logged in.
     */

    public function __construct($container)
    {
        $this->db = $container['db'];
        $this->tpl = $container['tpl'];
        $this->player = $container['player'];
        $this->container = $container;
        $this->menu = $this->loadCache();
    }

    public function loadCache()
    {
        $query = 'SELECT * FROM `<ezrpg>menu` WHERE active = 1 ORDER BY `pos`';
        $cache_file = md5($query);

        if (file_exists(CACHE_DIR . $cache_file)) {
            if (filemtime(CACHE_DIR . $cache_file) > time() - 60 * 60 * 24) {
                $array = unserialize(file_get_contents(CACHE_DIR . $cache_file));
                if ($this->container['config']['debug']['debug_mode'] == 1) {
                    echo 'Loaded Menu Cache! <br />';
                }
            } else {
                unlink(CACHE_DIR . $cache_file);
                $this->loadCache();

                return;
            }
        } else {
            $query1 = $this->db->execute($query);
            $array = $this->db->fetchAll($query1);
            file_put_contents(CACHE_DIR . $cache_file, serialize($array));
            if ($this->container['config']['debug']['debug_mode'] == 1) {
                echo 'Created Menu Cache! <br />';
            }
        }

        return $array;
    }

    /*
      Function: add_menu
      Adds menu to database.

      Returns:
      Inserted ID of menu added

      Parameters:
      $pid (Optional) Represents the Parent ID of the Menu this Menu belongs to.
      $name (Mandatory) Sets the 1word Name of the Menu.
      $title (Mandatory) Sets the User-Friendly Name of the menu.
      $uri (Optional) Sets the uri that the menu will go to.
      $pos (Optional) Sets position menu appears in order of other menus.

      Example Usage:
      $bid = add_menu('','Bank','Empire Bank', '', 'index.php?mod=Bank', 0, 3);
      $add_menu ($bid, 'Deposit', 'Deposit Money', '', 'index.php?mod=Bank&act=Deposit');
     */

    function add_menu($pid = 0, $name, $title = '', $alttitle = null, $uri = '', $pos = 0, $mod_id = 0)
    {
        if (is_numeric($pid)) {
            $item['parent_id'] = $pid;
        } elseif($pid == '') {
            $item['parent_id'] = 0;
        }else{
            $item['parent_id'] = $this->get_menu_id_by_name($pid);
        }
        $item['AltTitle'] = $alttitle;
        $item['name'] = $name;
        $item['title'] = $title;
        $item['uri'] = $uri;
        $item['module_id'] = $mod_id;
        $item['active'] = '0';
        if ($pos == '') {
            if ($item['parent_id'] == null) {
                $pos = '0';
            } else {
                $pos = $this->get_next_pos($item['parent_id']);
            }
        }
        $item['pos'] = $pos;

        return $this->db->insert("<ezrpg>menu", $item);
    }

    /*
      Function: edit_menu
      Applies new values to menu
     */

    function edit_menu($mid = '', $pid = 0, $name, $title = '', $alttitle = null, $uri = '', $pos = '', $active = '', $modid = 0)
    {
        if (is_numeric($pid)) {
            $item['parent_id'] = $pid;
        } else {
            $item['parent_id'] = $this->get_menu_id_by_name($pid);
        }
        $item['AltTitle'] = $alttitle;
        $item['name'] = $name;
        $item['title'] = $title;
        $item['uri'] = $uri;
        if ($pos == '') {
            if ($item['parent_id'] == 0) {
                $pos = '0';
            } else {
                $pos = $this->get_next_pos($item['parent_id']);
            }
        }
        $item['pos'] = $pos;
        $item['active'] = $active;
        $item['module_id'] = $modid;

        return $this->db->update("<ezrpg>menu", $item, "id = " . $mid);
    }

    /*
      Function: delete_menu
      Deletes a menu to database.

      Parameters:
      $id (Optional
      $pid (Optional) Represents the Parent ID of the Menu this Menu belongs to.

      Example Usage:
      $this->menu->delete_menu(6); //Deletes MenuID #6
      $this->menu->delete_menu(6,2); //Deletes MenuID #6 ONLY IF it's parent is MenuID #2
      or
      $this->menu->delete_menu(get_menu_id_by_name('Bank'), get_menu_id_by_name('City')); //Deletes Menu named Bank ONLY IF it's parent is Menu named City
      $this->menu->delete_menu(get_menu_id_by_name('Bank')); Deletes Menu named "Bank"
     */

    function delete_menu($id = null, $pid = null)
    {
        if (isset($id) && isset($pid)) {
            return $this->db->execute('DELETE FROM `<ezrpg>menu` WHERE parent_id=' . $pid . ' AND id=' . $id);
        } else {
            if (isset($id)) {
                return $this->db->execute('DELETE FROM `<ezrpg>menu` WHERE id=' . $id);
            } elseif (isset($pid)) {
                return $this->db->execute('DELETE FROM `<ezrpg>menu` WHERE id=' . $id);
            } else {
                return false;
            }
        }
    }

    /*
      Function: get_menus
      Preforms the inital grab of the Parent menus.

      Parameters:
      $parent (Optional): Sets the ParentID, if Null then it's a Group, if a string then finds ID
      $begin (BOOLEAN Optional) Determines if we should auto-include a Home menu item.
      $endings (BOOLEAN Optional) Determines if we should auto-include a Logout menu item.
      $menu (Optional) Initializes the $menu array variable
     */

    function get_menus($parent = null, $args = 0, $begin = true, $endings = true, $title = "", $customtag = "", $showchildren = true
    ) 
    {
        if ($args != 0) {
            (isset($args['begin']) ? $begin = $args['begin'] : '');
            (isset($args['endings']) ? $endings = $args['endings'] : '');
            (isset($args['title']) ? $title = $args['title'] : '');
            (isset($args['customtag']) ? $customtag = $args['customtag'] : '');
            (isset($args['showchildren']) ? $showchildren = $args['showchildren'] : '');
        }
        $result = '';
        $menu = $this->menu;
        $menuarray = array();
        if (LOGGED_IN != "TRUE") {
            $menuarray['Home'] = 'index.php';
            $menuarray['Register'] = 'index.php?mod=Register';
            $this->tpl->assign('TOP_MENU_LOGGEDOUT', $menuarray);
        } else {
            foreach ($menu as $item => $ival) {
                $menuarray[(defined('IN_ADMIN') ? "Admin" : "Home")] = 'index.php';
                if ($ival->active == 1) {
                    $this->tpl->assign('Menu' . $ival->name, $parent . " : " . $ival->parent_id);
                    if ($parent != null || $ival->parent_id != 0) {
                        if (!is_numeric($parent)) {
                            if ($ival->name == $parent) {
                                $result = $this->get_children($ival->id, $title, $showchildren);
                                foreach ($result as $mitem => $mval) {
                                    $menuarray[$mitem] = $mval;
                                }
                                $this->tpl->assign('MENU_' . (($customtag == "") ? $ival->name : $customtag),
                                    $menuarray);
                                unset($menuarray);
                            }
                        } else {
                            $result = $this->get_children($ival->id, $title, $showchildren);
                            foreach ($result as $mitem => $mval) {
                                $menuarray[$mitem] = $mval;
                            }
                            $this->tpl->assign('MENU_' . (($customtag == "") ? $ival->name : $customtag), $menuarray);
                            unset($menuarray);
                        }
                    } else {
                        if ($ival->parent_id == 0) { //it's a group
                            $result = $this->get_children($ival->id, $title, $showchildren);

                            foreach ($result as $mitem => $mval) {
                                $menuarray[$mitem] = $mval;
                            }
                            if (defined('IN_ADMIN')) {
                                $menuarray['To Game'] = '../index.php';
                            }
                            if ($this->player) {
                                if ($this->player->rank > 5) {
                                    if (defined('IN_ADMIN')) {
                                        $menuarray['To Game'] = '../index.php';
                                        $menuarray['Logout'] = '../index.php?mod=Logout';
                                    } else {
                                        $menuarray['Admin'] = 'admin/';
                                        $menuarray['Logout'] = 'index.php?mod=Logout';
                                    }
                                } else {
                                    $menuarray['Logout'] = 'index.php?mod=Logout';
                                }
                            }
                            $this->tpl->assign('TOP_MENU_' . (($customtag != 0) ? $customtag : $ival->name),
                                $menuarray);
                            unset($menuarray);
                        }
                    }
                }
            }
        }
    }

    /*
      Function: Get_Children
      Gets the submenus of a Menu's Parent_ID

      Parameters:
      $parent (Optional): Sets the ParentID, if 0 then it's a Group
      $menu (Optional): Initializes the use of the $menu array variable
      $title (Optional): Determines if you want to use Title(0), Alt Title (1)
      $showchildren (Optional): Determines if we're displaying any children menus.
     */

    function get_children($parent = 0, $title = 0, $showchildren = true, $menu = 0)
    {
        $result = "";
        if ($menu == 0) {
            $menu = $this->menu;
        }
        $menuarray = array();
        foreach ($menu as $item => $ival) {
            if ($ival->active == 1) {
                if (!is_numeric($parent)) {
                    if ($ival->name == $parent) {
                        $this->get_children($ival->id);
                        break;
                    }
                } else {
                    if ($ival->parent_id == $parent) {
                        $menuarray[($title == 0 ? $ival->title : $this->get_title($ival))] = $ival->uri;
                    }
                }
            }
        }

        return $menuarray;
    }

    /*
      Function: get_title()

      Returns:
      Menu's Title or Altnerative Title depending on if an AltTitle is set

      Parameters:
      $menu (Manadatory): Represents current row in Menu DB
     */

    function get_title($menu)
    {
        if (!is_null($menu->AltTitle)) {
            return $menu->AltTitle;
        } else {
            return $menu->title;
        }
    }

    /*
      Function: has_children
      BOOLEAN returns T/F if is a Parent Element

      Parameters:
      $parent (Optional): Sets the ParentID, if Null then it's a Group
      $menu (Optional): Initializes the use of the $menu array variable
     */

    function has_children($parent = null, $menu = 0)
    {
        if ($menu == 0) {
            $menu = $this->menu;
        }
        foreach ($menu as $item => $ival) {
            if ($ival->parent_id == $parent) {
                return true;
            }
        }

        return false;
    }

    /*
      Function: has_children
      BOOLEAN returns T/F if is a Parent Element

      Parameters:
      $parent (Optional): Sets the ParentID, if Null then it's a Group
      $menu (Optional): Initializes the use of the $menu array variable
     */

    function get_menu_id_by_name($pid)
    {
        foreach ($this->menu as $item => $ival) {
            if ($ival->name == $pid) {
                return $ival->id;
            }
        }
    }

    function get_module_name_by_id($mid){
        $query = $this->db->execute('SELECT `title`, `id` FROM <ezrpg>plugins WHERE <ezrpg>plugins.id = ' . $mid);
        return $this->db->fetch($query)->title;
    }
    /*
      Function: countmenus
      Returns the number of menus the group has

      Parameters:
      $pid (Optional): Sets the ParentID, if Null then it's a Group
     */

    function countmenus($pid = "")
    {
        $result = 0;
        foreach ($this->menu as $item => $ival) {
            if ($ival->parent_id == $pid) {
                $result++;
            }
        }

        return $result;
    }

    /*
      Function: get_next_pos
      Returns the next incremented position

      Parameters:
      $pid (Optional): Initializes the parentid variable
      $pos (Optional): Initializes the use of the $pos variable
     */

    function get_next_pos($pid = "", $pos = "")
    {
        $result = 0;
        if ($pos == "") {
            $pos = 0;
        }
        foreach ($this->menu as $item => $ival) {
            if ($ival->parent_id == $pid) {
                if ($ival->pos == $pos) {
                    $result = $this->get_next_pos($pid, ++$pos);
                } else {
                    $result = $pos;
                }
            }
        }

        return $result;
    }

    function isMenu($name)
    {
            $query = $this->db->execute('SELECT id, name FROM `<ezrpg>menu`  ORDER BY `id`');
            $array = $this->db->fetchAll($query);
        try {
            foreach ($array as $item => $ival) {
                if ($ival->name == $name) {
                    return true;
                } elseif ($ival->id == $name) {
                    return true;
                }
            }
        }catch(\Exception $ex){
            throw new EzException($ex->getMessage());
        }

        return false;
    }

}

?>
