{include file="header.tpl" TITLE="City"}

<h1>City</h1>

<div class="left">

<h3>Player</h3>

<ul>
	{foreach from=$MENU_UserMenu item=menu key=menukey} 
<li><a href={$menu}>{$menukey}</a></li>
	{/foreach}
</ul>
</div>
<div class="right">

<h3>World</h3>

<ul>
	{foreach from=$MENU_WorldMenu item=menu key=menukey} 
<li><a href={$menu}>{$menukey}</a></li>
	{/foreach}
</ul>

<h3>City</h3>

<ul>
	{foreach from=$MENU_City item=menu key=menukey} 
<li><a href={$menu}>{$menukey}</a></li>
	{/foreach}
</ul>



</div>

{include file="footer.tpl"}
