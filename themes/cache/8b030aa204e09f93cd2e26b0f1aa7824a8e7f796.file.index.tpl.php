<?php /* Smarty version Smarty-3.1.8, created on 2013-05-30 02:03:37
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/smarty/templates\admin\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1770551a697594d31a6-57991852%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8b030aa204e09f93cd2e26b0f1aa7824a8e7f796' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/smarty/templates\\admin\\index.tpl',
      1 => 1369838357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1770551a697594d31a6-57991852',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MENU_AdminModules' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51a69759548ef0_32980580',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a69759548ef0_32980580')) {function content_51a69759548ef0_32980580($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("admin/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Admin"), 0);?>

<h1>Admin</h1>
<h2>Admin Modules</h2>
<?php echo $_smarty_tpl->tpl_vars['MENU_AdminModules']->value;?>

<p>
If you install extra admin modules, edit <em>smarty/templates/admin/index.tpl</em> to add links above.
</p>

<?php echo $_smarty_tpl->getSubTemplate ("admin/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>