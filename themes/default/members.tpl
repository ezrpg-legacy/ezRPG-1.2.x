{include file="header.tpl" TITLE="Members"}

<table width="90%">
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

<span class="space"></span>

<span style="display: block; width: 90%; text-align: center;">
<strong>
<a href="index.php?mod=Members&page={$prevpage}">Previous Page</a> | <a href="index.php?mod=Members&page={$nextpage}">Next Page</a>
</strong>
</span>

{include file="footer.tpl"}