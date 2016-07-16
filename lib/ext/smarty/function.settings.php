<?php
/*
 *     Smarty plugin for ezRPG
 * -------------------------------------------------------------
 * File:        function.settings.php
 * Type:        function
 * Name:        settings
 * Description: This TAG grabs the value from our Settings where g=group and n=name.
 * Author:		UAKTags
 *
 * -------------------------------------------------------------
 * Parameter:
 * - g         = Settings Group (required)
 * - n		   = Settings Name (required)
 * -------------------------------------------------------------
 * Example usage:
 *
 * <div>{settings g="general" n="game_name"} ago </div>
 */
function smarty_function_settings($params, &$smarty) {
	global $container;
	$print = 'Setting Doesn\'t exist';
	if(!empty($params))
	{
		if (!empty($params['g']))
		{
		if (!empty($params['n']))
		{
			$print = $container['settings']->setting[$params['g']][$params['n']]['value'];
		}
		}
	}
    return $print;
}


?>