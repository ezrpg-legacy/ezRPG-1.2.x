{include file="file:[$THEME]header.tpl" TITLE="Inventory"}

<table width="90%">
  <tr>
    <th style="text-align: left;">Name</th>
    <th style="text-align: left;">Class</th>
    <th style="text-align: left;">Values</th>
    <th style="text-align: left;">Amount</th>
    <th style="text-align: left;">Action</th>
  </tr>

{foreach from=$playeritems item=itm}
  <tr>
    <td>{$itm->name}</td>
    <td>{$itm->class}</td>
    <td>{if $itm->strength neq "0"} Strength: {$itm->strength}<br /> {/if}
    {if $itm->vitality neq "0"} Vitality: {$itm->vitality}<br /> {/if}
    {if $itm->agility neq "0"} Agility: {$itm->agility}<br /> {/if}
    {if $itm->dexterity neq "0"} Dexterity: {$itm->dexterity}<br /> {/if}
    {if $itm->max_hp neq "0"} HP: {$itm->max_hp}<br /> {/if}
    {if $itm->hp neq "0"} Heals HP: {$itm->hp}<br /> {/if}
    {if $itm->max_energy neq "0"} Energy: {$itm->max_energy}<br /> {/if}
    {if $itm->energy neq "0"} Regenerate Energy: {$itm->energy}<br /> {/if}
    {if $itm->times_useable neq "0"} Times usable: {$itm->times_useable}<br /> {/if}
    {if $itm->in_use gt "0" and $itm->useable eq "1"} Uses left: {$itm->times_useable - $itm->in_use}<br /> {/if}</td>
    <td>{$itm->amount}</td>
    <td>{if $itm->useable eq "0" and $itm->in_use eq "0"} <a href="index.php?mod=Items&act=use&id={$itm->id}">Equip</a> {/if}
    {if $itm->useable eq "0" and $itm->in_use eq "1"} <a href="index.php?mod=Items&act=use&id={$itm->id}">Unequip</a> {/if}
    {if $itm->useable eq "1"} <a href="index.php?mod=Items&act=use&id={$itm->id}">Use</a> {/if}</td>
  </tr>
{/foreach}
</table>

<span class="space"></span>

<span style="display: block; width: 90%; text-align: center;">
<strong>
{if $curpage gt "0"} <a href="index.php?mod=Items&act=Inventory&page={$prevpage}">Previous Page</a> {/if}
{if $curpage gt "0" and $curpage lt $maxpages} | {/if} 
{if $curpage lt $maxpages} <a href="index.php?mod=Items&act=Inventory&page={$nextpage}">Next Page</a> {/if}
</strong>
</span>

{include file="file:[$THEME]footer.tpl"}