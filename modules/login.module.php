<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_Login
  This module handles user authentication.
 */

class Login extends Base_Module
{
    /*
      Function: start
      Checks player details to login the player.

      If successful, a new session is generated and the user is redirected to the game.

      On failure, session data is cleared and the user is redirected back to the login page.

    */

    public function start()
    {
		$hooks = $this->app['hooks'];
		$error = 0;
        if ( empty($_POST['username']) || empty($_POST['password']) )
        {
            $errors[] = 'Please enter your username and password!';
            $error = 1;
        }
        else
        {
            $player = $this->validate($_POST['3rdparty']);
            if ( $player === false )
            {
                $errors[] = 'Please check your username/password!';
                $error = 1;
            }
        }
        if ( $error == 0 )
        {
			if($_POST['3rdparty'] != 'ezrpg')
			{
				$data = array('post'=>$_POST, 'player'=>$player);
				$this->mergePlayer($data);
				exit;
			}
            $error = $this->ezrpg_pass_match($player);

            if ( $error['error'] == 0 )
            {
				$this->login_player($player);
            }
		}
        //If we made it this far, then there's an issue

        session_unset();

        foreach ( $errors as $errmsg )
        {
            //Sets message(s) to FAIL
            $this->setMessage($errmsg, 'FAIL');
        }

        header('Location: index.php');
        exit;
    }

    private function validate($logins)
    {
		if($logins == "ezrpg")
		{
			$query = $this->db->execute('SELECT `id`, `username`, `password`, `secret_key`, `pass_method` FROM `<ezrpg>players` WHERE `username`=?', array( $_POST['username'] ));

			if ( $this->db->numRows($query) == 0 )
			{
				return false;
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
			return $this->app['hooks']->run_hooks('3rdpartylogins_'.$logins, $_POST);
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
					return array('error'=>1,
							'errors'=>array('Password Set as Old Method!')
							);
				}
				else
				{
					$new_password = createPassword($player->secret_key, $_POST['password']);
					$this->db->execute('UPDATE `<ezrpg>players` SET `password`=?, `pass_method`=? WHERE `id`=?', array( $new_password, $pass_method, $player->id ));
					killPlayerCache($player->id);
					return array('error'=>0);
				}
			}
		}
	}
	
	public function mergePlayer($data)
	{
		$query = $this->db->execute('SELECT * FROM <ezrpg>players WHERE username=?', array($data['player']->username));
		if($this->db->numRows($query))
		{
			$user = $this->db->fetch($query);
			
			if($user->username == $data['player']->username && $user->password == $data['player']->password)
			{
				return $this->login($user);
			}else{
				$query2 = $this->db->execute('SELECT * FROM <ezrpg>players WHERE username=?', array($data['post']['3rdparty'].'_'.$data['player']->username));
				if($this->db->numRows($query2))
				{
					$user2 = $this->db->fetch($query2);
					
					if($user2->username == $data['post']['3rdparty'].'_'.$data['player']->username)
					{
						return $this->login_player($user2);
					}else{
						$pid = $this->app['players']->register($data['post']['3rdparty'].'_'.$data['player']->username, $data['player']->password, $data['player']->email);
						$player_query = $this->db->execute('SELECT * FROM <ezrpg>players WHERE id=?', array($pid));
						$player = $this->db->fetch($player_query);
						return $this->login_player($player);
					}
				}else{
					$pid = $this->app['players']->register($data['post']['3rdparty'].'_'.$data['player']->username, $data['player']->password, $data['player']->email);
					$player_query = $this->db->execute('SELECT * FROM <ezrpg>players WHERE id=?', array($pid));
					$player = $this->db->fetch($player_query);
					return $this->login_player($player);
				}
				
			}
		}else{
			$pid = $this->app['players']->register($data['player']->username, $data['player']->password, $data['player']->email);
			$player_query = $this->db->execute('SELECT * FROM <ezrpg>players WHERE id=?', array($pid));
			$player = $this->db->fetch($player_query);
			return $this->login_player($player);
		}
	}
	
	public function login_form()
	{
		$this->loadView('Login_form.tpl', 'Login');
	}
	
	public function login_player($player_info)
	{
		global $app;
		 //Run login hook
		$player = $app['hooks']->run_hooks('login', $player_info);
		$query = $this->db->execute('UPDATE `<ezrpg>players_meta` SET `last_login`=? WHERE `pid`=?', array( time(), $player->id ));
		$_SESSION['userid'] = $player->id;
		$_SESSION['hash'] = generateSignature();
		$_SESSION['last_active'] = time();

		$app['hooks']->run_hooks('login_after', $player);
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

?>
