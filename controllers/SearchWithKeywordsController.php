<?php


class SearchWithKeywordsController
{
    private $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function run()
    {
        $keywords = $this->_db->select_keywords();

        if (isset ($_POST['keyword']) )
            $venues = $this->_db->select_venues_by_keyword($_POST['keyword']);

        if (!empty($_SESSION['authenticated'])) {
            $memberVotes = $this->_db->select_votes_by_member($_SESSION['id_member']);
        }
        if (!empty($_POST['submitVote'])){
            $this->_db->insert_vote($_SESSION['id_member'], $_POST['id_venue']);
        }

        require_once(VIEW_PATH . 'searchWithKeywords.php');
    }
}