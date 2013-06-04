<?php

class Install_CreateAdmin extends InstallerFactory
{
	function start()
	{
		if(!isset($_POST['submit'])){
			$username = '';
			$email = '';
		} else {
			$errors = 0;
			$msg = '';
			$username = $_POST['username'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$email = $_POST['email'];
			if (empty($username) || empty($email) || empty($password) || empty($password2))
			{
				$errors = 1;
				$msg .= 'You forgot to fill in something!';
			}
			if ($password != $password2)
			{
				$errors = 1;
				$msg .= 'You didn\'t verify your password correctly.';
			}
        
			if ($errors == 0)
			{
				require_once "../config.php";
				require_once "../lib/func.rand.php";
				try
				{
					$db = DbFactory::factory($config_driver, $config_server, $config_username, $config_password, $config_dbname);
				}
				catch (DbException $e)
				{
					$e->__toString();
				}

				$secret_key = createKey(16);
				$insert = array();
				$insert['username'] = $username;
				$insert['password'] = sha1($secret_key . $password . SECRET_KEY);
				$insert['email'] = $email;
				$insert['secret_key'] = $secret_key;
				$insert['registered'] = time();
				$insert['rank'] = 10;
				$new_admin = $db->insert("<ezrpg>players", $insert);
				$admin_meta = array();
				$admin_meta['pid'] = $new_admin;
				$db->insert("<ezrpg>players_meta", $admin_meta);
				$this->header();
				echo "<p>Your admin account has been created! You may now login to the game.</p>\n";
				$fh = fopen("lock", "w+");
				if(!$fh){
					echo "<p>You need to delete the install directory for security reasons as we were unable to lock it.</p>\n";
				}
				echo "<p><a href=\"../index.php\">Continue to your game</a></p>";
				fclose($fh);
				$this->footer();
				exit;
			}
			else
			{
				$this->header();
				echo '<p><strong>Sorry, there were some problems:</strong><br />', $msg, '</p>';
				$this->footer();
			}
		}
		$this->header();
		echo '<h2>Create your admin account for ezRPG.</h2>';
		echo '<form method="post">';
		echo '<label>Username</label>';
		echo '<input type="text" name="username" value="', $username, '" />';
		echo '<label>Email</label>';
		echo '<input type="text" name="email" value="', $email, '" />';
		echo '<label>Password</label>';
		echo '<input type="password" name="password" />';
		echo '<label>Verify Password</label>';
		echo '<input type="password" name="password2" />';
		echo '<br />';
		echo '<input type="submit" value="Create" name="submit" class="button" />';
		echo '</form>';
	}
}
?>
