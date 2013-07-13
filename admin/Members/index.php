<?php

defined('IN_EZRPG') or exit;

/*
  Class: Admin_Members
  Admin page for managing members
 */

class Admin_Members extends Base_Module
{
    /*
      Function: start
      Displays the list of members or a member edit form.
     */

    public function start()
    {
        if ( isset($_GET['act']) )
        {
            if ( $_GET['act'] == 'edit' )
            {
                $this->editMember();
            }
            else if ( $_GET['act'] == 'delete' )
            {
                $this->deleteMember();
            }
        }
        else
        {
            $this->listMembers();
        }
    }

    /*
      Function: listMembers
      Gets a list of all members and displays them in a paginated format.
     */

    private function listMembers()
    {
        if ( isset($_GET['page']) )
            $page = (intval($_GET['page']) > 0) ? intval($_GET['page']) : 0;
        else
            $page = 0;

        $query = $this->db->execute('SELECT `id`, `username`, `email` FROM `<ezrpg>players` ORDER BY `id` ASC LIMIT ?,50', array( $page * 50 ));

        $members = Array( );
        while ( $m = $this->db->fetch($query) )
        {
            $members[] = $m;
        }

        $query = $this->db->fetchRow('SELECT COUNT(`id`) AS `count` FROM `<ezrpg>players`');
        $total_players = $query->count;

        $prevpage = (($page - 1) >= 0) ? ($page - 1) : 0;

        $this->tpl->assign('nextpage', ++$page);
        $this->tpl->assign('prevpage', $prevpage);
        $this->tpl->assign('playercount', $total_players);
        $this->tpl->assign('members', $members);

        $this->loadView('members.tpl');
    }

    /*
      Function: editMember
      Displays a form to edit a player, and processes the form submissions.
     */

    private function editMember()
    {
        if ( !isset($_GET['id']) )
        {
            header('Location: index.php?mod=Members');
            exit;
        }

        $member = $this->db->fetchRow('SELECT <ezrpg>players.id, <ezrpg>players.username, <ezrpg>players.email, <ezrpg>players_meta.rank, <ezrpg>players_meta.money, <ezrpg>players_meta.level FROM `<ezrpg>players` JOIN `<ezrpg>players_meta` ON <ezrpg>players.id = <ezrpg>players_meta.pid WHERE `id`=?', array( intval($_GET['id']) ));

        //No rows found
        if ( $member == false )
        {
            header('Location: index.php?mod=Members');
            exit;
        }

        //No form was submitted, so just display the edit form
        if ( !isset($_POST['edit']) )
        {
            $this->tpl->assign('member', $member);
            $this->loadView('members_edit.tpl');
            exit;
        }

        $msg = '';
        $errors = 0;
        //Form was submitted! \o/
        if ( empty($_POST['email']) )
        {
            $errors = 1;
            $msg .= 'You forgot to enter an email address.<br />';
        }

        $_POST['rank'] = (!empty($_POST['rank'])) ? intval($_POST['rank']) : $member->rank;
        if ( !isset($_POST['rank']) )
        {
            $errors = 1;
            $msg .= 'You didn\'t enter a rank for this player.<br />';
        }

        //If the rank of the player you're editing is higher or equal to your own rank, then you are not allowed to edit their rank
        if ( $member->rank > $this->player->rank )
        {
            if ( $_POST['rank'] != $member->rank )
            {
                //Reset rank to original rank
                $_POST['rank'] = $member->rank;
            }
        }
        else if ( $_POST['rank'] > $this->player->rank )
        {
            $errors = 1;
            $msg .= 'You can\'t set a member\'s rank to higher than your own!<br />';
        }

        $_POST['money'] = intval($_POST['money']);
        if ( $_POST['money'] < 0 )
        {
            $errors = 1;
            $msg .= 'The player must have a positive amount of money!<br />';
        }

        $_POST['level'] = intval($_POST['level']);
        if ( $_POST['level'] < 0 )
        {
            $errors = 1;
            $msg .= 'The player must have a level higher than 0!<br />';
        }

        //The form wasn't filled out correctly
        if ( $errors == 1 )
        {
            $member->email = $_POST['email'];
            $member->rank = $_POST['rank'];
            $member->money = $_POST['money'];
            $member->level = $_POST['level'];

            $this->tpl->assign('member', $member);
            $this->tpl->assign('GET_MSG', $msg);
            $this->loadView('members_edit.tpl');
            exit;
        }
        else
        {
            //No errors, update player info
            $query = $this->db->execute('UPDATE `<ezrpg>players` SET `email`=?, `rank`=?, `money`=?, `level`=? WHERE `id`=?', array( $_POST['email'], $_POST['rank'], $_POST['money'], $_POST['level'], $member->id ));

            $this->setMessage('You have updated the player\'s info.', 'GOOD');
            header('Location: index.php?mod=Members');
            exit;
        }
    }

    /*
      Function: deleteMember
      Deletes a member from the database. Asks for confirmation first.
     */

    private function deleteMember()
    {
        $member = $this->db->fetchRow('SELECT `id`, `username` FROM `<ezrpg>players` WHERE `id`=?', array( $_GET['id'] ));

        if ( $member == false )
        {
            header('Location: index.php?mod=Members');
            exit;
        }

        if ( $member->id == $this->player->id )
        {
            //Cannot delete self
            $this->setMessage('You cannot delete yourself!', 'WARN');
            header('Location: index.php?mod=Members');
            exit;
        }

        if ( !isset($_POST['confirm']) )
        {
            $this->tpl->assign('member', $member);
            $this->loadView('members_delete.tpl');
            exit;
        }
        else
        {
            $query = $this->db->execute('DELETE FROM `<ezrpg>players` WHERE `id`=?', array( $member->id ));
            $this->setMessage('You have deleted <strong>' . $member->username . '</strong>.', 'GOOD');
            header('Location: index.php?mod=Members');
            exit;
        }
    }

}