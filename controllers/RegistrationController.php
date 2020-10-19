<?php


class RegistrationController
{
    private $_db;


    public function __construct($_db)
    {
        $this->_db = $_db;
    }


    public function run()
    {
        $message = '';

        //if already logged -> homepage
        if (!empty($_SESSION['authenticated'])) {
            header("Location:index.php");
            die();
        }


        //Registration asked
        if (isset($_POST['memberRegistration'])) {
            $formInputs = $this->registration_form_check();

            //If every fields are filled in correctly
            if (empty($formInputs['messages'])) {

                if ($this->insert_member_in_database()) {
                    $member = $this->_db->select_member_by_email($_POST['email']);
                    Login::login($member);
                    header("Location: index.php");
                    die();
                } else
                    $message = 'Un problème est survenu, veuillez réessayer plus tard ou nous contacter si cela se reproduit';
            }
        }
        require_once(VIEW_PATH . 'registration.php');
    }

    /**
     * Check the conformity of the fields in the registration form.
     * array['content'] will keep the content of the fields except passwords to avoid any privacy and security issue
     * array['messages'] will keep the messages of the fields not/incorrectly filled in
     *
     * @return array of arrays of String
     */
    public function registration_form_check()
    {
        $array = array();
        $messages = array(); //Messages for incorrectly filled fields
        $content = array(); //Content already given in a field by the user

        if (empty($_POST['firstname']))
            $messages['firstname'] = 'Veuillez compléter le champ ci-dessus';
        else
            $content['firstname'] = htmlspecialchars($_POST['firstname']);

        if (empty($_POST['lastname']))
            $messages['lastname'] = 'Veuillez compléter le champ ci-dessus';
        else
            $content['lastname'] = htmlspecialchars($_POST['lastname']);

        if (empty($_POST['email']))
            $messages['email'] = 'Veuillez compléter le champ ci-dessus';
        else {
            $content['email'] = htmlspecialchars($_POST['email']);
            if (!preg_match('/^.+\..+\@(student\.)?(vinci\.be)$/', $_POST['email']))
                $messages['email'] = "Formats valides: prenom.nom@vinci.be ou prenom.nom@student.vinci.be";
            elseif ($this->_db->select_member_by_email($_POST['email']) != null)
                $messages['email'] = 'L\'adresse e-mail existe déjà';
        }

        if (empty($_POST['password']) || empty($_POST['passwordbis']))
            $messages['password'] = 'Veuillez compléter les deux champs de mot de passe';
        elseif ($_POST['password'] != $_POST['passwordbis'])
            $messages['password'] = 'Les mots de passe ne concordent pas';

        $array['messages'] = $messages;
        $array['content'] = $content;

        return $array;
    }

    /**
     * Try to insert the member in the database
     *
     * @return boolean true if the insertion is a success, false if not
     */
    public function insert_member_in_database()
    {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); //password encryption
        return $this->_db->insert_member($_POST['email'], $_POST['firstname'], $_POST['lastname'], $password);
    }

}