{include file="file:[$THEME]header.tpl" TITLE="Add Class"}
<h1>Add Class</h1>
<a href="index.php?mod=Items&act=item&classid={$items->class_id}">Back</a> <br /><br />
<table width="90%"><tr>
<th style="text-align: left;">Description</th>
<th style="text-align: left;">Field</th></tr>
<form action="index.php?mod=Items&act=doedititem&classid={$items->class_id}&id={$items->item_id}" method="post" > 
<tr><td>Name</td> <td><input name="name" type="text" maxlength="50" value={$items->name}></td></tr> 
{if $items->useable eq "0"}<tr><td>Strength</td> <td><input name="strength" type="text" maxlength="50" value={$items->strength}></td></tr> {/if}
{if $items->useable eq "0"}<tr><td>Vitality</td> <td><input name="vitality" type="text" maxlength="50" value={$items->vitality}></td></tr>{/if}
{if $items->useable eq "0"}<tr><td>Agility</td> <td><input name="agility" type="text" maxlength="50" value={$items->agility}></td></tr>{/if}
{if $items->useable eq "0"}<tr><td>Dexterity</td> <td><input name="dexterity" type="text" maxlength="50" value={$items->dexterity}></td></tr>{/if}
{if $items->useable eq "0"}<tr><td>Max HP</td> <td><input name="max_hp" type="text" maxlength="50" value={$items->max_hp}></td></tr>{/if}
{if $items->useable eq "1"}<tr><td>HP</td> <td><input name="hp" type="text" maxlength="50" value={$items->hp}></td></tr>{/if}
{if $items->useable eq "0"}<tr><td>Max Energy</td> <td><input name="max_energy" type="text" maxlength="50" value={$items->max_energy}></td></tr>{/if}
{if $items->useable eq "1"}<tr><td>Energy</td> <td><input name="energy" type="text" maxlength="50" value={$items->energy}></td></tr>{/if}
{if $items->useable eq "1"}<tr><td>Times useable</td> <td><input name="times_useable" type="text" maxlength="50" value={$items->times_useable}></td></tr>{/if}
{if $items->useable eq "0"}<tr><td>Damage</td> <td><input name="damage" type="text" maxlength="50" value={$items->damage}></td></tr>{/if}
<tr><td>Buy Price</td> <td><input name="buy_price" type="text" maxlength="50" value={$items->buy_price}></td></tr>
<tr><td>Sell Price</td> <td><input name="sell_price" type="text" maxlength="50" value={$items->sell_price}></td></tr>
<tr><td><input name="Submit1" type="submit" value="submit"></tr></td>
</form></table>


{include file="file:[$THEME]footer.tpl"}
