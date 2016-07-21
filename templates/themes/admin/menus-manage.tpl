{include file="file:[$THEME]header.tpl" TITLE="Plugin/Module Admin"}
{if $page eq "edit"}
<!-- START OF ADMINCP_PLUGINS_ROW-->
{if $error != 0}
<b>You have an error with a code of {$error}:</b>
<b>{$errormsg}</b>
<br />
{/if}
<form action="index.php?mod=Menu" method="post" id="menu">
<label for="mname">Menu Name:</label>
<input type="text" name="mname" value="{$mitem->name}" required="true" />
<label for="mpid">Menu Parent_ID:</label>
<select name="mpid" required="true" form="menu">
<option value="0">Create A Group</option>
{foreach from=$menubox item=mitm}
{if $mitem->parent_id eq $mitm->id} 
 <option value="{$mitm->id}" selected> 
 {else}
 <option value="{$mitm->id}">
 {/if}
 {$mitm->title}
 </option>
{/foreach}
  </select>
<label for="mtitle">Menu Title:</label>
<input type="text" name="mtitle" value="{$mitem->title}" required="true" />
<label for="malt">Menu Alternate Title:</label>
<input type="text" name="malt" value="{$mitem->AltTitle}" />
<label for="mpos">Menu Position:</label>
<input type="text" name="mpos" value="{$mitem->pos}" />
<label for="muri">Menu URI:</label>
<input type="text" name="muri" value="{$mitem->uri}" />
<label for="mactive">Active?:</label>
<select name="mactive">
<option value="0" {if $mitem->active eq 0} selected {/if} >Disabled</option>
<option value="1" {if $mitem->active eq 1} selected {/if} >Enabled</option>
</select>
<label for="modid">Required Module:</label>
<select name="modid" required="true" form="menu">
    <option value="0">None</option>
    {foreach from=$plugins item=plug}
        {if $mitem->module_id eq $plug->id}
            <option value="{$plug->id}" selected>
                {else}
            <option value="{$plug->id}">
        {/if}
        {$plug->title}
        </option>
    {/foreach}
</select>
<br />
<input type="hidden" name="mid" value="{$mitem->id}" />
<input type="hidden" name="mod" value="Menu" />
<input type="hidden" name="act" value="edit" />
<input type="submit" class="button" name="submit" value="Save!" />
</form>
<!-- END OF ADMINCP_PLUGINS_ROW-->
{elseif $page eq "delete"}
{if $error == FALSE}
<p>Are you sure you want to delete '{$menuname}' menu?</p>
<a href="index.php?mod=Menu&act=remove&confirm=1&mid={$menuid}"><input name="login" type="submit" class="button" value="Yes" /></a>
<a href="index.php?mod=Menu"><input name="login" type="submit" class="button" value="No" /></a>
{else}
<p>Sorry but you cannot delete a Menu Group without first deleting it's children!</p>
<a href="index.php?mod=Menu"><input name="login" type="submit" class="button" value="Menu Manager" /></a>
{/if}
{elseif $page eq "add"}
{if $error != 0}
<b>You have an error with a code of {$error}</b>
<b>{$errormsg}</b>
<br />
{/if}
<form action="index.php?mod=Menu" method="post" id="menu">
<label for="mname">Menu Name:</label>
<input type="text" name="mname" value="{$mname}" required="true" />
<label for="mpid">Menu Parent_ID:</label>
<select name="mpid" required="true" form="menu">
<option value="0">Create A Group</option>
{foreach from=$menus item=mitem}
    <option value="{$mitem->id}">
        {$mitem->title} |
        {if $mitem->parent_id eq 0 }
            Menu Group
        {else}
            {foreach from=$groups item=$grp}
                {if $grp->id eq $mitem->parent_id}
                    {$grp->name}
                {/if}
            {/foreach}
        {/if}
    </option>
{/foreach}

  </select>
<label for="mtitle">Menu Title:</label>
<input type="text" name="mtitle" value="{$mtitle}" required="true" />
<label for="malt">Menu Alternate Title:</label>
<input type="text" name="malt" value="{$malt}" />
<label for="mpos">Menu Position:</label>
<input type="text" name="mpos" value="{$mpos}" />
<label for="muri">Menu URI:</label>
<input type="text" name="muri" value="{$muri}" />
<label for="modid">Required Module:</label>
<select name="modid" required="true" form="menu">
    <option value="0">None</option>
    {foreach from=$plugins item=plug}
        <option value="{$plug->id}">
            {$plug->title}
        </option>
    {/foreach}
</select>
<br />
<input type="hidden" name="mod" value="Menu" />
<input type="hidden" name="act" value="add" />
<input type="submit" class="button" name="submit" value="Create!" />
</form>
{/if}
{include file="file:[$THEME]footer.tpl"}
