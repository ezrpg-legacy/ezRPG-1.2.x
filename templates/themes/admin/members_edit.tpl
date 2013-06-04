{include file="file:[$THEME]header.tpl" TITLE="Members Admin"}

<h1>Edit Member</h1>

<form method="post" action="index.php?mod=Members&act=edit&id={$member->id}">

<label>Username</label>
<input type="text" disabled="disabled" value="{$member->username}" />

<label>Email</label>
<input type="text" name="email" value="{$member->email}" />

<label>Rank</label>
<input type="text" name="rank" value="{$member->rank}" />
<p>
If the player has rank 5 or higher, the player will be able to access the admin panel.
</p>

<label>Money</label>
<input type="text" name="money" value="{$member->money}" />

<label>Level</label>
<input type="text" name="level" value="{$member->level}" />

<br />
<input class="button" type="submit" value="Edit" name="edit" />

</form>

{include file="file:[$THEME]footer.tpl"}