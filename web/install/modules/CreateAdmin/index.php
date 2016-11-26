<?php

namespace ezrpg\Install\Modules;
use ezrpg\Install\InstallerFactory,
    ezrpg\core\DbException,
    ezrpg\core\EzException;

class Install_CreateAdmin extends InstallerFactory
{

    function start()
    {
        if ( !isset($_POST['submit']) )
        {
            $sitefolder = strtok($_SERVER['PHP_SELF'], 'install');
            $siteurl = 'http://' . $_SERVER['HTTP_HOST'] . $sitefolder;
            $gamename = 'ezRPG 1.2.1';
            $username = '';
            $email = '';
        }
        else
        {
            $errors = 0;
            $msg = '';
            $siteurl = $_POST['siteurl'];
            $gamename = $_POST['gamename'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $email = $_POST['email'];
            if ( empty($username) || empty($email) || empty($password) || empty($password2) )
            {
                $errors = 1;
                $msg .= 'You forgot to fill in something!';
            }
            if ( !preg_match("/^[a-zA-Z0-9_]{4,16}$/", $username) )
            {
                $errors = 1;
                $msg .= 'Username is invalid!';
            }
            if ( $password != $password2 )
            {
                $errors = 1;
                $msg .= 'You didn\'t verify your password correctly.';
            }
            if ( strlen($password) < 6 )
            {
                $errors = 1;
                $msg .= 'Password must be at least 6 characters long.';
            }

            if ( $errors == 0 )
            {
                require_once ROOT_DIR . "/core/functions/rand.php";
                try
                {
                    $this->container['app']->getConfig();
                    // Initialize the Database
                    $this->container['app']->setDatabase();

                    //$db = \ezrpg\core\database\DatabaseFactory::factory($this->container['config']);
                }
                catch ( DbException $e )
                {
                    $e->__toString();
                }
                $insert = array( );
                $insert['username'] = $username;
                $insert['password'] = password_hash($password, PASSWORD_BCRYPT);
                $insert['email'] = $email;
                $insert['registered'] = time();
                $insert['rank'] = 10;
                $new_admin = $this->container['db']->insert("<ezrpg>players", $insert);
                $admin_meta = array( );
                $admin_meta['pid'] = $new_admin;
                $this->container['db']->insert("<ezrpg>players_meta", $admin_meta);
                try{
                    $this->container['app']->hooks->run_hooks('register_after', $admin_meta['pid']);
                }catch( \Exception $e){
                    throw new EzException($e->getMessage() . $e->getLine());
                }
                $config = <<<CONF
<?php
return [
    'app' => [
        'game_name' => [
            'value' => '{$gamename}'
        ],
        'site_url' => [
            'value' => '{$siteurl}'
        ]
    ]
];
CONF;
                $fh = fopen(ROOT_DIR . '/config/site.php', 'w');
                fwrite($fh, $config);
                fclose($fh);

                $this->container['app']->buildConfigCache();

                $this->header();
                echo "<p>Your admin account has been created! You may now login to the game.</p>\n";
                $fh = fopen(CUR_DIR . "/lock", "w+");
                if ( !$fh )
                {
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
        echo '<label>SiteURL</label>';
        echo '<input type="text" name="siteurl" value="', $siteurl, '" />';
        echo '<label>Game Name</label>';
        echo '<input type="text" name="gamename" value="', $gamename, '" />';
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
