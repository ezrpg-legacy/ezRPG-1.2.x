<?php

namespace ezRPG\Modules;

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

        if ( isset($_POST['change_password']) )
        {
            $this->changePassword();
        }
        else
        {
            $this->loadView('account_settings.tpl', 'Account_Settings');
        }
    }

    private function changePassword()
    {
        $msg = '';
        if ( empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['new_password2']) )
        {
            $msg = 'You forgot to fill in something!';
        }
        else
        {
            $player_check = $this->db->fetchRow('SELECT id, password, secret_key FROM `<ezrpg>players` WHERE `id` =?', array( $_SESSION['userid'] ));

            $check = checkPassword($player_check->secret_key, $_POST['current_password'], $player_check->password);
            if ( $check !== TRUE )
            {
                $msg = 'The password you entered does not match this account\'s password.';
            }
            else if ( !isPassword($_POST['new_password']) )
            {
                $msg = 'Your password must be longer than 3 characters!';
            }
            else if ( $_POST['new_password'] != $_POST['new_password2'] )
            {
                $msg = 'You didn\'t confirm your new password correctly!';
            }
            else
            {
                $new_password = createPassword($player_check->secret_key, $_POST['new_password']);
                $this->db->execute('UPDATE `<ezrpg>players` SET `password`=? WHERE `id`=?', array( $new_password, $this->player->id ));
                $msg = 'You have changed your password.';
            }
        }
        $this->setMessage($msg);
        header('Location: index.php?mod=AccountSettings');
    }

}

?>