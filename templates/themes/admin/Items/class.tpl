{include file="file:[$THEME]header.tpl" TITLE="Class"}
<h1>Class</h1>
<a href="index.php?mod=Items&act=home">Back</a>
<table width="90%"><tr>
<th style="text-align: left;"><h2><h2><a href="index.php?mod=Items&act=addclass">Add Class</a></h2></h2></th>
<th style="text-align: left;"><h2>Edit Class</h2></th>
<th style="text-align: left;"><h2>Delete Class</h2></th></tr>
<ul>
	{foreach from=$class item=itm} 
	<tr><td></td><td><a href="index.php?mod=Items&act=editclass&id={$itm->class_id}">Edit {$itm->class}</a></td> 
  <td><a href="index.php?mod=Items&act=editclass&id={$itm->class_id}">Delete {$itm->class}</a></td></tr>
	{/foreach}
</ul>
</table>

{include file="file:[$THEME]footer.tpl"}
