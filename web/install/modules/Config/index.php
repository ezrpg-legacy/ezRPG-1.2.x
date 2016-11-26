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
            $dbconfig['host'] = "localhost";
            $dbconfig['user'] = "root";
            $dbconfig['name'] = "ezrpg";
            $dbconfig['port'] = "3306";
            $dbconfig['pass'] = "ezrpg_";
            $dbconfig['prefix'] = "ezrpg_";
        }
        else
        {
            $dbconfig['host'] = $_POST['dbhost'];
            $dbconfig['user'] = $_POST['dbuser'];
            $dbconfig['name'] = $_POST['dbname'];
            $dbconfig['pass'] = $_POST['dbpass'];
            $dbconfig['prefix'] = $_POST['dbprefix'];
            $dbconfig['driver'] = $_POST['dbdriver'];
            $dbconfig['port'] = $_POST['dbport'];
            $error = 0;

            //test database connection.
            try
            {
                $conf['database'] = $dbconfig;
                $db = \ezrpg\core\DbFactory::factory($conf);
            }
            catch ( \PDOException $e )
            {
                $error = 1;
            }
            if ( $error != 1 )
            {
                require_once(ROOT_DIR . "/core/functions/rand.php");
                $secret_key = createKey(24);
                $configdb = <<<CONF
<?php
return [
    'database' => [
        'host' => '{$dbconfig['host']}',
        'name' => '{$dbconfig['name']}',
        'user' => '{$dbconfig['user']}',
        'pass' => '{$dbconfig['pass']}',
        'driver' => '{$dbconfig['driver']}',
        'port' => '{$dbconfig['port']}',
        'prefix' => '{$dbconfig['prefix']}',
    ],
    'secret' => '{$secret_key}',
];
CONF;
                $fh = fopen(ROOT_DIR . '/config/database.php', 'w');
                fwrite($fh, $configdb);
                fclose($fh);
                $this->header();
                if ( filesize(ROOT_DIR . '/config/database.php') == 0 )
                {
                    rename(ROOT_DIR . '/config/database.php', ROOT_DIR . '/config/database.php.bak');
                    echo "<h2>Error Writing To Config.php</h2>";
                    echo "<p>There was an error writing to Config.php.</p>";
                    echo "<p>Before continuing, please rename 'http://ezrpg/config/database.php' to 'http://ezrpg/config/database.php.bak' and update with the following:</p><br />\n";
                    echo "<pre style='background-color: lightgrey;'><code style='word-wrap: break-word;'>&#60;?php<br />
return [<br />
    'database' => [<br />
        'host' => '{$dbconfig['host']}',<br />
        'name' => '{$dbconfig['name']}',<br />
        'user' => '{$dbconfig['user']}',<br />
        'pass' => '{$dbconfig['pass']}',<br />
        'driver' => '{$dbconfig['driver']}',<br />
        'port' => '{$dbconfig['port']}',<br />
        'prefix' => '{$dbconfig['prefix']}',<br />
    ],<br />
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
        echo '<input type="text" name="dbhost" value="' . $dbconfig['host'] . '" />';
        echo '<label>Port</label>';
        echo '<input type="text" name="dbport" value="' . $dbconfig['port'] . '" />';
        echo '<label>Database Name</label>';
        echo '<input type="text" name="dbname" value="' . $dbconfig['name'] . '" />';
        echo '<label>User</label>';
        echo '<input type="text" name="dbuser" value="' . $dbconfig['user'] . '" />';
        echo '<label>Password</label>';
        echo '<input type="password" name="dbpass" value="" />';
        echo '<label>Table Prefix (Optional)</label>';
        echo '<input type="text" name="dbprefix" value="', $dbconfig['prefix'], '" />';
        echo '<p>You can enter a prefix for your table names if you like.<br />This can be useful if you will be sharing the database with other applications, or if you are running more than one ezRPG instance in a single database.</p>';
        echo '<p><strong>Note</strong> Please make sure that the database exists.</p>';
        echo '<input type="submit" name="submit" value="Submit"  class="button" />';
        echo '</form>';
    }

}
