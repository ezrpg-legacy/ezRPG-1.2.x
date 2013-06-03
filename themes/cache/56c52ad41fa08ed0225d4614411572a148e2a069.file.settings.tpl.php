<?php /* Smarty version Smarty-3.1.8, created on 2013-06-03 17:57:00
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/themes/default\admin\settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1351151acbb406db930-25692119%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '56c52ad41fa08ed0225d4614411572a148e2a069' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/themes/default\\admin\\settings.tpl',
      1 => 1370275020,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1351151acbb406db930-25692119',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51acbb408087d4_85622070',
  'variables' => 
  array (
    'error' => 0,
    'errormsg' => 0,
    'groups' => 0,
    'gitem' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51acbb408087d4_85622070')) {function content_51acbb408087d4_85622070($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("admin/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Plugin/Module Admin"), 0);?>


<!-- START OF ADMINCP_PLUGINS_ROW-->
<?php if ($_smarty_tpl->tpl_vars['error']->value!=0){?>
<b>You have an error with a code of <?php echo $_smarty_tpl->tpl_vars['error']->value;?>
:</b>
<b><?php echo $_smarty_tpl->tpl_vars['errormsg']->value;?>
</b>
<br />
<?php }?>
<div>
<table width="100%" border="1px">
  <tbody>
		<tr>
			<th width="25%">
				Setting Groups
			</th>
		</tr>
<?php  $_smarty_tpl->tpl_vars['gitem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['gitem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['gitem']->key => $_smarty_tpl->tpl_vars['gitem']->value){
$_smarty_tpl->tpl_vars['gitem']->_loop = true;
?>
			<td valign="top">
				<a href="index.php?mod=Settings&act=getGroup&gid=<?php echo $_smarty_tpl->tpl_vars['gitem']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['gitem']->value->title;?>
</a><br>
			</td>
<?php } ?>
		</tr>
	</tbody>
</table>
</div>

<!-- END OF ADMINCP_PLUGINS_ROW-->
<a href="index.php?mod=Menu&act=add"><input name="login" type="submit" class="button" value="Add New.." /></a>

<?php echo $_smarty_tpl->getSubTemplate ("admin/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>