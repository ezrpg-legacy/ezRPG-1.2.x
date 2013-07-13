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
Created By: <a href="{if $plugin->url eq null}#{else}{$plugin->url}{/if}">{$plugin->author}</a>
</td>
<td valign="top">
{if $plugin->title != ezRPGCore }
{if $plugin->active != 1}
<a href="index.php?mod=Plugins&act=enable&id={$plugin->id}">Activate</a>
{else}
<a href="index.php?mod=Plugins&act=disable&id={$plugin->id}">Deactivate</a>
{/if}
 |
 <a href="index.php?mod=Plugins&act=remove&id={$plugin->id}">Uninstall</a>
{/if}
</td>
</tr>
{/foreach}
<!-- END OF ADMINCP_PLUGINS_ROW-->
</tbody></table>
<a href="index.php?mod=Plugins&act=upload"><input name="login" type="submit" class="button" value="Upload New.." /></a>
{include file="file:[$THEME]footer.tpl"}
