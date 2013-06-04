{include file="file:[$THEME]header.tpl" TITLE="Plugin/Module Admin"}
{if isset($MSG)}
  <span class="msg info">{$MSG}</span>
{/if}
<form action="index.php?mod=Plugins&act=upload" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>
{include file="file:[$THEME]footer.tpl"}
