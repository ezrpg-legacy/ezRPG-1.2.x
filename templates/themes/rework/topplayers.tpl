{include file="[$THEME]header.tpl" TITLE="Top 10 Players"}
 
<h2>Top 10 Players</h2>
 
<table width="90%">
  <tr>
    <th style="text-align: left;">Username</th>
    <th style="text-align: left;"><a href="index.php?mod=TopPlayers&order=level">Level</a></th>
    <th style="text-align: left;"><a href="index.php?mod=TopPlayers&order=money">Money</a></th>
  </tr>
 
{foreach from=$members item=member}
  <tr>
    <td>{$member->username}</td>
    <td>{$member->level}</td>
    <td>{$member->money}</td>
  </tr>
{/foreach}
</table>
 
{include file="[$THEME]footer.tpl"}
