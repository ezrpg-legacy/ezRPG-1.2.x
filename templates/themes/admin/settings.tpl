{include file="file:[$THEME]header.tpl" TITLE="Plugin/Module Admin"}

<!-- START OF ADMINCP_PLUGINS_ROW-->
{if $error != 0}
<b>You have an error with a code of {$error}:</b>
<b>{$errormsg}</b>
<br />
{/if}
<div>
<table width="100%" border="1px">
  <tbody>
		<tr>
			<th width="25%">
				Setting Groups
			</th>
		</tr>
{foreach from=$groups item=gitem}
			<td valign="top">
				<a href="index.php?mod=Settings&act=getGroup&gid={$gitem->id}">{$gitem->title}</a><br>
			</td>
{/foreach}
		</tr>
	</tbody>
</table>
</div>

<!-- END OF ADMINCP_PLUGINS_ROW-->
<a href="index.php?mod=Menu&act=add"><input name="login" type="submit" class="button" value="Add New.." /></a>

{include file="file:[$THEME]footer.tpl"}
