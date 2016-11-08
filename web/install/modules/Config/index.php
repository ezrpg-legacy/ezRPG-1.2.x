<?php
namespace ezrpg\Install\Modules;
use ezrpg\Install\InstallerFactory;

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
            $dbconfig['dbprefix'] = "ezrpg_";
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
                $db = \ezrpg\core\DbFactory::factory($dbconfig);
            }
            catch ( DbException $e )
            {
                $error = 1;
            }
            if ( $error != 1 )
            {
                require_once(ROOT_DIR . "/core/functions/func.rand.php");
                $secret_key = createKey(24);
                $config = <<<CONF
<?php
return [
    'database' => [
        'host' => '{$dbconfig['dbserver']}',
        'name' => '{$dbconfig['dbname']}',
        'user' => '{$dbconfig['dbuser']}',
        'pass' => '{$dbconfig['dbpass']}',
        'driver' => '{$dbconfig['dbdriver']}',
        'port' => '{$dbconfig['dbport']}',
        'prefix' => '{$dbconfig['dbprefix']}',
    ],
    'debug' => [
        'show_errors' => 0,
        'debug_mode' => 0,
    ],
    'version' => '1.2.1.9',
    'secret' => '{$secret_key}',
];
CONF;
                $fh = fopen(ROOT_DIR . '/config/core.php', 'w');
                fwrite($fh, $config);
                fclose($fh);
                $this->header();
                if ( filesize(ROOT_DIR . '/config/core.php') == 0 )
                {
                    rename(ROOT_DIR . '/config/core.php', ROOT_DIR . '/config/core.php.bak');
                    echo "<h2>Error Writing To Config.php</h2>";
                    echo "<p>There was an error writing to Config.php.</p>";
                    echo "<p>Before continuing, please rename 'http://ezrpg/config.php.bak' to 'http://ezrpg/config.php.bak' and update with the following:</p><br />\n";
                    echo "<pre style='background-color: lightgrey;'><code style='word-wrap: break-word;'>&#60;?php<br />
return [<br />
    'database' => [<br />
        'host' => '{$dbconfig['dbserver']}',<br />
        'name' => '{$dbconfig['dbname']}',<br />
        'user' => '{$dbconfig['dbuser']}',<br />
        'pass' => '{$dbconfig['dbpass']}',<br />
        'driver' => '{$dbconfig['dbdriver']}',<br />
        'port' => '{$dbconfig['dbport']}',<br />
        'prefix' => '{$dbconfig['dbprefix']}',<br />
    ],<br />
    'debug' => [<br />
        'show_errors' => 0,<br />
        'debug_mode' => 0,<br />
    ],<br />
    'version' => '1.2.1.9',<br />
    'secret' => '{$secret_key}',<br />
];<br />
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
