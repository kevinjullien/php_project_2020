<?php


class LoginController
{
    private $_db;


    public function __construct($_db)
    {
        $this->_db = $_db;
    }

    public function run()
    {
        $emailinput = '';
        $message = '';
        $logininfo = array();

        if (!empty($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) {
            header("Location: index.php?action=admin");
            die();
        }

        if (isset($_POST['loginMember'])) {

            if (empty($_POST['email'])) {
                $logininfo['emailmessage'] = 'Veuillez compléter le champs ci-dessus';
            }

            $emailinput = $_POST['email'];


            if (empty($_POST['password'])) {
                $logininfo['passwordmessage'] = 'Veuillez compléter le champs ci-dessus';

            } else {
                $user = $this->_db->select_member_by_email($emailinput);

                if ($user != NULL) {
                    if (password_verify($_POST['password'], $user->getPassword())) {
                        if ($user->getActivate() == 0) $message = 'Votre compte a été désactivé par un admin.';
                        else {
                            Login::login($user);
                            header("Location: index.php?action=home");
                            die();
                        }
                    }
                    else {
                        $logininfo['passwordmessage'] = 'Le mot de passe est incorrect';
                    }
                }
                else if (empty($logininfo)){
                    $message = 'Oh oh.. Cet utilisateur n\'existe pas !';
                }
            }
        }
            require_once(VIEW_PATH . 'login.php');

    }

}