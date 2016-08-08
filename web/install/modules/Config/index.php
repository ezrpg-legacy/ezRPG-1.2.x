<?php
namespace ezRPG\Install\Modules;
use ezRPG\Install\InstallerFactory;

class Install_Config extends InstallerFactory
{

    /**
     * This method creates the configuration file.
     */
    function start()
    {
        $dbconfig = [];
        if ( !isset($_POST['submit']) )
        {
            $dbconfig['dbserver'] = "localhost";
            $dbconfig['dbuser'] = "root";
            $dbconfig['dbname'] = "ezrpg";
            $dbconfig['dbport'] = "3306";
            $dbconfig['dbpass'] = "ezrpg_";
        }
        else
        {
            $dbconfig['dbserver'] = $_POST['dbhost'];
            $dbconfig['dbuser'] = $_POST['dbuser'];
            $dbconfig['dbname'] = $_POST['dbname'];
            $dbconfig['dbpass'] = $_POST['dbpass'];
            $dbconfig['dbprefix'] = $_POST['dbprefix'];
            $dbconfig['dbdriver'] = $_POST['dbdriver'];
            $dbconfig['dbport'] = $_POST['dbport'];
            $error = 0;

            //test database connection.
            try
            {
                $db = \ezRPG\lib\DbFactory::factory($dbconfig);
            }
            catch ( DbException $e )
            {
                $error = 1;
            }
            if ( $error != 1 )
            {
                require_once(ROOT_DIR . "/lib/functions/func.rand.php");
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
  \$config_port - Contains the database port your database server port.
*/
\$config_server = '{$dbconfig['dbserver']}';
\$config_dbname = '{$dbconfig['dbname']}';
\$config_username = '{$dbconfig['dbuser']}';
\$config_password = '{$dbconfig['dbpass']}';
\$config_driver = '{$dbconfig['dbdriver']}';
\$config_port = '{$dbconfig['dbport']}';

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
define('DB_PREFIX', '{$dbconfig['dbprefix']}');
define('VERSION', '1.2.1.7');
define('SHOW_ERRORS', 0);
define('DEBUG_MODE', 0);
?>
CONF;
                $fh = fopen(ROOT_DIR . '/config.php', 'w');
                fwrite($fh, $config);
                fclose($fh);
                $this->header();
                if ( filesize(ROOT_DIR . '/config.php') == 0 )
                {
                    rename(ROOT_DIR . '/config.php', ROOT_DIR . '/config.php.bak');
                    echo "<h2>Error Writing To Config.php</h2>";
                    echo "<p>There was an error writing to Config.php.</p>";
                    echo "<p>Before continuing, please rename 'http://ezrpg/config.php.bak' to 'http://ezrpg/config.php.bak' and update with the following:</p><br />\n";
                    echo "<pre style='background-color: lightgrey;'><code style='word-wrap: break-word;'>&#60;?php<br />
//This file cannot be viewed, it must be included<br />
defined('IN_EZRPG') or exit; <br />
<br />
/*<br />
  Title: Config<br />
  The most important settings for the game are set here.<br />
*/<br />
<br />
/*<br />
  Variables: Database Connection<br />
  Connection settings for the database.<br />
  <br />
  \$config_server - Database server <br />
  \$config_dbname - Database name <br />
  \$config_username - Username to login to server with<br />
  \$config_password - Password to login to server with<br />
  \$config_driver - Contains the database driver to use to connect to the database.<br />
  \$config_port - Contains the database port your database server port.<br />
*/<br />
\$config_server = '{$dbconfig['dbserver']}';<br />
\$config_dbname = '{$dbconfig['dbname']}';<br />
\$config_username = '{$dbconfig['dbuser']}';<br />
\$config_password = '{$dbconfig['dbpass']}';<br />
\$config_driver = '{$dbconfig['dbdriver']}';<br />
\$config_port = '{$dbconfig['dbport']}';<br />
<br />
/*<br />
  Constant:<br />
  This secret key is used in the hashing of player passwords and other important data.<br />
  Secret keys can be of any length, however longer keys are more effective.<br />
  <br />
  This should only ever be set ONCE! Any changes to it will cause your game to break!<br />
  You should save a copy of the key on your computer, just in case the secret key is lost or accidentally changed,.<br />
  <br />
  SECRET_KEY - A long string of random characters.<br />
*/<br />
define('SECRET_KEY', '{$secret_key}');<br />
<br />
<br />
/*<br />
  Constants: Settings<br />
  Various settings used in ezRPG.<br />
  <br />
  DB_PREFIX - Prefix to the table names<br />
  VERSION - Version of ezRPG<br />
  SHOW_ERRORS - Turn on to show PHP errors.<br />
  DEBUG_MODE - Turn on to show database errors and debug information.<br />
*/<br />
define('DB_PREFIX', '{$dbconfig['dbprefix']}');<br />
define('VERSION', '1.2.1.7');<br />
define('SHOW_ERRORS', 0);<br />
define('DEBUG_MODE', 0);<br />
?><br /></code></pre>";
                    echo "<a href=\"index.php?step=Populate\">Continue to next step</a>";
                }
                else
                {
                    echo "<h2>Configuration file written</h2>\n";
                    echo "<p>The configuration has ben verified, and the config.php file has been successfully written.</p><br />\n";
                    echo "<a href=\"index.php?step=Populate\">Continue to next step</a>";
                }
                $this->footer();
                die;
            }
        }

        $this->header();
        echo "<h2>Database Configuration</h2><br />\n";
        echo '<form method="post">';
        echo '<label>Driver</label>';
        echo '<select name="dbdriver"><option value="mysql">MySQLi</option></select>';
        echo '<label>Host</label>';
        echo '<input type="text" name="dbhost" value="' . $dbconfig['dbserver'] . '" />';
        echo '<label>Port</label>';
        echo '<input type="text" name="dbport" value="' . $dbconfig['dbport'] . '" />';
        echo '<label>Database Name</label>';
        echo '<input type="text" name="dbname" value="' . $dbconfig['dbname'] . '" />';
        echo '<label>User</label>';
        echo '<input type="text" name="dbuser" value="' . $dbconfig['dbuser'] . '" />';
        echo '<label>Password</label>';
        echo '<input type="password" name="dbpass" value="" />';
        echo '<label>Table Prefix (Optional)</label>';
        echo '<input type="text" name="dbprefix" value="', $dbconfig['dbprefix'], '" />';
        echo '<p>You can enter a prefix for your table names if you like.<br />This can be useful if you will be sharing the database with other applications, or if you are running more than one ezRPG instance in a single database.</p>';
        echo '<p><strong>Note</strong> Please make sure that the database exists.</p>';
        echo '<input type="submit" name="submit" value="Submit"  class="button" />';
        echo '</form>';
    }

}
