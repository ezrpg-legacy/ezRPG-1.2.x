<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_Login
  This module handles user authentication.
 */

class Login extends Base_Module
{
	public function __construct(&$app)
	{
		$this->error=0;
		$this->errors[] ='';
	}
    /*
      Function: start
      Checks player details to login the player.

      If successful, a new session is generated and the user is redirected to the game.

      On failure, session data is cleared and the user is redirected back to the login page.

    */

    public function start()
    {
		$hooks = $this->app['hooks'];
        if ( empty($_POST['username']) || empty($_POST['password']) )
        {
            $this->errors[] = 'Please enter your username and password!';
            $this->error = 1;
        }
        else
        {
            $player = $this->validate($_POST['3rdparty']);
            if ( $player === false )
            {
                $this->errors[] = 'Please check your username/password!';
                $this->error = 1;
            }
        }
        if ( $this->error == 0 )
        {
            $this->ezrpg_pass_match($player);

            if ( $error == 0 )
            {

                //Run login hook
                $player = $hooks->run_hooks('login', $player);

                $query = $this->db->execute('UPDATE `<ezrpg>players_meta` SET `last_login`=? WHERE `pid`=?', array( time(), $player->id ));
                $_SESSION['userid'] = $player->id;
                $_SESSION['hash'] = generateSignature();
                $_SESSION['last_active'] = time();

                $hooks->run_hooks('login_after', $player);
                if ( isset($_SESSION['last_page']) )
                {
                    header('Location: ' . $_SESSION['last_page']);
                    exit;
                }
                else
                {
                    header('Location: index.php');
                    exit;
                }
            }
		}
        //If we made it this far, then there's an issue

        session_unset();

        foreach ( $this->errors as $errmsg )
        {
            //Sets message(s) to FAIL
            $this->setMessage($errmsg, 'FAIL');
        }

        header('Location: index.php');
        exit;
    }

    private function validate($logins)
    {
		if($logins = "ezrpg")
		{
			$query = $this->db->execute('SELECT `id`, `username`, `password`, `secret_key`, `pass_method` FROM `<ezrpg>players` WHERE `username`=?', array( $_POST['username'] ));

			if ( $this->db->numRows($query) == 0 )
			{
				return $this->check_3rdparty();
			}
			else
			{
				$player = $this->db->fetch($query);
				
				// We have different authentication methods at our disposal.
				$pass_meth = $this->settings->setting['general']['pass_encryption']['value']['value'];
				$check = checkPassword($player->secret_key, $_POST['password'], $player->password, ($player->pass_method == $pass_meth ? '0' : $player->pass_method));
				if ( $check != true )
				{
					return false;
				}

				return $player;
			}
		}else{
			return $app['hooks']->run_hooks('3rdpartylogins_'.$logins, $_POST);
		}
    }
	
	public function ezrpg_pass_match($player)
	{
		$pass_method = $this->settings->setting['general']['pass_encryption']['value']['value'];
		$check = checkPassword($player->secret_key, $_POST['password'], $player->password);

		if ( $check != TRUE )
		{
			if ( $player->pass_method != $pass_method )
			{
				$check = checkPassword($player->secret_key, $_POST['password'], $player->password, $player->pass_method);
				if ( $check != TRUE )
				{
					$this->errors[] = 'Password Set as Old Method!';
					$this->error = 1;
				}
				else
				{
					$new_password = createPassword($player->secret_key, $_POST['password']);
					$this->db->execute('UPDATE `<ezrpg>players` SET `password`=?, `pass_method`=? WHERE `id`=?', array( $new_password, $pass_method, $player->id ));
					killPlayerCache($player->id);
				}
			}
		}
	}
	
	public function login_form()
	{
		$this->loadView('Login_form.tpl', 'Login');
	}
}

?>
