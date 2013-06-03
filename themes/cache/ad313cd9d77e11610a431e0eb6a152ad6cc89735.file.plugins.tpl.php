<?php /* Smarty version Smarty-3.1.8, created on 2013-05-30 02:03:46
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/smarty/templates\admin\plugins.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2093851a697622b8fd5-72092013%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ad313cd9d77e11610a431e0eb6a152ad6cc89735' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/smarty/templates\\admin\\plugins.tpl',
      1 => 1369838357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2093851a697622b8fd5-72092013',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'INSTALLED' => 0,
    'plugins' => 0,
    'plugin' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51a69762347f40_22102917',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a69762347f40_22102917')) {function content_51a69762347f40_22102917($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("admin/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Plugin/Module Admin"), 0);?>

<?php if ($_smarty_tpl->tpl_vars['INSTALLED']->value==false){?>
DO YOU WISH TO INSTALL THE PLUGIN MANAGER?
<a href="index.php?mod=Plugins&act=install">Yes</a> or <a href="index.php?mod=Index">No</a>
<?php }else{ ?>
<table width="100%" border="1">
<tbody><tr>
<th width="75%">
Plugin
</th>
<th>
Actions
</th>
</tr>
<!-- START OF ADMINCP_PLUGINS_ROW-->
<?php  $_smarty_tpl->tpl_vars['plugin'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['plugin']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['plugins']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['plugin']->key => $_smarty_tpl->tpl_vars['plugin']->value){
$_smarty_tpl->tpl_vars['plugin']->_loop = true;
?>
<tr>
<td valign="top">
<?php echo $_smarty_tpl->tpl_vars['plugin']->value->title;?>
<br>
<i><?php echo $_smarty_tpl->tpl_vars['plugin']->value->description;?>
</i><br>
Created By: <a href="<?php echo $_smarty_tpl->tpl_vars['plugin']->value->authorsite;?>
"><?php echo $_smarty_tpl->tpl_vars['plugin']->value->author;?>
</a>
</td>
<td valign="top">
<?php if ($_smarty_tpl->tpl_vars['plugin']->value->id!=1){?>
<a href="#">Edit</a> | <a href="#">Deactivate</a> | <a href="#">Uninstall</a> | <a href="#">Export</a>
<?php }?>
</td>
</tr>
<?php } ?>
<!-- END OF ADMINCP_PLUGINS_ROW-->
</tbody></table>
<a href="index.php?mod=Plugins&act=upload"><input name="login" type="submit" class="button" value="Upload New.." /></a>
<?php }?>
<?php echo $_smarty_tpl->getSubTemplate ("admin/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>