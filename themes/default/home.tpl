{include file="header.tpl" TITLE="Home"}

<h1>Home</h1>

<div class="left">
<p>
<strong>Username</strong>: {$player->username}<br />
<strong>Email</strong>: {$player->email}<br />
<strong>Registered</strong>: {$player->registered|date_format:'%B %e, %Y %l:%M %p'}<br />
<strong>Kills/Deaths</strong>: {$player->kills}/{$player->deaths}<br />
<br />
{if $player->stat_points > 0}
You have stat points left over!<br />
<a href="index.php?mod=StatPoints"><strong>Spend them now!</strong></a>
{else}
You have no extra stat points to spend.
{/if}
</p>
</div>


<div class="right">
<p>
<strong>Level</strong>: {$player->level}<br />
<strong>Gold</strong>: {$player->money}<br />
<br />
<strong>Strength</strong>: {$player->strength}<br />
<strong>Vitality</strong>: {$player->vitality}<br />
<strong>Agility</strong>: {$player->agility}<br />
<strong>Dexterity</strong>: {$player->dexterity}<br />
</p>
</div>

{include file="footer.tpl"}