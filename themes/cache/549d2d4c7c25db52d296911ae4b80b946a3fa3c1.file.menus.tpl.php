<?php /* Smarty version Smarty-3.1.8, created on 2013-05-30 02:03:41
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/smarty/templates\admin\menus.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2283751a6975dd14678-38431166%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '549d2d4c7c25db52d296911ae4b80b946a3fa3c1' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/smarty/templates\\admin\\menus.tpl',
      1 => 1369838357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2283751a6975dd14678-38431166',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'error' => 0,
    'errormsg' => 0,
    'groups' => 0,
    'gitem' => 0,
    'menus' => 0,
    'mitem' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51a6975dde4752_06303017',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a6975dde4752_06303017')) {function content_51a6975dde4752_06303017($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("admin/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Plugin/Module Admin"), 0);?>


<!-- START OF ADMINCP_PLUGINS_ROW-->
<?php if ($_smarty_tpl->tpl_vars['error']->value!=0){?>
<b>You have an error with a code of <?php echo $_smarty_tpl->tpl_vars['error']->value;?>
:</b>
<b><?php echo $_smarty_tpl->tpl_vars['errormsg']->value;?>
</b>
<br />
<?php }?>
<ul class='tabs'>
<?php  $_smarty_tpl->tpl_vars['gitem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['gitem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['gitem']->key => $_smarty_tpl->tpl_vars['gitem']->value){
$_smarty_tpl->tpl_vars['gitem']->_loop = true;
?>
    <li><a href='#tab<?php echo $_smarty_tpl->tpl_vars['gitem']->value->id;?>
'><input type="submit" class="buton" value="<?php echo $_smarty_tpl->tpl_vars['gitem']->value->title;?>
"></a></li>
<?php } ?>
</ul>
<?php  $_smarty_tpl->tpl_vars['gitem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['gitem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['gitem']->key => $_smarty_tpl->tpl_vars['gitem']->value){
$_smarty_tpl->tpl_vars['gitem']->_loop = true;
?>
<div id='tab<?php echo $_smarty_tpl->tpl_vars['gitem']->value->id;?>
'>
<table width="100%" border="1px">
  <tbody>
		<tr>
			<th>
				ID
			</th>
			<th width="25%">
				Menu
			</th>
			<th>
				Alternate Title
			</th>
			<th>
				URI
			</th>
			<th>
				Position
			</th>
			<th>
				Active
			</th>
			<th>
				Actions
			</th>
		</tr>
<?php  $_smarty_tpl->tpl_vars['mitem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mitem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['mitem']->key => $_smarty_tpl->tpl_vars['mitem']->value){
$_smarty_tpl->tpl_vars['mitem']->_loop = true;
?>
<?php if ($_smarty_tpl->tpl_vars['mitem']->value->parent_id==$_smarty_tpl->tpl_vars['gitem']->value->id){?>
		<tr>
			<td valign="top">
				<?php echo $_smarty_tpl->tpl_vars['mitem']->value->id;?>
<br />
			</td>
			<td valign="top">
				<?php echo $_smarty_tpl->tpl_vars['mitem']->value->title;?>
<br>
			</td>
			<td valign="top">
				<?php echo $_smarty_tpl->tpl_vars['mitem']->value->AltTitle;?>

			</td>
			<td valign="top">
				<?php echo $_smarty_tpl->tpl_vars['mitem']->value->uri;?>

			</td>
			<td valign="top">
				<?php echo $_smarty_tpl->tpl_vars['mitem']->value->pos;?>

			</td>
			<td valign="top">
				<?php echo $_smarty_tpl->tpl_vars['mitem']->value->active;?>

			</td>
			<td valign="top">
<?php if ($_smarty_tpl->tpl_vars['mitem']->value->id!=0){?>
				<a href="index.php?mod=Menu&act=edit&mid=<?php echo $_smarty_tpl->tpl_vars['mitem']->value->id;?>
">Edit</a> | <a href="index.php?mod=Menu&act=remove&mid=<?php echo $_smarty_tpl->tpl_vars['mitem']->value->id;?>
">Delete</a>
<?php }?>
			</td>
<?php }?>
<?php } ?>
		</tr>
	</tbody>
</table>
</div>
<?php } ?>
<!-- END OF ADMINCP_PLUGINS_ROW-->
<a href="index.php?mod=Menu&act=add"><input name="login" type="submit" class="button" value="Add New.." /></a>

<?php echo $_smarty_tpl->getSubTemplate ("admin/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>