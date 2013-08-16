{include file="file:[$THEME]header.tpl" TITLE="Sell {$class}"}

<h1>Buy {$class}</h1> 
<a href="index.php?mod=Items&act=Shop">Back</a>

<table width="90%">
  <tr>
    <th style="text-align: left;">Name</th>
    <th style="text-align: left;">Values</th>
    <th style="text-align: left;">Price</th>
    <th style="text-align: left;">Action</th>
  </tr>

{foreach from=$items item=itm}
  <tr>
    <td>{$itm->name}</td>
    <td>{if $itm->strength neq "0"} Strength: {$itm->strength}<br /> {/if}
    {if $itm->vitality neq "0"} Vitality: {$itm->vitality}<br /> {/if}
    {if $itm->agility neq "0"} Agility: {$itm->agility}<br /> {/if}
    {if $itm->dexterity neq "0"} Dexterity: {$itm->dexterity}<br /> {/if}
    {if $itm->max_hp neq "0"} HP: {$itm->max_hp}<br /> {/if}
    {if $itm->hp neq "0"} Heals HP: {$itm->hp}<br /> {/if}
    {if $itm->max_energy neq "0"} Energy: {$itm->max_energy}<br /> {/if}
    {if $itm->energy neq "0"} Regenerate Energy: {$itm->energy}<br /> {/if}
    {if $itm->times_useable neq "0"} Times usable: {$itm->times_useable}<br /> {/if}
    {if $itm->damage neq "0"} Damage: {$itm->damage}<br /> {/if}</td>
    <td>{$itm->sell_price}</td>
    <td><a href="index.php?mod=Items&act=dosell&class={$itm->class}&id={$itm->id}">Sell</a></td>
  </tr>
{/foreach}
</table>

<span class="space"></span>

<span style="display: block; width: 90%; text-align: center;">
<strong>
{if $curpage gt "0"} <a href="index.php?mod=Items&act=buy&class={$class}&page={$prevpage}">Previous Page</a> {/if}
{if $curpage gt "0" and $curpage lt $maxpages} | {/if} 
{if $curpage lt $maxpages} <a href="index.php?mod=Items&act=buy&class={$class}&page={$nextpage}">Next Page</a> {/if}
</strong>
</span>

{include file="file:[$THEME]footer.tpl"}