<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_Login
  This module handles user authentication.
*/
class Module_Login extends Base_Module
{
    /*
      Function: start
      Checks player details to login the player.
	
      If successful, a new session is generated and the user is redirected to the game.
	
      On failure, session data is cleared and the user is redirected back to the login page.
    */
    public function start()
    {
        $fail = '';
		$warn = '';

        if (empty($_POST['username']) || empty($_POST['password'])) {
            $warn = 'Please enter your username and password!';
		} else {
			$player = $this->validate();
			if ($player === false)
				$fail = 'Please check your username/password!';
		}
        if (empty($fail) && empty($warn)) {
			$query = $this->db->execute('SELECT `id`, `username`, `password`, `secret_key`, `pass_method` FROM `<ezrpg>players` WHERE `username`=?', array($_POST['username']));
			if ($this->db->numRows($query) == 0)
			{
				$errors[] = 'Please check your username/password!';
				$error = 1;
			}
			else
			{
				$player = $this->db->fetch($query);
				$pass_method = $this->settings->get_settings_by_id($this->settings->get_settings_by_cat_name('general')['pass_encryption'])['value'];
				$check = checkPassword($player->secret_key, $_POST['password'], $player->password);
				if ($check !== TRUE)
				{
					if ($player->pass_method != $pass_method) {
						$check = checkPassword($player->secret_key, $_POST['password'], $player->password, $player->pass_method);
						if ($check !== TRUE)
						{
							$errors[] = 'Password Set as Old Method!';
							$error = 1;
						} else {
							$new_password = createPassword($player->secret_key, $_POST['password']);
							//$this->db->update('<ezrpg>players', $item['password']=$new_password, $item['id']=$player->id);
							$this->db->execute('UPDATE `<ezrpg>players` SET `password`=?, `pass_method`=? WHERE `id`=?', array($new_password, $pass_method, $player->id));
						}
					} else {
						$errors[] = 'Please check your username/password!';
						$error = 1;
					}
				}
			}
			
			if ($error == 0)
			{
				global $hooks;
				
				//Run login hook
				$player = $hooks->run_hooks('login', $player);
				
				$query = $this->db->execute('UPDATE `<ezrpg>players_meta` SET `last_login`=? WHERE `pid`=?', array(time(), $player->id));
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
			else
			{
				session_unset();
				
				$msg = 'Sorry, you could not login:<br />';
				$msg .= '<ul>';
				foreach($errors as $errmsg)
				{
					$msg .= '<li>' . $errmsg . '</li>';
				}
				$msg .= '</ul>';
				
				header('Location: index.php?msg=' . urlencode($msg));
				exit;
			}
		}
    }
private function validate() {
		global $settings;
		$query = $this->db->execute('SELECT `id`, `username`, `password`, `secret_key`, `pass_method` FROM `<ezrpg>players` WHERE `username`=?', array($_POST['username']));
        
		if ($this->db->numRows($query) == 0)
			return false;

        else {
            $player = $this->db->fetch($query);
            
            // We have different authentication methods at our disposal.
			$pass_meth = $settings->get_settings_by_id($settings->get_settings_by_cat_name('general')['pass_encryption'])['value'];
            $check = checkPassword($player->secret_key, $_POST['password'], $player->password, ($player->pass_method == $pass_meth ? '0': $player->pass_method));
            if ($check === FALSE){
				echo "false";
			}
			if ($check !== true) {
				return false;
			}

			return $player;
        }
	}
}
?>
