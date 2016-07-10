<?php
/*
 *     Smarty plugin for ezRPG
 * -------------------------------------------------------------
 * File:        function.module.php
 * Type:        function
 * Name:        module
 * Description: This TAG calls a specific function inside a specified module.
 * Author:		UAKTags
 *
 * -------------------------------------------------------------
 * Parameter:
 * - n         = Name of module (required)
 * - f		   = Function (optional)
 * -------------------------------------------------------------
 * Example usage:
 *
 * <div>{module n="Login" f="login_form"}</div>
 */
function smarty_function_module($params, &$smarty) {
	$print = '<!-- Not Found -->';
	if(DEBUG_MODE)
	{
		//$print .= '<pre>'. print_r($params) . '</pre>';
	}
	//$print .= '-->';
	if(!empty($params))
	{
		if (!empty($params['n']))
		{
			global $db, $tpl, $player, $menu, $settings;
			$module = ModuleFactory::factory($db, $tpl, $player, $params['n'], $menu, $settings);
			if(!empty($params['f']))
			{
				$print = $module->$params['f']();
			}else
			{
				$print = $module->start();
			}
		}
	}
    return $print;
}


?>