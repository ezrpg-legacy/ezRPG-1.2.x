{include file="file:[$THEME]header.tpl" TITLE="Members"}

<table width="90%">
  <tr>
    <th style="text-align: left;">Username</th>
    <th style="text-align: left;">Level</th>
    <th style="text-align: left;">Attack</th> <!-- included for PoC purpose 6/29/2016 -->
  </tr>

{foreach from=$members item=member}
  <tr>
    <td>{$member->username}</td>
    <td>{$member->level}</td>
    {include file="file:[$THEME]/Attack/attack.tpl" mem=$member} <!-- included for PoC purpose 6/29/2016 -->
  </tr>
{/foreach}
</table>

<span class="space"></span>

<span style="display: block; width: 90%; text-align: center;">
<strong>
<a href="index.php?mod=Members&page={$prevpage}">Previous Page</a> | <a href="index.php?mod=Members&page={$nextpage}">Next Page</a>
</strong>
</span>

{include file="file:[$THEME]footer.tpl"}