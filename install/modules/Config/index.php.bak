<?php
class Install_Config extends InstallerFactory
{
	function start(){
		if(!isset($_POST['submit'])){
			$dbhost = "localhost";
			$dbuser = "root";
			$dbname = "ezrpg";
			$dbprefix = "ezrpg_";
		}else{
			$dbhost = $_POST['dbhost'];
			$dbuser = $_POST['dbuser'];
			$dbname = $_POST['dbname'];
			$dbpass = $_POST['dbpass'];
			$dbprefix = $_POST['dbprefix'];
			$dbdriver = $_POST['dbdriver'];
			$error = 0;

			//test database connection.
			define("DB_PREFIX", $dbprefix);
			try
			{
				$db = DbFactory::factory($dbdriver, $dbhost, $dbuser, $dbpass, $dbname);
			}
			catch (DbException $e)
			{
				$error = 1;
			}
			if($error != 1){
				require_once("../lib/func.rand.php");
				$secret_key = createKey(24);
				$config = <<<CONF
<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Title: Config
  The most important settings for the game are set here.
*/

/*
  Variables: Database Connection
  Connection settings for the database.
  
  \$config_server - Database server
  \$config_dbname - Database name
  \$config_username - Username to login to server with
  \$config_password - Password to login to server with
  \$config_driver - Contains the database driver to use to connect to the database.
*/
\$config_server = '{$dbhost}';
\$config_dbname = '{$dbname}';
\$config_username = '{$dbuser}';
\$config_password = '{$dbpass}';
\$config_driver = '{$dbdriver}';

/*
  Constant:
  This secret key is used in the hashing of player passwords and other important data.
  Secret keys can be of any length, however longer keys are more effective.
  
  This should only ever be set ONCE! Any changes to it will cause your game to break!
  You should save a copy of the key on your computer, just in case the secret key is lost or accidentally changed,.
  
  SECRET_KEY - A long string of random characters.
*/
define('SECRET_KEY', '{$secret_key}');


/*
  Constants: Settings
  Various settings used in ezRPG.
  
  DB_PREFIX - Prefix to the table names
  VERSION - Version of ezRPG
  SHOW_ERRORS - Turn on to show PHP errors.
  DEBUG_MODE - Turn on to show database errors and debug information.
*/
define('DB_PREFIX', '{$dbprefix}');
define('VERSION', '1.2.0');
define('SHOW_ERRORS', 0);
define('DEBUG_MODE', 0);
?>
CONF;
				$fh = fopen('../config.php', 'w');
				fwrite($fh, $config);
				fclose($fh);
				$this->header();
				echo "<h2>Configuration file written</h2>\n";
				echo "<p>The configuration has ben verified, and the config.php file has been successfully written.</p><br />\n";
				echo "<a href=\"index.php?step=Populate\">Continue to next step</a>";
				$this->footer();
				die;
			}
		}
		$this->header();
		echo "<h2>Database Configuration</h2><br />\n";
		echo '<form method="post">';
		echo '<label>Driver</label>';
		echo '<select name="dbdriver"><option value="mysql">MySQL</option><option value="mysqli">MySQLi</option></select>';
		echo '<label>Host</label>';
		echo '<input type="text" name="dbhost" value="' . $dbhost . '" />';
		echo '<label>Database Name</label>';
		echo '<input type="text" name="dbname" value="' . $dbname . '" />';
		echo '<label>User</label>';
		echo '<input type="text" name="dbuser" value="' . $dbuser . '" />';
		echo '<label>Password</label>';
		echo '<input type="password" name="dbpass" value="" />';
		echo '<label>Table Prefix (Optional)</label>';
		echo '<input type="text" name="dbprefix" value="', $dbprefix, '" />';
		echo '<p>You can enter a prefix for your table names if you like.<br />This can be useful if you will be sharing the database with other applications, or if you are running more than one ezRPG instance in a single database.</p>';
		echo '<input type="submit" name="submit" value="Submit"  class="button" />';
		echo '</form>';
	}
}
?>
