{include file="file:[$THEME]header.tpl" TITLE="Plugin/Module Admin"}

<!-- START OF ADMINCP_PLUGINS_ROW-->
{if $error != 0}
<b>You have an error with a code of {$error}:</b>
<b>{$errormsg}</b>
<br />
{/if}
<ul class='tabs'>
{foreach from=$groups item=gitem}
    <li><a href='#tab{$gitem->id}'><input type="submit" class="buton" value="{$gitem->title}"></a></li>
{/foreach}
</ul>
{foreach from=$groups item=gitem}
<div id='tab{$gitem->id}'>
<table width="100%" border="1px">
  <tbody>
		<tr>
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
				Plugins
			</th>
			<th>
				Actions
			</th>
		</tr>
{foreach from=$menus item=mitem}
{if $mitem->parent_id eq $gitem->id}
		<tr>
			<td valign="top">
				{$mitem->title}<br>
			</td>
			<td valign="top">
				{$mitem->AltTitle}
			</td>
			<td valign="top">
				{$mitem->uri}
			</td>
			<td valign="top">
				{$mitem->pos}
			</td>
			<td valign="top">
				{$mitem->active}
			</td>
			<td valign="top">
				{foreach from=$plugins item=$plug}
					{if $plug->id eq $mitem->module_id}
						{$plug->title}
					{/if}
				{/foreach}
			</td>
			<td valign="top">
{if $mitem->id != 0 }
				<a href="index.php?mod=Menu&act=edit&mid={$mitem->id}">Edit</a> | <a href="index.php?mod=Menu&act=remove&mid={$mitem->id}">Delete</a>
{/if}
			</td>
{/if}
{/foreach}
		</tr>
	</tbody>
</table>
</div>
{/foreach}
<!-- END OF ADMINCP_PLUGINS_ROW-->
<a href="index.php?mod=Menu&act=add"><input name="login" type="submit" class="button" value="Add New.." /></a> <a href="index.php?mod=Menu&act=clean"><input name="login" type="submit" class="button" value="Clear Cache" /></a>

{include file="file:[$THEME]footer.tpl"}
