{include file="file:[$THEME]header.tpl" TITLE="Admin"}
<h1>Admin</h1>
<h2>Admin Modules</h2>
<ul>
	{foreach from=$MENU_AdminModules item=menu key=menukey} 
	<li><a href={$menu}>{$menukey}</a></li>
	{/foreach}
</ul>


{include file="file:[$THEME]footer.tpl"}
