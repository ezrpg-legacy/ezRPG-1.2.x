{include file="[$THEME]header.tpl" TITLE="Home"}

<h1>Home</h1>

{if isset($MSG_WARN)}
    <span class="msg info">{$MSG_WARN}</span>
    <br />
{elseif isset($MSG_FAIL)}
    <span class="msg fail">{$MSG_FAIL}</span>
    <br />
{elseif isset($MSG_GOOD)}
    <span class="msg good">{$MSG_GOOD}</span>
    <br />
{/if}
<div class="left">
    <p>
        Welcome to ezRPG! Login now!
    </p>
</div>

<div class="right">
    <form method="post" action="index.php?mod=Login">
        <label for="username">Username</label>
        <input type="text" name="username" />

        <label for="password">Password</label>
        <input type="password" name="password" />
		
        <input name="login" type="submit" class="button" value="Login">
    </form>
</div>

{include file="[$THEME]footer.tpl"}