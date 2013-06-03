<?php /* Smarty version Smarty-3.1.8, created on 2013-06-03 17:36:17
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/themes/default\admin\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2676551acb7f18e1549-84060978%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5d20cdc58c76cdc7243997c5c9199d4985267175' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/themes/default\\admin\\index.tpl',
      1 => 1369838357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2676551acb7f18e1549-84060978',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MENU_AdminModules' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51acb7f195f484_28493454',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51acb7f195f484_28493454')) {function content_51acb7f195f484_28493454($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("admin/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Admin"), 0);?>

<h1>Admin</h1>
<h2>Admin Modules</h2>
<?php echo $_smarty_tpl->tpl_vars['MENU_AdminModules']->value;?>

<p>
If you install extra admin modules, edit <em>smarty/templates/admin/index.tpl</em> to add links above.
</p>

<?php echo $_smarty_tpl->getSubTemplate ("admin/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>