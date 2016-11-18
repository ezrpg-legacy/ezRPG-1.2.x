<?php
/*
 *     Smarty plugin for ezRPG
 * -------------------------------------------------------------
 * File:        function.hook.php
 * Type:        function
 * Name:        module
 * Description: This TAG calls a specific hook.
 * Author:		UAKTags
 *
 * -------------------------------------------------------------
 * Parameter:
 * - n         = Name of hook (required)
 * -------------------------------------------------------------
 * Example usage:
 *
 * <div>{hook n="header"}</div>
 */
function smarty_function_hook($params, &$smarty)
{
    $print = '<!-- Not Found -->';
    if ($this->container['config']['debug']['debug_mode']['debug_mode']) {
        //$print .= '<pre>'. print_r($params) . '</pre>';
    }
    //$print .= '-->';
    if (!empty($params)) {
        if (!empty($params['n'])) {
            global $container;
            $module = $container['hooks'];
            $print = $module->run_hooks($params['n'], $params);

        }
    }

    $smarty->assign('hook_' . $params['n'], $print);

    return;
}


?>