<?php

namespace ezrpg\Modules;
use \ezrpg\core\Base_Module;

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_Login
  This module handles user authentication.
 */

class Module_Login extends Base_Module
{
    public function __construct($container)
    {
        parent::__construct($container);
    }

    /*
      Function: start
      Checks player details to login the player.

      If successful, a new session is generated and the user is redirected to the game.

      On failure, session data is cleared and the user is redirected back to the login page.

     */

    public function start()
    {
        global $hooks;
        $error = 0;
        if (empty($_POST['username']) || empty($_POST['password'])) {
            $errors[] = 'Please enter your username and password!';
            $error = 1;
        } else {
            $player = $this->validate();
            if ($player === false) {
                $errors[] = 'Please check your username/password!';
                $error = 1;
            }
        }
        if ($error == 0) {

            $check = password_verify($_POST['password'], $player->password);

            if ($check != true) {
                $errors[] = 'Please check your username/password!';
                $error = 1;
            }

            if ($error == 0) {

                //Run login hook
                $player = $hooks->run_hooks('login', $player);

                $query = $this->db->execute('UPDATE `<ezrpg>players_meta` SET `last_login`=? WHERE `pid`=?',
                    array(time(), $player->id));
                $_SESSION['userid'] = $player->id;
                $_SESSION['hash'] = generateSignature();
                $_SESSION['last_active'] = time();

                $hooks->run_hooks('login_after', $player);
                if (isset($_SESSION['last_page'])) {
                    header('Location: ' . $_SESSION['last_page']);
                    exit;
                } else {
                    header('Location: index.php');
                    exit;
                }
            }
        } else {
            $hooks->run_hooks('3rdparty_login', $_POST);
        }

        //If we made it this far, then there's an issue

        session_unset();

        foreach ($errors as $errmsg) {
            //Sets message(s) to FAIL
            $this->setMessage($errmsg, 'FAIL');
        }

        header('Location: index.php');
        exit;
    }

    private function validate()
    {
        $query = $this->db->execute('SELECT `id`, `username`, `password` FROM `<ezrpg>players` WHERE `username`=?',
            array($_POST['username']));

        if ($this->db->numRows($query) == 0) {
            return false;
        } else {
            $player = $this->db->fetch($query);

            $check = $this->container['hooks']->run_hooks('login_funcs', array('post'=>$_POST['password'], 'player' =>$player));
            
            if ($check != true) {
                return false;
            }

            return $player;
        }
    }

    public function login_form()
    {
        $this->loadView('Login_form.tpl', 'Login');
    }
}

?>
