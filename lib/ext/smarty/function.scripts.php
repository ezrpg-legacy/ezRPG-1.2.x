<?php
/*
 *     Smarty plugin for ezRPG
 * -------------------------------------------------------------
 * File:        function.scripts.php
 * Type:        function
 * Name:        scripts
 * Description: This TAG creates an html tag for scripts/css included in header.
 * Author:		UAKTags
 *
 * -------------------------------------------------------------
 * Parameter:
 * - t         = Type (required)
 * - f		   = Filename (required)
 * -------------------------------------------------------------
 * Example usage:
 *
 * <div>{scripts t="css" f="path/to/file.css"} </div>
 */
function smarty_function_scripts($params, &$smarty) {
	$print = '<!-- Not Found ';
	if(DEBUG_MODE)
	{
		$print .= '<pre>'. print_r($params) . '</pre>';
	}
	$print .= '-->';
	if(!empty($params))
	{
		if (!empty($params['t']))
		{
			if (!empty($params['f']))
			{
				if($params['t'] == 'css')
				{
					$print = '<link rel="stylesheet" href="'.$params['f'].'" type="text/css" />';
				}elseif($params['t'] == 'js')
				{
					$print = '<script src="'.$params['f'].'"></script>';
				}
			}
		}
	}
    return $print;
}


?>