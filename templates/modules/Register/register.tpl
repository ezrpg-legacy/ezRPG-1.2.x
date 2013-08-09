{include file="file:[$THEME]header.tpl" TITLE="Register"}

<h1>Register</h1>

<p>
Want to join the fun? Fill out the form below to register!
</p>

<form method="POST" action="index.php?mod=Register">

<label>Username</label>
<input type="text" size="40" name="username"{if $GET_USERNAME != ""} value="{$GET_USERNAME}"{/if} />

<label>Password</label>
<input type="password" size="40" name="password" />

<label>Verify Password</label>
<input type="password" size="40" name="password2" />

<label>Email</label>
<input type="text" size="40" name="email"{if $GET_EMAIL != ""} value="{$GET_EMAIL}"{/if} />

<label>Verify Email</label>
<input type="text" size="40" name="email2"{if $GET_EMAIL2 != ""} value="{$GET_EMAIL2}"{/if} />

<label>Enter The Code</label>
<img src="./captcha.php" /><br />
<input type="text" size="40" name="reg_verify" autocomplete="off" />

<br />
<input name="register" type="submit" value="Register!" class="button" />
</form>

{include file="file:[$THEME]footer.tpl"}