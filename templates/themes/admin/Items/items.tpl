{include file="file:[$THEME]header.tpl" TITLE="Items"}
<h1>Class</h1>
<a href="index.php?mod=Items&act=home">Back</a>
<table width="90%"><tr>
<th style="text-align: left;"><h2><a href="index.php?mod=Items&act=additem&classid={$classid}">Add Item</a></h2></th>
<th style="text-align: left;"><h2>Edit Item</h2></th>
<th style="text-align: left;"><h2>Delete Item</h2></th></tr>
<ul>
	{foreach from=$items item=itm} 
	<tr><td></td>
  <td><a href="index.php?mod=Items&act=edititem&id={$itm->item_id}">Edit {$itm->name}</a></td> 
  <td><a href="index.php?mod=Items&act=deleteitem&id={$itm->item_id}">Delete {$itm->name}</a></td></tr>
	{/foreach}
</ul>
</table>

<span class="space"></span>

<span style="display: block; width: 90%; text-align: center;">
<strong>
{if $curpage gt "0"} <a href="index.php?mod=Items&act=buy&classid={$classid}&page={$prevpage}">Previous Page</a> {/if}
{if $curpage gt "0" and $curpage lt $maxpages} | {/if} 
{if $curpage lt $maxpages} <a href="index.php?mod=Items&act=buy&classid={$classid}&page={$nextpage}">Next Page</a> {/if}
</strong>
</span>

{include file="file:[$THEME]footer.tpl"}
