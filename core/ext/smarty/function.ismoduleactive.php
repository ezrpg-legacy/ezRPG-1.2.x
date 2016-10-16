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
function smarty_function_ismoduleactive($params, &$smarty)
{
    if (!empty($params)) {
        if (!empty($params['n'])) {
            global $container;
            if(isModuleActive($params['n'])){
                return true;
            }
            return false;
        }
    }
}


?>