<?php
namespace ezrpg\Install\Modules;
use ezrpg\Install\InstallerFactory;

class Install_Index extends InstallerFactory
{

    function start()
    {
        if ( !is_writable(ROOT_DIR . '/config.php') )
        {
            //Attempt to create file
            $fh = fopen(ROOT_DIR . '/config.php', 'w+');
            if ( !$fh )
            {
                $files[] = "config.php";
            }
            fclose($fh);
        }
        if ( !is_writable(ROOT_DIR . '/cache/templates') )
        {
            $files[] = ROOT_DIR . "/cache/templates";
        }
        if ( !empty($files) )
        {
            $this->header();
            echo "<strong>The following files need to be writable</strong><br />\n<ul>\n";
            foreach ( $files as $file )
            {
                echo "$file <br />";
            }
            echo "\n</ul><br />\n";
            echo "<a href=\"index.php\">Click here to try again.</a>";
            $this->footer();
        }
        else
        {
            $this->header();
            echo "<strong>All files have correct permissions!</strong><br />\n";
            echo "<a href=\"index.php?step=Config\">Continue to next step</a>.";
            $this->footer();
        }
    }

}

?>
