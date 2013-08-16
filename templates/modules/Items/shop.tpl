{include file="file:[$THEME]header.tpl" TITLE="Shop"}

<h1>ItemShop</h1>

<div class="left">

	<h2><a href="index.php?mod=Items&act=Inventory">Inventory</a></h2>
	<h2>Buy</h2>
  {foreach from=$buy item=itm}
  <a href="index.php?mod=Items&act=buy&class={$itm->class}">{$itm->class}</a><br />
  {/foreach}
  <h2>Sell</h2>
  {foreach from=$sell item=itm}
  <a href="index.php?mod=Items&act=sell&class={$itm->class}">{$itm->class}</a><br />
  {/foreach}
</div>

{include file="file:[$THEME]footer.tpl"}