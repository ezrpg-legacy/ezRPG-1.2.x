{include file="file:[$THEME]header.tpl" TITLE="Update Admin"}
Current Version Installed: {$version}
<table width="100%" border="1">
<tbody><tr>
<th width="75%">
Updates
</th>

</tr>
<!-- START OF ADMINCP_UPDATES_ROW-->

<tr>
<td valign="top">

</td>

<!-- END OF ADMINCP_UPDATE_ROW-->
</tbody></table>
<a href="index.php?mod=UpdateManager&act=upload"><input name="login" type="submit" class="button" value="Upload New.." /></a>
{include file="file:[$THEME]footer.tpl"}
