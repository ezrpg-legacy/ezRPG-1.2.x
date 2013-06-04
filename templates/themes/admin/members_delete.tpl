{include file="file:[$THEME]header.tpl" TITLE="Members Admin"}

<h1>Delete Member</h1>

<p>
Are you sure you want to delete <strong>{$member->username}</strong>?
</p>

<form method="post" action="index.php?mod=Members&act=delete&id={$member->id}">

<input type="submit" name="confirm" value="Delete" />

</form>

{include file="file:[$THEME]footer.tpl"}