<?php /* Smarty version Smarty-3.1.8, created on 2013-06-03 20:22:56
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/themes/default\admin\settings_page.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13751acc162e33c75-65160667%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b16f3344c54ce717e1d88bd5ff78e6c23a82fd17' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/themes/default\\admin\\settings_page.tpl',
      1 => 1370283690,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13751acc162e33c75-65160667',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51acc162ee0e11_89837067',
  'variables' => 
  array (
    'error' => 0,
    'errormsg' => 0,
    'GROUP' => 0,
    'settings' => 0,
    'sitem' => 0,
    'allSettings' => 0,
    'subitem' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51acc162ee0e11_89837067')) {function content_51acc162ee0e11_89837067($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("admin/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Plugin/Module Admin"), 0);?>


<!-- START OF ADMINCP_PLUGINS_ROW-->
<?php if ($_smarty_tpl->tpl_vars['error']->value!=0){?>
<b>You have an error with a code of <?php echo $_smarty_tpl->tpl_vars['error']->value;?>
:</b>
<b><?php echo $_smarty_tpl->tpl_vars['errormsg']->value;?>
</b>
<br />
<?php }?>
<div>
		<form method="post" action="index.php?mod=Settings&act=save">
<table width="100%" border="1px">
  <tbody>
		<tr>
			<th width="25%">
				<?php echo $_smarty_tpl->tpl_vars['GROUP']->value;?>

			</th>
		</tr>
<?php  $_smarty_tpl->tpl_vars['sitem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sitem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['settings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sitem']->key => $_smarty_tpl->tpl_vars['sitem']->value){
$_smarty_tpl->tpl_vars['sitem']->_loop = true;
?>
		<?php if ($_smarty_tpl->tpl_vars['sitem']->value->visible==1){?>
		<tr>
			<td valign="top">
				<strong><?php echo $_smarty_tpl->tpl_vars['sitem']->value->title;?>
</strong><br>
				<?php echo $_smarty_tpl->tpl_vars['sitem']->value->description;?>
<br>
				<?php if ($_smarty_tpl->tpl_vars['sitem']->value->optionscode=='text'){?>
					<input name="sid<?php echo $_smarty_tpl->tpl_vars['sitem']->value->id;?>
" type='text' value="<?php echo $_smarty_tpl->tpl_vars['sitem']->value->value;?>
"></input>
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['sitem']->value->optionscode=='select'){?>
					<select name="sgid<?php echo $_smarty_tpl->tpl_vars['sitem']->value->id;?>
">
						<?php  $_smarty_tpl->tpl_vars['subitem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['subitem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['allSettings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['subitem']->key => $_smarty_tpl->tpl_vars['subitem']->value){
$_smarty_tpl->tpl_vars['subitem']->_loop = true;
?>
						<?php if ($_smarty_tpl->tpl_vars['sitem']->value->id==$_smarty_tpl->tpl_vars['subitem']->value->gid){?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['subitem']->value->id;?>
" <?php if ($_smarty_tpl->tpl_vars['subitem']->value->id==$_smarty_tpl->tpl_vars['sitem']->value->value){?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['subitem']->value->title;?>
</option>
						<?php }?>
						<?php } ?>
					</select>
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['sitem']->value->optionscode=='radio'){?>
					<?php  $_smarty_tpl->tpl_vars['subitem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['subitem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['allSettings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['subitem']->key => $_smarty_tpl->tpl_vars['subitem']->value){
$_smarty_tpl->tpl_vars['subitem']->_loop = true;
?>
					<?php if ($_smarty_tpl->tpl_vars['sitem']->value->id==$_smarty_tpl->tpl_vars['subitem']->value->gid){?>
					<input name="sgid<?php echo $_smarty_tpl->tpl_vars['sitem']->value->id;?>
" type='radio' name='setting<?php echo $_smarty_tpl->tpl_vars['sitem']->value->id;?>
' value="<?php echo $_smarty_tpl->tpl_vars['subitem']->value->id;?>
" <?php if ($_smarty_tpl->tpl_vars['subitem']->value->id==$_smarty_tpl->tpl_vars['sitem']->value->value){?> checked<?php }?>><?php echo $_smarty_tpl->tpl_vars['subitem']->value->title;?>
</input>
					<?php }?>
					<?php } ?>
				<?php }?>
			</td>
		</tr>
		<?php }?>
<?php } ?>

	</tbody>
</table>

</div>
<input type="hidden" name="act" value="save" />
<input type="submit" class="button" value="Save Settings" name='save' />
</form>
<!-- END OF ADMINCP_PLUGINS_ROW-->
<a href="index.php?mod=Menu&act=add"><input name="login" type="submit" class="button" value="Add New.." /></a>

<?php echo $_smarty_tpl->getSubTemplate ("admin/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>