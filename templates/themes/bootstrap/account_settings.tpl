{include file="file:[$THEME]header.tpl" TITLE="Account Settings"}

<h1>Account Settings</h1>

<p>
Here you can change your password.
</p>

<form method="post" action="index.php?mod=AccountSettings" class="form" role="form">
<div class="form-group">
	<label>Current Password</label>
	<div class="col-lg-6">
		<input type="password" size="40" name="current_password" autocomplete="off" />
	</div>
</div>
<div class="form-group">
<label>New Password</label>
<div class="col-lg-6">
<input type="password" size="40" name="new_password" autocomplete="off" />
	</div>
</div>
<div class="form-group">
<label>Verify New Password</label>
<div class="col-lg-6">
<input type="password" size="40" name="new_password2" autocomplete="off" />
	</div>
</div>
<input name="change_password" type="submit" value="Change Password" class="btn btn-submit" />

</form>

{include file="file:[$THEME]footer.tpl"}