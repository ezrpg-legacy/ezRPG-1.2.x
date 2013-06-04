{include file="file:[$THEME]header.tpl" TITLE="Admin"}
<h1>Admin</h1>
<h2>Admin Modules</h2>
<ul>
	{foreach from=$TOP_MENU_AdminModules item=menu key=menukey} 
	<li><a href={$menu}>{$menukey}</a></li>
	{/foreach}
	</ul>
<p>
If you install extra admin modules, edit <em>smarty/templates/admin/index.tpl</em> to add links above.
</p>

{include file="admin/footer.tpl"}
