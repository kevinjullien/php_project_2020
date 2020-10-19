<?php


class PlacesOrEventsController
{
    private $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function run()
    {
        switch ($_GET['action']) {
            case 'places':
                $venuesType = 'P';
                break;
            default:
                $venuesType = 'E';
                break;
        }


        if (!empty($_POST['submitVote']))
            $this->_db->insert_vote($_SESSION['id_member'], $_POST['id_venue']);

        if (!empty($_SESSION['authenticated']))
            $memberVotes = $this->_db->select_votes_by_member($_SESSION['id_member']);

        if (!empty($_POST['form_delete'])) {
            foreach ($_POST['form_delete'] as $id_venue => $action) {
                $this->_db->delete_venue($id_venue);
            }
        }
        $venues = $this->_db->select_venues_by_type($venuesType);


        require_once(VIEW_PATH . 'placesOrEvents.php');
    }
}