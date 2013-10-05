{include file="file:[$THEME]header.tpl" TITLE="Members"}
<div class="panel panel-default">
<div class="panel-heading">Members</div>
<table width="90%" class="table">
  <tr>
    <th style="text-align: left;">Username</th>
    <th style="text-align: left;">Level</th>
  </tr>

{foreach from=$members item=member}
  <tr>
    <td>{$member->username}</td>
    <td>{$member->level}</td>
  </tr>
{/foreach}
</table>
<div class="panel-footer">
	<strong>
		<a href="index.php?mod=Members&page={$prevpage}">Previous Page</a> | <a href="index.php?mod=Members&page={$nextpage}">Next Page</a>
	</strong>	
</div>
</div>
<span class="space"></span>

<span style="display: block; width: 90%; text-align: center;">
<strong>

</strong>
</span>

{include file="file:[$THEME]footer.tpl"}