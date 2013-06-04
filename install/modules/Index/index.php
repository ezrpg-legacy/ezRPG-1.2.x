<?php

/**
 * Index
 */
class Install_Index extends InstallerFactory
{
	/**
	 * Default landing page for the installer, confirms that
	 * all required files have the correct permissions assigned.
	 */
	function start()
	{
		$parent_dir = dirname(dirname(dirname(__DIR__)));
		$invalid_perms = array();
		
		$required_files = array(
			$parent_dir . '/config.php'
		);
		
		$required_directories = array(
			$parent_dir . '/lib/ext/smarty/cache'
		);
		
		// validate files
		foreach($required_files as $file) {
			var_dump(touch($file), is_writable($file));
			touch($file) && is_writable($file) 
				?: array_push($invalid_perms, $file);
		}
		
		// validate directories
		foreach($required_directories as $directory) {
			is_dir($directory) && touch($directory . '/empty') 
				?: array_push($invalid_perms, $directory);
		}
		
		$this->header();
		
		if (!empty($invalid_perms)) {
			echo '<strong>The following file(s) and/or directory(s) needs to be writable:</strong>';
			echo '<ul>';
			
			foreach($invalid_perms as $context) {
				echo '<li>' . $context . '</li>';
			}
			
			echo '</ul>' . '<p>Please include write permiisions on these items.</p>';
			
			echo 'Once you have done so, try <a href="./index.php">verifying them again</a>.';
		} else {
			echo '<strong>Hooray!</strong><p>All of the files and/or directories have the correct permissions.</p>';
			echo '<a href="./index.php?step=Config">Continue to the next step</a>';
		}
		
		$this->footer();
	}
}