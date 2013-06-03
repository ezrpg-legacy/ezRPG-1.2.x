<?php /* Smarty version Smarty-3.1.8, created on 2013-06-03 21:33:28
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120\themes\core\admin\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1766851acef88c90a86-04346316%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1b86b6815304a2d1f7ac28c1b1ce6f495bc181ee' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120\\themes\\core\\admin\\index.tpl',
      1 => 1370287648,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1766851acef88c90a86-04346316',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'TOP_MENU_AdminModules' => 0,
    'menu' => 0,
    'menukey' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51acef88d394e7_71853968',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51acef88d394e7_71853968')) {function content_51acef88d394e7_71853968($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("admin/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Admin"), 0);?>

<h1>Admin</h1>
<h2>Admin Modules</h2>
<ul>
	<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_smarty_tpl->tpl_vars['menukey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['TOP_MENU_AdminModules']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['menukey']->value = $_smarty_tpl->tpl_vars['menu']->key;
?> 
	<li><a href=<?php echo $_smarty_tpl->tpl_vars['menu']->value;?>
><?php echo $_smarty_tpl->tpl_vars['menukey']->value;?>
</a></li>
	<?php } ?>
	</ul>
<p>
If you install extra admin modules, edit <em>smarty/templates/admin/index.tpl</em> to add links above.
</p>

<?php echo $_smarty_tpl->getSubTemplate ("admin/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>