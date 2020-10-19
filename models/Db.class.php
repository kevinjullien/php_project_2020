<?php


class Db
{
    private static $instance = null;
    private $_db;

    private function __construct()
    {
        $ini = parse_ini_file('config/config.ini');
        try {
            $this->_db = new PDO('mysql:host=' . $ini['db_host'] . ';dbname=' . $ini['db_name'] . ';charset=utf8mb4', $ini['db_login'], $ini['db_password']);
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    # Pattern Singleton
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Db();
        }
        return self::$instance;
    }


    #################### Member area ####################


    /**
     * Searches in the DB for a member via its email
     * @param $email string the email
     * @return Member|null member if found, null if not
     */
    public function select_member_by_email($email)
    {
        $query = 'SELECT * FROM members WHERE email = :email';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':email', $email);
        $qp->execute();
        $row = $qp->fetch();
        $member = null;
        if (!empty($row))
            $member = new Member($row->id_member, $row->password, $row->email, $row->is_admin, $row->activate, $row->lastname, $row->firstname);

        return $member;
    }

    /**
     * Searches in the DB for a member via its id
     * @param $id string the id
     * @return Member|null member if found, null if not
     */
    public function select_member_by_id($id)
    {
        $query = 'SELECT * FROM members WHERE id_member = :id';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':id', $id);
        $qp->execute();
        $row = $qp->fetch();
        $member = null;
        if (!empty($row))
            $member = new Member($row->id_member, $row->password, $row->email, $row->is_admin, $row->activate, $row->lastname, $row->firstname);

        return $member;
    }


    public function select_members()
    {
        $query = 'SELECT * FROM members WHERE is_admin <> 1';
        $ps = $this->_db->prepare($query);
        $ps->execute();
        $arrayMembers = array();
        while ($row = $ps->fetch()) {
            $arrayMembers[] = new Member($row->id_member, $row->password, $row->email, $row->is_admin, $row->activate, $row->lastname, $row->firstname);
        }
        return $arrayMembers;
    }

    public function changeStatus($id_member, $activate)
    {
        $query = 'UPDATE members SET activate=:activate WHERE id_member=:id_member';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':activate', $activate);
        $ps->bindValue(':id_member', $id_member);
        return $ps->execute();
    }

    /**
     * Inserts a member in the database
     *
     * @param $email
     * @param $firstname
     * @param $lastname
     * @param $password
     * @return bool
     */
    public function insert_member($email, $firstname, $lastname, $password)
    {
        $query = 'INSERT INTO members (email, firstname, lastname, password)
                  VALUES (:email,:firstname,:lastname,:password)';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':email', $email);
        $qp->bindValue(':firstname', $firstname);
        $qp->bindValue(':lastname', $lastname);
        $qp->bindValue(':password', $password);


        return $qp->execute();
    }


    #################### End member area ####################


    #################### Venue area ####################


    /**
     * Fetches a venue
     * The password of the submitter will be NULL for security purpose
     *
     * @param $id_venue int the venue's id
     * @return Venue or null if doesn't exist
     */
    public function select_venue($id_venue)
    {
        $query = 'SELECT ve.*, ad.*, me.* FROM venues ve, adresses ad, members me 
                  WHERE ve.id_venue = :id_venue AND ad.id_adress = ve.id_adress AND me.id_member = ve.submitter';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':id_venue', $id_venue);
        $qp->execute();
        $venue = null;
        if ($row = $qp->fetch()) {
            $venue = $this->venue_from_row($row);
        }
        return $venue;
    }

    /**
     * Creates a complete Venue with its Address and Submitter with a fetched row from the database
     *
     * @param $row
     * @return Venue
     */
    public function venue_from_row($row)
    {
        $submitter = new Member($row->id_member, NULL, $row->email, $row->is_admin, $row->activate, $row->lastname, $row->firstname);
        $address = new Address($row->id_adress, $row->country, $row->city, $row->postal_code, $row->street, $row->number, $row->latitude, $row->longitude);
        $venue = new Venue($row->id_venue, $row->title, $row->photo, $address, $row->type, $submitter, $row->start_datetime, $row->start_endtime);
        return $venue;
    }

    /**
     * Fetches the most voted venues with at least one vote
     * The amount of venues will be between 0 and $count or the max amount of venues in the database if less than $count
     * If the amount is under 0, an empty array will be returned
     * Order by amount of votes, then most recently added in the database
     *
     * @param $count integer the amount of venues desired
     * @return array of venues, empty array if no venue is voted
     */
    public function select_venues_ordered_by_votes_amount($count)
    {
        $venues = array();
        if ($count < 0) return $venues;

        $query = 'SELECT ve.*, count(vo.id_favourite) AS "votes", ad.*, me.* 
                  FROM venues ve, votes vo, adresses ad, members me 
                  WHERE vo.id_favourite = ve.id_venue AND ad.id_adress = ve.id_adress AND me.id_member = ve.submitter 
                  GROUP BY ve.id_venue HAVING votes >0 
                  ORDER BY votes DESC, ve.id_venue DESC LIMIT 0, :count';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':count', $count, PDO::PARAM_INT);
        $qp->execute();

        if ($qp->rowcount() != 0) {
            while ($row = $qp->fetch()) {
                $venues[] = $this->venue_from_row($row);
            }
        }
        return $venues;
    }

    /**
     * Fetches different random venues
     * The password of the submitter will be NULL for security purpose
     *
     * @param $count integer the amount of venues desired
     * @return array of venues
     * @throws InvalidArgumentException if the amount asked is lower than 0 or higher than the amount of venues in the DB
     */
    public function select_random_venues($count)
    {
        if ($count < 0 || $count > $this->count_venues())
            throw new InvalidArgumentException("Invalid value");

        $query = 'SELECT ve.*, ad.*, me.* FROM venues ve, adresses ad, members me 
                  WHERE ad.id_adress = ve.id_adress AND me.id_member = ve.submitter 
                  GROUP BY ve.id_venue ORDER BY RAND() DESC LIMIT 0, :count';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':count', $count, PDO::PARAM_INT);
        $qp->execute();
        $array = array();
        if ($qp->rowcount() != 0) {
            while ($row = $qp->fetch()) {
                $array[] = $this->venue_from_row($row);
            }
        }
        return $array;
    }

    /**
     * Counts the amount of venues
     *
     * @return int the amount of venues
     */
    public function count_venues()
    {
        $query = 'SELECT COUNT(ve.id_venue) AS "amount" FROM venues ve';
        $result = $this->_db->query($query);

        return $result->fetch()->amount;
    }

    /**
     * SFetches the id of the latest address added in the database
     *
     * @return mixed
     */
    public function select_latest_added_address()
    {
        $query = 'SELECT ad.id_adress FROM adresses ad ORDER BY ad.id_adress DESC LIMIT 1';
        $result = $this->_db->query($query);

        return $result->fetch()->id_adress;
    }

    /**
     * Fetches the venues that a member has created
     * The last added will be the first fetched
     * The password of the submitter will be NULL for security purpose
     *
     * @param $id_member
     * @return mixed
     */
    public function select_owned_venues($id_member)
    {
        $query = 'SELECT ve.*, ad.*, me.* FROM venues ve, adresses ad, members me 
                  WHERE ad.id_adress = ve.id_adress AND ve.submitter = :id_member AND me.id_member = ve.submitter 
                  ORDER BY ve.id_venue DESC';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':id_member', $id_member);
        $qp->execute();
        $array = array();
        if ($qp->rowcount() != 0) {
            while ($row = $qp->fetch()) {
                $array[$row->id_venue] = $this->venue_from_row($row);
            }
        }
        return $array;
    }

    /**
     * Inserts an event in the database.
     * If $start and $end are different but one is NULL, return false
     *
     * @param $title
     * @param $photo
     * @param $id_address
     * @param $submitter
     * @param $start
     * @param $end
     * @return bool
     */
    public function insert_event_with_latest_address($title, $photo, $submitter, $start, $end)
    {
        if ($start != $end && ($start == null || $end == null))
            return false;

        $id_address = $this->select_latest_added_address();
        $query = 'INSERT INTO venues (title, photo, id_adress, type, submitter, start_datetime, start_endtime) 
                  VALUES (:title, :photo, :id_address, :type, :submitter, :start_datetime, :start_endtime)';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':title', $title);
        $qp->bindValue(':photo', $photo);
        $qp->bindValue(':id_address', $id_address);
        $qp->bindValue(':type', 'E');
        $qp->bindValue(':submitter', $submitter);
        $qp->bindValue(':start_datetime', $start);
        $qp->bindValue(':start_endtime', $end);

        return $qp->execute();
    }

    /**
     * Inserts a place in the database
     *
     * @param $title
     * @param $photo
     * @param $id_address
     * @param $submitter
     * @return bool
     */
    public function insert_place_with_latest_address($title, $photo, $submitter)
    {
        $id_address = $this->select_latest_added_address();
        $query = 'INSERT INTO venues (title, photo, id_adress, type, submitter) 
                  VALUES (:title, :photo, :id_address, :type, :submitter)';
        $qp = $this->_db->prepare($query);

        $qp->bindValue(':title', $title);
        $qp->bindValue(':photo', $photo);
        $qp->bindValue(':id_address', $id_address);
        $qp->bindValue(':type', 'P');
        $qp->bindValue(':submitter', $submitter);

        return $qp->execute();
    }

    /**
     * @return Venue the latest added venue
     */
    public function select_latest_venue_added()
    {
        $query = 'SELECT ve.*, ad.*, me.* FROM venues ve, adresses ad, members me ORDER BY ve.id_venue DESC LIMIT 0,1';
        $qp = $this->_db->prepare($query);
        $qp->execute();
        $row = $qp->fetch();

        $venue = $this->venue_from_row($row);

        return $venue;
    }

    /**
     * Fetches the venues with the chosen keyword
     * The last added will be the first fetched
     *
     * @param $label string
     * @return array of venues or empty
     */
    public function select_venues_by_keyword($label)
    {
        $query = 'SELECT ve.*, ad.*, me.* FROM venues ve, adresses ad, members me, keywords_of_venues kv, keywords ke 
                  WHERE ve.id_venue = kv.id_venue AND kv.id_keyword = ke.id_keyword 
                  AND ad.id_adress = ve.id_adress AND me.id_member = ve.submitter AND ke.label = :label';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':label', $label);
        $qp->execute();
        $array = array();
        if ($qp->rowcount() != 0) {
            while ($row = $qp->fetch()) {
                $array[] = $this->venue_from_row($row);
            }
        }
        return $array;
    }

    /**
     * Fetches venues by types
     *
     * @param $type string 'E' for event, 'P' for place
     * @return array of Venue
     */
    public function select_venues_by_type($type)
    {
        $query = 'SELECT ve.*, ad.*, me.* FROM venues ve, adresses ad, members me 
                  WHERE ad.id_adress = ve.id_adress AND me.id_member = ve.submitter AND ve.type = :type 
                  ORDER BY ve.id_venue DESC';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':type', $type);
        $qp->execute();
        $array = array();
        if ($qp->rowcount() != 0) {
            while ($row = $qp->fetch()) {
                $array[$row->id_venue] = $this->venue_from_row($row);
            }
        }
        return $array;
    }

    /**
     * deletes venue
     *
     * @param $id_venue, id of the venue
     */
    public function delete_venue($id_venue) {
        $query = 'DELETE ve.*, ad.* FROM venues ve INNER JOIN adresses ad ON ad.id_adress = ve.id_adress WHERE ve.id_venue = :id_venue';
        $ps = $this->_db->prepare($query);
        $ps->bindValue(':id_venue',$id_venue);

        return $ps->execute();
    }


    #################### End venue area ####################


    #################### Address area ####################


    /**
     * Updates an existing venue
     *
     * @param $id_venue
     * @param $title
     * @param $photo
     * @param $type
     * @param $start_datetime
     * @param $end_datetime
     * @return bool
     */
    public function update_venue($id_venue, $title, $photo, $type, $start_datetime, $end_datetime)
    {
        $query = 'UPDATE venues SET ';

        if ($title != null) {

            $query = $query . 'title = ' . $this->_db->quote($title) . ' ,';
        }
        if ($photo != null) {
            $query = $query . 'photo = ' . $this->_db->quote($photo) . ' ,';
        }
        if ($type != null) {
            $query = $query . 'type = ' . $this->_db->quote($type) . ' ,';
        }

        if ($start_datetime != null && $end_datetime != null) {
            $query = $query . 'start_datetime = ' . $this->_db->quote($start_datetime) . ', start_endtime = ' . $this->_db->quote($end_datetime) . ' ,';
        } else
            $query = $query . 'start_datetime = NULL, start_endtime = NULL ,';

        $query = trim($query, ",");
        $query = $query . "WHERE id_venue = $id_venue";


        $qp = $this->_db->prepare($query);

        return $qp->execute();
    }

    /**
     * Fetches an address by its id
     *
     * @param $id_address
     * @return Address or NULL if not found
     */
    public function select_address($id_address)
    {
        $query = 'SELECT ad.* FROM adresses ad WHERE ad.id_adress = :id';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':id', $id_address);
        $qp->execute();
        $row = $qp->fetch();
        if (!empty($row))
            return new Address($row->id_adress, $row->country, $row->city, $row->postal_code, $row->street, $row->number, $row->latitude, $row->longitude);
        return NULL;
    }

    /**
     * @return Address the latest added address
     */
    public function select_latest_address_added()
    {
        $query = 'SELECT ad.* FROM adresses ad ORDER BY ad.id_adress DESC LIMIT 0,1';
        $qp = $this->_db->prepare($query);
        $qp->execute();
        $row = $qp->fetch();

        return new Address($row->id_adress, $row->country, $row->city, $row->postal_code, $row->street, $row->number, $row->latitude, $row->longitude);
    }

    /**
     * Inserts an adress in the database.
     * if $latitude and $longitude are different but one is NULL, return false
     *
     * @param $country
     * @param $city
     * @param $postal_code
     * @param $street
     * @param $number
     * @param $latitude
     * @param $longitude
     * @return bool
     */
    public function insert_complete_address($country, $city, $postal_code, $street, $number, $latitude, $longitude)
    {
        if ($latitude != $longitude && ($latitude == NULL || $longitude == NULL))
            return false;
        $query = 'INSERT INTO adresses (country, city, postal_code, street, number) 
                  VALUES (:country, :city, :postal_code, :street, :number)';
        $qp = $this->_db->prepare($query);
        if ($latitude != NULL) {
            $query = 'INSERT INTO adresses (country, city, postal_code, street, number, latitude, longitude) 
                      VALUES (:country, :city, :postal_code, :street, :number, :latitude, :longitude)';
            $qp = $this->_db->prepare($query);
            $qp->bindValue(':latitude', $latitude);
            $qp->bindValue(':longitude', $longitude);
        }
        $qp->bindValue(':country', $country);
        $qp->bindValue(':city', $city);
        $qp->bindValue(':postal_code', $postal_code);
        $qp->bindValue(':street', $street);
        $qp->bindValue(':number', $number);

        return $qp->execute();
    }

    /**
     * Inserts an adress in the database without latitude and longitude.
     *
     * @param $country
     * @param $city
     * @param $postal_code
     * @param $street
     * @param $number
     * @return bool
     */
    public function insert_address($country, $city, $postal_code, $street, $number)
    {
        $query = 'INSERT INTO adresses (country, city, postal_code, street, number) 
                  VALUES (:country, :city, :postal_code, :street, :number)';
        $qp = $this->_db->prepare($query);

        $qp->bindValue(':country', $country);
        $qp->bindValue(':city', $city);
        $qp->bindValue(':postal_code', $postal_code);
        $qp->bindValue(':street', $street);
        $qp->bindValue(':number', $number);

        return $qp->execute();
    }


    #################### End address area ####################


    #################### Start keyword area ####################


    /**
     * Updates an address in the database
     *
     * @param $id
     * @param $country
     * @param $city
     * @param $postal_code
     * @param $street
     * @param $number
     * @param $latitude
     * @param $longitude
     * @return bool
     */
    public function update_address($id, $country, $city, $postal_code, $street, $number, $latitude, $longitude)
    {
        $query = 'UPDATE adresses a SET ';

        if ($country != null)
            $query = $query . 'country = ' . $this->_db->quote($country) . ' ,';

        if ($city != null)
            $query = $query . 'city = ' . $this->_db->quote($city) . ' ,';

        if ($postal_code != null)
            $query = $query . 'postal_code = ' . $this->_db->quote($postal_code) . ' ,';

        if ($street != null)
            $query = $query . 'street = ' . $this->_db->quote($street) . ' ,';

        if ($number != null)
            $query = $query . 'number = ' . $this->_db->quote($number) . ' ,';

        if ($latitude != null && $longitude != null) {
            $query = $query . 'latitude  = ' . $this->_db->quote($latitude) . ', longitude = ' . $this->_db->quote($longitude) . ' ,';
        }
        //removes the last comma
        $query = trim($query, ",");


        $query = $query . "WHERE id_adress = $id";
        $qp = $this->_db->prepare($query);

        return $qp->execute();
    }

    /**
     * Fetches every existing venues
     *
     * Places a keyword label in $array[id_keywords]
     * @return array of venues
     */
    public function select_keywords()
    {
        $query = 'SELECT k.* FROM keywords k ORDER BY k.id_keyword';
        $result = $this->_db->query($query);
        $array = array();
        if ($result->rowcount() != 0) {
            while ($row = $result->fetch()) {
                $array[$row->id_keyword] = $row->label;
            }
        }
        return $array;
    }

    /**
     * Fetches the keywords of a venue if there are any
     *
     * @param $venue Venue
     * @return array of string
     */
    public function select_keywords_of_venue($venue)
    {
        $query = 'SELECT k.* FROM keywords_of_venues k, venues v 
                  WHERE k.id_venue = v.id_venue AND k.id_venue = :id_venue';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':id_venue', $venue->getId(), PDO::PARAM_INT);
        $qp->execute();
        $array = array();
        if ($qp->rowcount() != 0) {
            while ($row = $qp->fetch()) {
                $array[] = $row->id_keyword;
            }
        }
        return $array;
    }

    /**
     * Inserts a keyword linked to the latest venue
     *
     * @param $id_keyword
     * @return bool
     */
    public function insert_keyword_of_latest_venue($id_keyword)
    {
        $query = 'INSERT INTO keywords_of_venues (id_venue, id_keyword) VALUES (LAST_INSERT_ID(), :id_keyword)';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':id_keyword', $id_keyword);

        return $qp->execute();
    }

    /**
     * Inserts a keyword_of_venues
     *
     * @param $id_venue
     * @param $id_keyword
     * @return bool
     */
    public function insert_keywords_of_venues($id_venue, $id_keyword)
    {
        if ($id_venue == null || -$id_keyword == null)
            return false;

        $query = 'INSERT INTO keywords_of_venues (id_venue, id_keyword) VALUES (:id_venue, :id_keyword)';

        $qp = $this->_db->prepare($query);
        $qp->bindValue(':id_venue', $id_venue);
        $qp->bindValue(':id_keyword', $id_keyword);

        return $qp->execute();
    }

    /**
     * Removes a keyword_of_venues
     *
     * @param $id_venue
     * @param $id_keyword
     * @return bool
     */
    public function remove_keywords_of_venues($id_venue, $id_keyword)
    {
        if ($id_venue == null || -$id_keyword == null)
            return false;

        $query = 'DELETE FROM keywords_of_venues WHERE id_venue = :id_venue AND id_keyword = :id_keyword';

        $qp = $this->_db->prepare($query);
        $qp->bindValue(':id_venue', $id_venue);
        $qp->bindValue(':id_keyword', $id_keyword);

        return $qp->execute();
    }

    /**
     * Returns how many keywords are in the database
     *
     * @return mixed
     */
    public function count_keywords()
    {
        $query = 'SELECT COUNT(k.id_keyword) AS "amount" FROM keywords k';
        $result = $this->_db->query($query);

        return $result->fetch()->amount;
    }


    #################### End keyword area ####################


    #################### Votes area ####################


    /**
     * Fetches the votes of a specific member
     * Each vote will take place as a 'true' entry in array['id_venue'] where id_venue is the id of the voted venue
     *
     * @param $id_member int the member's ID
     * @return array of boolean
     */
    public function select_votes_by_member($id_member)
    {
        $query = 'SELECT vo.* FROM votes vo WHERE vo.id_member = :id_member';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':id_member', $id_member, PDO::PARAM_INT);
        $qp->execute();
        $array = array();
        if ($qp->rowcount() != 0) {
            while ($row = $qp->fetch()) {
                $array[$row->id_favourite] = true;
            }
        }
        return $array;
    }


    #################### End votes area ####################

    /**
     * Inserts a vote in the database
     *
     * @param $id_member
     * @param $id_venue
     * @return bool
     */
    public function insert_vote($id_member, $id_venue)
    {
        $query = 'INSERT INTO votes (id_member, id_favourite)
                  VALUES (:id_member, :id_favourite)';
        $qp = $this->_db->prepare($query);
        $qp->bindValue(':id_member', $id_member);
        $qp->bindValue(':id_favourite', $id_venue);

        return $qp->execute();
    }

}