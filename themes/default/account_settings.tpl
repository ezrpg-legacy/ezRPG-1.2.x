{include file="header.tpl" TITLE="Account Settings"}

<h1>Account Settings</h1>

<p>
Here you can change your password.
</p>

<form method="post" action="index.php?mod=AccountSettings">

<label>Current Password</label>
<input type="password" size="40" name="current_password" autocomplete="off" />

<label>New Password</label>
<input type="password" size="40" name="new_password" autocomplete="off" />

<label>Verify New Password</label>
<input type="password" size="40" name="new_password2" autocomplete="off" />

<br />
<input name="change_password" type="submit" value="Change Password" class="button" />

</form>

{include file="footer.tpl"}