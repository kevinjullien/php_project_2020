<?php


class HomeController
{

    private $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function run()
    {
        if (!empty($_POST['submitVote'])){
            $this->_db->insert_vote($_SESSION['id_member'], $_POST['id_venue']);
        }

        ### Random venues ###
        //The number of random images displayed in the carousel.
        //Nothing displayed if less than 0 or more than the number of venues in the db
        $numberOfVenuesInCarousel = 3;
        try {
            $randomVenues = $this->_db->select_random_venues($numberOfVenuesInCarousel);
        } catch (InvalidArgumentException $exception) {
            $numberOfVenuesInCarousel = 0;
        }

        ### Top venues ###
        $numberOfVenuesInTop = 10;

        //If the user is logged, fetches their votes
        if (!empty($_SESSION['authenticated'])) {
            $memberVotes = $this->_db->select_votes_by_member($_SESSION['id_member']);
        }

        if (!empty($_POST['form_delete'])) {
            foreach ($_POST['form_delete'] as $id_venue => $action) {
                $this->_db->delete_venue($id_venue);
            }
        }

        $venues = $this->_db->select_venues_ordered_by_votes_amount($numberOfVenuesInTop);



        require_once(VIEW_PATH . 'home.php');
    }

}