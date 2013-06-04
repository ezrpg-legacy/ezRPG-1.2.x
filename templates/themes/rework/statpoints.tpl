{include file="[$THEME]header.tpl" TITLE="Stat Points"}

<h1>Stat Points</h1>

{if isset($MSG_GOOD)}
	<span class="msg good">{$MSG_GOOD}</span>
{/if}

<p>
Here you can use your stat points to increase your stats! You have <strong>{$player->stat_points}</strong> points to use!
<br /><br />
You receive stat points when you first sign up to the game, and also each time when you level up!
</p>

<form method="post" action="index.php?mod=StatPoints">
<input type="submit" class="button" name="stat" value="Strength" />
</form>

<p>
<strong>Strength</strong> - This increases the damage you do in battle, and increases your weight limit so you can carry more items.
</p>

<form method="post" action="index.php?mod=StatPoints">
<input type="submit" class="button" name="stat" value="Vitality" />
</form>

<p>
<strong>Vitality</strong> - This increases the amount of health you have and decreases the amount of damage you receive in battle.
</p>

<form method="post" action="index.php?mod=StatPoints">
<input type="submit" class="button" name="stat" value="Agility" />
</form>

<p>
<strong>Agility</strong> - This increases your chance to completely dodge and attack and take no damage in battle!
</p>

<form method="post" action="index.php?mod=StatPoints">
<input type="submit" class="button" name="stat" value="Dexterity" />
</form>

<p>
<strong>Dexterity</strong> - This helps you aim better so you are less likely to miss your opponent.
</p>

{include file="[$THEME]footer.tpl"}