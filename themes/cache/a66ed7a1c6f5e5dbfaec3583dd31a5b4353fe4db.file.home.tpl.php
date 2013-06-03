<?php /* Smarty version Smarty-3.1.8, created on 2013-05-29 17:50:24
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/smarty/templates\home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1496251a61c5cb6b0e0-55239853%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a66ed7a1c6f5e5dbfaec3583dd31a5b4353fe4db' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/smarty/templates\\home.tpl',
      1 => 1369842624,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1496251a61c5cb6b0e0-55239853',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51a61c5cc16c69_74814728',
  'variables' => 
  array (
    'player' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a61c5cc16c69_74814728')) {function content_51a61c5cc16c69_74814728($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120\\lib\\ext\\smarty\\plugins\\modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Home"), 0);?>


<h1>Home</h1>

<div class="left">
<p>
<strong>Username</strong>: <?php echo $_smarty_tpl->tpl_vars['player']->value->username;?>
<br />
<strong>Email</strong>: <?php echo $_smarty_tpl->tpl_vars['player']->value->email;?>
<br />
<strong>Registered</strong>: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['player']->value->registered,'%B %e, %Y %l:%M %p');?>
<br />
<strong>Kills/Deaths</strong>: <?php echo $_smarty_tpl->tpl_vars['player']->value->kills;?>
/<?php echo $_smarty_tpl->tpl_vars['player']->value->deaths;?>
<br />
<br />
<?php if ($_smarty_tpl->tpl_vars['player']->value->stat_points>0){?>
You have stat points left over!<br />
<a href="index.php?mod=StatPoints"><strong>Spend them now!</strong></a>
<?php }else{ ?>
You have no extra stat points to spend.
<?php }?>
</p>
</div>


<div class="right">
<p>
<strong>Level</strong>: <?php echo $_smarty_tpl->tpl_vars['player']->value->level;?>
<br />
<strong>Gold</strong>: <?php echo $_smarty_tpl->tpl_vars['player']->value->money;?>
<br />
<br />
<strong>Strength</strong>: <?php echo $_smarty_tpl->tpl_vars['player']->value->strength;?>
<br />
<strong>Vitality</strong>: <?php echo $_smarty_tpl->tpl_vars['player']->value->vitality;?>
<br />
<strong>Agility</strong>: <?php echo $_smarty_tpl->tpl_vars['player']->value->agility;?>
<br />
<strong>Dexterity</strong>: <?php echo $_smarty_tpl->tpl_vars['player']->value->dexterity;?>
<br />
</p>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>