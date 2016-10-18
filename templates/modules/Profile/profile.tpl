{include file="file:[$THEME]header.tpl" TITLE="Account Settings"}

<h1>Profile</h1>

{foreach from=$profile item=user}
    <h3><b>{$user->username}</b></h3>
    <br>
    {if $avatar eq true}
        {$user->avatar}
    {/if}
    <span class="space" />
    <div class="left">
        <p>
            <strong>Kills/Deaths</strong>: {$user->kills}/{$user->deaths}<br />
            <strong>Level</strong>: {$user->level}<br />
            <strong>Gold</strong>: {$user->money}<br />
        </p>
    </div>


    <div class="right">
        <p>
            <strong>Strength</strong>: {$user->strength}<br />
            <strong>Vitality</strong>: {$user->vitality}<br />
            <strong>Agility</strong>: {$user->agility}<br />
            <strong>Dexterity</strong>: {$user->dexterity}<br />
        </p>
    </div>
{/foreach}

{include file="file:[$THEME]footer.tpl"}