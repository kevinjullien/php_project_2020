<?php


class MembersListController
{

    private $_db;

    public function __construct($db) {
        $this->_db = $db;
    }

    public function run()
    {

        if (empty($_SESSION['authenticated']) ||  $_SESSION['admin'] = false) {
            header("Location: index.php?action=home");
            die();
        }

        $activate = '';


        if (!empty($_POST)) {
            if (!empty($_POST['form_deactivate'])) {
                $activate = 0;
                foreach ($_POST['form_deactivate'] as $id_member => $action) {
                    $this->_db->changeStatus($id_member, $activate);
                }
            } else if (!empty($_POST['form_activate'])) {
                $activate = 1;
                foreach ($_POST['form_activate'] as $id_member => $action) {
                    $this->_db->changeStatus($id_member, $activate);
                }
            }
        }
        $members =$this->_db->select_members();

        require_once(VIEW_PATH . 'membersList.php');
    }
}

?>