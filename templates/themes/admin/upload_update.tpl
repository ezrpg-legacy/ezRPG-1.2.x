{include file="file:[$THEME]header.tpl" TITLE="Plugin/Module Admin"}
{if isset($MSG)}
  <span class="msg info">{$MSG}</span>
{/if}
<form action="index.php?mod=Update&act=upload" method="post" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file" /><br>
<label for="warning">Updates allow SQL to run without interaction. Be sure you received the update from trusted sources!</label>
<input type="checkbox" name="agree" id="agree" /><br/>
<input type="submit" name="submit" value="Submit">
</form>
{include file="file:[$THEME]footer.tpl"}
