{include file="file:[$THEME]header.tpl" TITLE="Plugin/Module Admin"}

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
{foreach from=$plugins item=plugin}
<tr>
<td valign="top">
{$plugin->title} | Version: {$plugin->version}<br>
<i>{$plugin->description}</i><br>
Created By: <a href="{$plugin->authorsite}">{$plugin->author}</a>
</td>
<td valign="top">
{if $plugin->id != 1 }
<a href="#">Edit</a> | <a href="#">Deactivate</a> | <a href="#">Uninstall</a> | <a href="#">Export</a>
{/if}
</td>
</tr>
{/foreach}
<!-- END OF ADMINCP_PLUGINS_ROW-->
</tbody></table>
<a href="index.php?mod=Plugins&act=upload"><input name="login" type="submit" class="button" value="Upload New.." /></a>
{include file="file:[$THEME]footer.tpl"}
