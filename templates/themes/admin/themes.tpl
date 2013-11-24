{include file="file:[$THEME]header.tpl" TITLE="Themes Admin"}

<!-- START OF ADMINCP_PLUGINS_ROW-->
<div>
<table width="100%" border="1px">
  <tbody>
		<tr>
			<th width="25%">
				Themes
			</th>
			<th width="1%">
				Enabled?
			</th>
		</tr>
<form method="post" action="index.php?mod=ThemeManager&act=save">
{foreach from=$groups item=gitem}
		<tr>
			<td>
				Theme : {$gitem->name}
			</td>
			<td>
				 <input type="radio" name="themes" value={$gitem->id} {if $gitem->enabled == 1} checked{/if} />
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

{include file="file:[$THEME]footer.tpl"}
