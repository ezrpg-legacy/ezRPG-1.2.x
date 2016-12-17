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
{foreach from=$groups item=gitem key=gkey}
	{if isset($gitem.visible) && $gitem.visible == 1}
		<tr>
			<td valign="top">
				<a href="index.php?mod=Settings&act=getGroup&group={$gkey}">{$gkey|capitalize}</a><br>
			</td>
		</tr>
    {/if}
{/foreach}
	</tbody>
</table>
</div>

<!-- END OF ADMINCP_PLUGINS_ROW-->

{include file="file:[$THEME]footer.tpl"}
