<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_AccountSettings
  Lets the user change their password.
*/
class Module_AccountSettings extends Base_Module
{
    /*
      Function: start
      Begins the account settings page/
    */
    public function start()
    {
        //Require login
        requireLogin();
        
        if (isset($_POST['change_password']))
        {
            $this->changePassword();
        }
        else
        {
            $this->loadView('account_settings.tpl');
        }
    }

    private function changePassword()
    {
        $msg = '';
        if (empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['new_password2']))
        {
            $msg = 'You forgot to fill in something!';
        }
        else
        {
            $check = sha1($this->player->secret_key . $_POST['current_password'] . SECRET_KEY);
            if ($check != $this->player->password)
            {
                $msg = 'The password you entered does not match this account\'s password.';
            }
            else if (!isPassword($_POST['new_password']))
            {
                $msg = 'Your password must be longer than 3 characters!';
            }
            else if ($_POST['new_password'] != $_POST['new_password2'])
            {
                $msg = 'You didn\'t confirm your new password correctly!';
            }
            else
            {
                $new_password = sha1($this->player->secret_key . $_POST['new_password2'] . SECRET_KEY);
                $this->db->execute('UPDATE `<ezrpg>players` SET `password`=? WHERE `id`=?', array($new_password, $this->player->id));
                $msg = 'You have changed your password.';
            }
        }
        
        header('Location: index.php?mod=AccountSettings&msg=' . urlencode($msg));
    }
}
?>