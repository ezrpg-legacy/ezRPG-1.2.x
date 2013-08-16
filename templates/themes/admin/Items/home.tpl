{include file="file:[$THEME]header.tpl" TITLE="Items"}
<h1>Items</h1>
<table width="90%"><tr>
<th style="text-align: left;"><h2><a href="index.php?mod=Items&act=class">Add/Edit Class</a></h2></th>
<th style="text-align: left;"><h2>Add/Edit Item</h2></th>
	{foreach from=$class item=itm} 
	<tr><td></td><td><a href="index.php?mod=Items&act=item&classid={$itm->class_id}">{$itm->class}</a></td></tr>
	{/foreach}
</table>

{include file="file:[$THEME]footer.tpl"}
