{include file="admin/header.tpl" TITLE="Members Admin"}

<h1>Members</h1>

<p>
<strong>Total Players: </strong> {$playercount}
</p>

<table width="100%">
  <tr>
    <th style="text-align: left;">Username</th>
    <th style="text-align: left;">Email</th>
    <th style="text-align: left;">Actions</th>
  </tr>

{foreach from=$members item=member}
  <tr>
    <td>{$member->username}</td>
    <td><a href="mailto:{$member->email}">{$member->email}</a></td>
    <td>
      <a href="index.php?mod=Members&act=edit&id={$member->id}"><strong>Edit</strong></a> | <a href="index.php?mod=Members&act=delete&id={$member->id}"><strong>Delete</strong>
    </td>
  </tr>
{/foreach}
</table>

<span class="space"></span>

<span style="display: block; width: 90%; text-align: center;">
<strong>
<a href="index.php?mod=Members&page={$prevpage}">Previous Page</a>
|
<a href="index.php?mod=Members&page={$nextpage}">Next Page</a>
</strong>
</span>

{include file="admin/footer.tpl"}