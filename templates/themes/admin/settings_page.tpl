{include file="file:[$THEME]header.tpl" TITLE="Plugin/Module Admin"}

<!-- START OF ADMINCP_PLUGINS_ROW-->
{if $error != 0}
<b>You have an error with a code of {$error}:</b>
<b>{$errormsg}</b>
<br />
{/if}
<div>
		<form method="post" action="index.php?mod=Settings&act=save">
<table width="100%" border="1px">
  <tbody>
		<tr>
			<th width="25%">
				{$GROUP}
			</th>
		</tr>
{foreach from=$settings item=sitem}
		{if $sitem->visible == 1}
		<tr>
			<td valign="top">
				<strong>{$sitem->title}</strong><br>
				{$sitem->description}<br>
				{if $sitem->optionscode == 'text'}
					<input name="sid{$sitem->id}" type='text' value="{$sitem->value}"></input>
				{/if}
				
				{if $sitem->optionscode == 'select'}
					<select name="sid{$sitem->id}">
						{foreach from=$allSettings item=subitem}
						{if $sitem->id == $subitem->gid}
					<option value="{$subitem->id}" {if $subitem->id == $sitem->value} selected{/if}>{$subitem->title} - {$subitem->value}</option>
						{/if}
						{/foreach}
					</select>
				{/if}
				
				{if $sitem->optionscode == 'radio'}
					{foreach from=$allSettings item=subitem}
					{if $sitem->id == $subitem->gid}
					<input name="sid{$sitem->id}" type='radio' name='setting{$sitem->id}' value="{$subitem->id}" {if $subitem->id == $sitem->value} checked{/if}>{$subitem->value}</input>
					{/if}
					{/foreach}
				{/if}
			</td>
		</tr>
		{/if}
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
