{include file="file:[$THEME]header.tpl" TITLE="Plugin/Module Admin"}

<!-- START OF ADMINCP_PLUGINS_ROW-->
{if $error != 0}
<b>You have an error with a code of {$error}:</b>
<b>{$errormsg}</b>
<br />
{/if}
<div>
<form method="post" action="index.php?mod=Themes&act=save">
<table width="100%" border="1px">
  <tbody>
{foreach from=$themes item=sitem}
		<tr>
			<th width="25%">
				{$sitem->name}
			</th>
		</tr>
		<tr>
			<td valign="top">
				<strong>Enabled:</strong>
				<select name=enabled>
					<option value=1 {if $sitem->enabled == 1} selected{/if}>Enable</option>
					<option value=0 {if $sitem->enabled == 0} selected{/if}>Disable</option>
				</select>
			</td>
		</tr>
	
{/foreach}

	</tbody>
</table>

</div>
<input type="hidden" name="act" value="save" />
<input type="submit" class="button" value="Save Settings" name='save' />
</form>
<!-- END OF ADMINCP_PLUGINS_ROW-->
<a href="index.php?mod=Menu&act=add"><input name="login" type="submit" class="button" value="Add New.." /></a>

{include file="file:[$THEME]footer.tpl"}
