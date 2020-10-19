<?php


class AddEditVenueController
{
    private $_db;


    public function __construct($_db)
    {
        $this->_db = $_db;
    }

    public function run()
    {
        //if not logged -> homepage
        if (empty($_SESSION['authenticated'])) {
            header("Location: index.php?action=login");
            die();
        }

        $message = '';
        if (isset ($_POST['choice']) || isset($_POST['selectedVenueId']) || isset($_POST['updateVenue']))
            $keywords = $this->_db->select_keywords();
        if (isset ($_POST['choice']) && $_POST['choice'] == "edit")
            $venues = $this->_db->select_owned_venues($_SESSION['id_member']);
        //if venue is selected for edition
        if (isset($_POST['selectedVenueId'])) {
            $venue = $this->_db->select_venue($_POST['selectedVenueId']);
            $eventStart = str_replace(" ", "T", $venue->getStartDatetime());
            $eventEnd = str_replace(" ", "T", $venue->getEndDatetime());
            $keywordsOfSelectedVenue = $this->_db->select_keywords_of_venue($venue);
        }
        if (isset($_POST['updateVenue'])) {
            $venue = $this->_db->select_venue($_POST['selectedVenueId']);
            $keywordsOfSelectedVenue = $this->_db->select_keywords_of_venue($venue);
            $formInputs = $this->update_form_check($venue, $keywordsOfSelectedVenue);
            if (empty($formInputs['messages'])) {
                if ($this->update_venue($venue, $formInputs['content'], $keywordsOfSelectedVenue))
                    $message = 'Edition effectuée';
                else
                    $message = 'Un problème est survenu';
            }
        } elseif (isset($_POST['addVenue'])) {
            $formInputs = $this->add_form_check();
            if (empty($formInputs['messages'])) {
                $newKeywords = $this->get_keywords_after_post($formInputs['content']);
                $newVenue = $this->construct_venue_after_post($formInputs['content']);
                $newAddress = $this->construct_address_after_post($formInputs['content']);
                if ($this->add_venue($newVenue, $newAddress, $newKeywords))
                    $message = 'Ajout effectué';
                else
                    $message = 'Un problème est survenu';
            }
        }

        require_once(VIEW_PATH . 'addOrEditVenue.php');
    }

    ### Add area ###

    /**
     * Checks the whole form for a venue edit
     * @param $venue Venue the original venue
     * @param $keywordsOfVenue string the keywords attached to the original venue
     */
    private function update_form_check($venue, $keywordsOfVenue)
    {
        $array = array();
        $messages = array(); //Messages for incorrectly filled fields
        $content = array(); //Fields'content of the venue and the address + needed elements to create an Adress and a Venue
        $content['id_venue'] = $venue->getId();
        $content['id_address'] = $venue->getAddress()->getId();

        if (!empty($_POST['title']))
            $content['title'] = $_POST['title'];

        #Address
        if (empty($_POST['latitude']) && !empty($_POST['longitude'])) {
            $messages['latitude'] = 'Remplissez ce champ ou laissez longitude vide';
            $messages['longitude'] = 'Videz ce champs ou remplissez latitude';
            $content['longitude'] = $_POST['longitude'];
        } elseif (!empty($_POST['latitude']) && empty($_POST['longitude'])) {
            $messages['latitude'] = 'videz ce champs ou remplissez longitude';
            $messages['longitude'] = 'Remplissez ce champ ou laissez latitude vide';
            $content['latitude'] = $_POST['latitude'];
        } elseif (!empty($_POST['latitude']) && !empty($_POST['longitude'])) {
            $latitude = $this->comma_to_full_stop($_POST['latitude']);
            $longitude = $this->comma_to_full_stop($_POST['longitude']);

            //Range accepted: from -90.000000 to 90.000000, with least one decimal, up to 6
            if (!$this->decimal_degree_format_check($latitude))
                $messages['latitude'] = 'Format invalide, accepté: [-90.000000 ... 90.000000]';
            if (!$this->decimal_degree_format_check($longitude))
                $messages['longitude'] = 'Format invalide, accepté: [-90.000000 ... 90.000000] jusqu\'à 6 décimales';

            if (!isset($messages['latitude']) && !isset($messages['longitude'])) {
                $content['latitude'] = $latitude;
                $content['longitude'] = $longitude;
                $content['geographicData'] = true;
            }
        }

        if (!empty($_POST['country']))
            $content['country'] = $_POST['country'];


        if (!empty($_POST['city']))
            $content['city'] = $_POST['city'];

        if (!empty($_POST['postal_code']))

            if ($this->postal_code_format_check($_POST['postal_code']))
                $content['postal_code'] = $_POST['postal_code'];
            else
                $messages['postal_code'] = 'Format invalide';

        if (!empty($_POST['street']))
            $content['street'] = $_POST['street'];

        if (!empty($_POST['number']))
            $content['number'] = $_POST['number'];

        #Keywords
        $content['0'] = $_POST['0'];

        if ($_POST['1'] != $_POST['0'])
            $content['1'] = $_POST['1'];

        if ($_POST['2'] != $_POST['0'] && $_POST['2'] != $_POST['1'])
            $content['2'] = $_POST['2'];

        #Event date and time
        if (!empty($_POST['start_datetime']) && !empty($_POST['end_datetime'])) {
            $content['start_datetime'] = $this->date_treatment_post_to_sql($_POST['start_datetime']);
            $content['end_datetime'] = $this->date_treatment_post_to_sql($_POST['end_datetime']);
            $content['type'] = 'E';
        } elseif ($venue->getType() == 'E' & empty($_POST['start_datetime']) && empty($_POST['end_datetime'])) {
            $content['start_datetime'] = null;
            $content['end_datetime'] = null;
            $content['type'] = 'P';
        } elseif ((empty($_POST['start_datetime']) && !empty($_POST['end_datetime'])) || (!empty($_POST['start_datetime']) && empty($_POST['end_datetime']))) {
            $messages['start_datetime'] = 'Veuillez compléter les deux champs ou les laisser vides';
            $messages['end_datetime'] = 'Veuillez compléter les deux champs ou les laisser vides';
        }

        #File
        if (!empty($_FILES['userfile']['tmp_name']) && empty($messages)) {
            if ($this->is_uploaded_file_an_image('userfile')) {
                $content['photo'] = $this->uploaded_image_treatment('userfile');
            } else
                $messages['photo'] = 'Format invalide, veuillez ajouter un fichier image conforme';
        } elseif (!empty($_FILES['userfile']['tmp_name']) && !empty($messages))
            $messages['photo'] = 'Veuillez réintroduire votre image';

        $array['messages'] = $messages;
        $array['content'] = $content;
        return $array;
    }

    /**
     * Replaces coma with a full stop if necessary.
     *
     * @param $data string
     * @return string
     */
    private function comma_to_full_stop($data)
    {
        if (substr_count($data, ',') == 0)
            return $data;
        else
            $data = str_replace(',', '.', $data);
        return $data;
    }


    ### Edit area ###

    /**
     * Assure that the data received is an expression of a decimal degree latitude or longitude
     * Range is -90.000000 to 90.000000
     * At least one decimal up to six
     *
     * @param $data string
     * @return bool
     */
    private function decimal_degree_format_check($data)
    {
        if (preg_match('/-?([1-8]?[0-9]\.[0-9]{1,7}|90\.0{1,6})$/', $data))
            return true;
        return false;
    }

    /**
     * Assure that the postal_code may be one.
     * Max 12 characters, no special one except ' ' and '-', not at the begining nor the ending of the data
     *
     * @param $data string
     * @return bool
     */
    private function postal_code_format_check($data)
    {
        if (preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\- ]{0,10}[a-zA-Z0-9]$/', $data))
            return true;
        return false;
    }


    ### various functions ###

    /**
     * Converts the format from form field type="datetime-local" to sql timestamp
     *
     * @param $date
     * @return string
     */
    private function date_treatment_post_to_sql($date)
    {
        $date = str_replace('T', ' ', $date);

        //if seconds are not present in the timestamp
        if (substr_count($date, ':') == 1)
            $start = $date . ':00';

        return $date;
    }

    /**
     * Checks if the file after a POST
     *
     * @param $path string the path as $_FILES['$path']
     * @return bool
     */
    private function is_uploaded_file_an_image($path)
    {
        $imageinfo = getimagesize($_FILES["$path"]['tmp_name']);
        if (($_FILES["$path"]['type'] == 'image/jpeg' && $imageinfo['mime'] == 'image/jpeg') || ($_FILES["$path"]['type'] == 'image/png' && $imageinfo['mime'] == 'image/png'))
            return true;
        return false;
    }

    /**
     * Treat an uploaded image after a POST
     * Adds a timestamp before the file's name
     * Places the renamed file in IMAGE_PATH
     *
     * @param $path string the path as $_FILES['$path']
     * @return string
     */
    private function uploaded_image_treatment($path)
    {
        $uploadTime = str_replace('.', '_', microtime(true));
        $origin = $_FILES["$path"]['tmp_name'];
        $destination = IMAGE_PATH . $uploadTime . basename($_FILES["$path"]['name']);
        move_uploaded_file($origin, $destination);

        return $destination;
    }

    /**
     * Prepares data to call functions in Db.class
     * Calls the functions
     *
     * @param $venue Venue the selected venue to be edited
     * @param $dataArray string of data from $array['content'] from update_form_check()
     * @param $keywordsOfVenue string id's of the selected venue's keywords before edit
     * @return bool if every functions went right
     */
    private function update_venue($venue, $dataArray, $keywordsOfVenue)
    {
        $id_address = $dataArray['id_address'];
        $id_venue = $dataArray['id_venue'];

        #Address
        $addressIsEdited = false;
        if (isset($dataArray['country'])) {
            $country = $dataArray['country'];
            $addressIsEdited = true;
        } else
            $country = null;
        if (isset($dataArray['city'])) {
            $city = $dataArray['city'];
            $addressIsEdited = true;
        } else
            $city = null;
        if (isset($dataArray['postal_code'])) {
            $postal_code = $dataArray['postal_code'];
            $addressIsEdited = true;
        } else
            $postal_code = null;
        if (isset($dataArray['street'])) {
            $street = $dataArray['street'];
            $addressIsEdited = true;
        } else
            $street = null;
        if (isset($dataArray['number'])) {
            $number = $dataArray['number'];
            $addressIsEdited = true;
        } else
            $number = null;
        if (isset($dataArray['latitude'])) {
            $latitude = $dataArray['latitude'];
            $longitude = $dataArray['longitude'];
            $addressIsEdited = true;
        } else {
            $latitude = null;
            $longitude = null;
        }

        if ($addressIsEdited)
            if (!$this->_db->update_address($id_address, $country, $city, $postal_code, $street, $number, $latitude, $longitude))
                return false;

        #Venue
        $venueIsEdited = false;
        if (isset($dataArray['title'])) {
            $title = $dataArray['title'];
            $venueIsEdited = true;
        } else
            $title = null;
        if (isset($dataArray['photo'])) {
            $photo = $dataArray['photo'];
            $venueIsEdited = true;
        } else
            $photo = null;
        if (isset($dataArray['start_datetime'])) {
            $start_datetime = $dataArray['start_datetime'];
            $venueIsEdited = true;
        } else
            $start_datetime = null;
        if (isset($dataArray['end_datetime'])) {
            $end_datetime = $dataArray['end_datetime'];
            $venueIsEdited = true;
        } else
            $end_datetime = null;
        if (isset($dataArray['type']))
            $type = $dataArray['type'];
        else
            $type = null;

        if ($venue->getType() == 'E' && $dataArray['type'] == 'P')
            if (!$this->_db->update_venue($id_venue, $title, $photo, $type, NULL, NULL))
                return false;
            elseif ($venueIsEdited) {
                if (!$this->_db->update_venue($id_venue, $title, $photo, $type, $start_datetime, $end_datetime))
                    return false;
            }

        #Keywords
        $formerKeywords = array();
        $newKeywords = array();

        for ($i = 0; $i < 3; $i++) {
            if (!empty($_POST[$i]))
                $newKeywords[$_POST[$i]] = true;
            if (isset($keywordsOfVenue[$i]))
                $formerKeywords[$keywordsOfVenue[$i]] = true;
        }

        if ($newKeywords != $formerKeywords) {
            for ($i = 1; $i <= $this->_db->count_keywords(); $i++) {
                if (isset($newKeywords[$i]) && !isset($formerKeywords[$i])) {
                    if (!$this->_db->insert_keywords_of_venues($id_venue, $i))
                        return false;
                } elseif (!isset($newKeywords[$i]) && isset($formerKeywords[$i])) {
                    if (!$this->_db->remove_keywords_of_venues($id_venue, $i))
                        return false;
                }
            }
        }

        return true;
    }

    /**
     * Checks the whole form for a venue add
     * Generate error messages and pu them into ['messages']
     * Keep the form fields in ['content']
     *
     * @return array of arrays: ['content'] and ['messages']
     */
    private function add_form_check()
    {
        $array = array();
        $messages = array(); //Messages for incorrectly filled fields
        $content = array(); //Fields'content of the venue and the address + needed elements to create an Adress and a Venue
        $content['submitter'] = $_SESSION['id_member'];

        ##Event date and time and type
        if (empty($_POST['start_datetime']) && empty($_POST['end_datetime'])) {
            $content['type'] = 'P';
            $content['start_datetime'] = NULL;
            $content['end_datetime'] = NULL;
        } else
            if (empty($_POST['start_datetime']) || empty($_POST['end_datetime'])) {
                $messages['start_datetime'] = 'Veuillez compléter les deux champs ou les laisser vides';
                $messages['end_datetime'] = 'Veuillez compléter les deux champs ou les laisser vides';
            } else {
                $content['start_datetime'] = $this->date_treatment_post_to_sql($_POST['start_datetime']);
                $content['end_datetime'] = $this->date_treatment_post_to_sql($_POST['end_datetime']);
                $content['type'] = 'E';
            }

        #Title
        if (empty($_POST['title']))
            $messages['title'] = 'Veuillez compléter le champ ci-dessus';
        else
            $content['title'] = $_POST['title'];

        #Address
        if (empty($_POST['latitude']) && empty($_POST['longitude'])) {
            $content['latitude'] = NULL;
            $content['longitude'] = NULL;
            $content['geographicData'] = false;
        } elseif (empty($_POST['latitude'])) {
            $messages['latitude'] = 'Remplissez ce champ ou laissez longitude vide';
            $messages['longitude'] = 'Videz ce champs ou remplissez latitude';
            $content['longitude'] = $_POST['longitude'];
        } elseif (empty($_POST['longitude'])) {
            $messages['latitude'] = 'videz ce champs ou remplissez longitude';
            $messages['longitude'] = 'Remplissez ce champ ou laissez latitude vide';
            $content['latitude'] = $_POST['latitude'];
        } else {
            $latitude = $this->comma_to_full_stop($_POST['latitude']);
            $longitude = $this->comma_to_full_stop($_POST['longitude']);

            if (!$this->decimal_degree_format_check($latitude))
                $messages['latitude'] = 'Format invalide, accepté: [-90.000000 ... 90.000000]';
            if (!$this->decimal_degree_format_check($longitude))
                $messages['longitude'] = 'Format invalide, accepté: [-90.000000 ... 90.000000]';

            if (!isset($messages['latitude']) && !isset($messages['longitude'])) {
                $content['latitude'] = $latitude;
                $content['longitude'] = $longitude;
                $content['geographicData'] = true;
            }
        }

        if (empty($_POST['country']))
            $messages['country'] = 'Veuillez compléter le champ ci-dessus';
        else
            $content['country'] = $_POST['country'];

        if (empty($_POST['city']))
            $messages['city'] = 'Veuillez compléter le champ ci-dessus';
        else
            $content['city'] = $_POST['city'];

        if (empty($_POST['postal_code']))
            $messages['postal_code'] = 'Veuillez compléter le champ ci-dessus';
        else
            if ($this->postal_code_format_check($_POST['postal_code']))
                $content['postal_code'] = $_POST['postal_code'];
            else
                $messages['postal_code'] = 'Format invalide';

        if (empty($_POST['street']))
            $messages['street'] = 'Veuillez compléter le champ ci-dessus';
        else
            $content['street'] = $_POST['street'];

        if (empty($_POST['number']))
            $messages['number'] = 'Veuillez compléter le champ ci-dessus';
        else
            $content['number'] = $_POST['number'];

        #Keywords
        if ($_POST['0'] != null)
            $content['0'] = $_POST['0'];

        if ($_POST['1'] != null && $_POST['1'] != $_POST['0'])
            $content['1'] = $_POST['1'];

        if ($_POST['2'] != null && $_POST['2'] != $_POST['0'] && $_POST['2'] != $_POST['1'])
            $content['2'] = $_POST['2'];

        #File
        if (empty($_FILES['userfile']['tmp_name']))
            if (!empty($_FILES['userfile']['error']) && ($_FILES['userfile']['error'] == 1 || $_FILES['userfile']['error'] == 2))
                $messages['photo'] = 'Taille de l\'image trop importante, veuillez en sélectionner une autre ou la réduire';
            else
                $messages['photo'] = 'Veuillez ajouter une image';

        //treatment only if every other fields are correctly filled in
        elseif (empty($messages)) {
            if ($this->is_uploaded_file_an_image('userfile')) {
                $content['photo'] = $this->uploaded_image_treatment('userfile');
            } else
                $messages['photo'] = 'Format invalide, veuillez ajouter un fichier image conforme';
        } else
            $messages['photo'] = 'Veuillez réintroduire votre image';

        $array['messages'] = $messages;
        $array['content'] = $content;

        return $array;
    }

    /**
     * Gets the keywords selected from the form
     *
     * @param $array
     * @return array
     */
    private function get_keywords_after_post($array)
    {
        $keywords = array();
        for ($i = 0; $i < 3; $i++) {
            if (isset($array["$i"]))
                $keywords[$i] = $array["$i"];
            else
                $keywords[$i] = null;
        }
        return $keywords;
    }

    /**
     * Constructs a Venue without id nor address
     *
     * @param $array string containing $array['title'], $array['photo'], $array['type'], $array['submitter']
     * having optionally $array['start_datetime'], $array['end_datetime']
     * @return Venue
     */
    private function construct_venue_after_post($array)
    {
        if (!isset($array['start_datetime']))
            return new Venue(NULL, $array['title'], $array['photo'], NULL, $array['type'], $array['submitter'], NULL, NULL);
        else
            return new Venue(NULL, $array['title'], $array['photo'], NULL, $array['type'], $array['submitter'], $array['start_datetime'], $array['end_datetime']);
    }

    /**
     * Constructs an Address without id
     *
     * @param $array string having $array['country'], $array['city'], $array['postal_code'], $array['street'], $array['number']
     * having optionally $array['latitude'], $array['longitude']
     * @return Address
     */
    private function construct_address_after_post($array)
    {
        if (!$array['geographicData'])
            return new Address(NULL, $array['country'], $array['city'], $array['postal_code'], $array['street'], $array['number'], NULL, NULL);
        else
            return new Address(NULL, $array['country'], $array['city'], $array['postal_code'], $array['street'], $array['number'], $array['latitude'], $array['longitude']);
    }

    /**
     * Add a venue in the database with Venue and Address
     * Address's id , venue's id and venue's address can be empty
     *
     * @param $keywords array of keywords id as $keywords['0'], $keywords['1'], $keywords['2']
     * @param $address Address
     * @param $venue Venue
     * @return bool true if every steps went right
     */
    private function add_venue($venue, $address, $keywords)
    {
        #address
        if (empty($address->getLatitude())) {
            if (!$this->_db->insert_address($address->getCountry(), $address->getCity(), $address->getPostalCode(), $address->getStreet(), $address->getNumber()))
                return false;
        } else
            if (!$this->_db->insert_complete_address($address->getCountry(), $address->getCity(), $address->getPostalCode(), $address->getStreet(), $address->getNumber(), $address->getLatitude(), $address->getLongitude()))
                return false;

        #venue
        if ($venue->getType() == 'P') {
            if (!$this->_db->insert_place_with_latest_address($venue->getTitle(), $venue->getPhoto(), $venue->getSubmitter()))
                return false;
        } else
            if (!$this->_db->insert_event_with_latest_address($venue->getTitle(), $venue->getPhoto(), $venue->getSubmitter(), $venue->getStartDatetime(), $venue->getEndDatetime()))
                return false;

        #keywords
        if (!empty($keywords['0']))
            if (!$this->_db->insert_keyword_of_latest_venue($keywords['0']))
                return false;

        if (!empty($keywords['1']))
            if (!$this->_db->insert_keyword_of_latest_venue($keywords['1']))
                return false;

        if (!empty($keywords['2']))
            if (!$this->_db->insert_keyword_of_latest_venue($keywords['2']))
                return false;

        return true;
    }
}