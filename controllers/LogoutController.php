<?php
class LogoutController{

    public function __construct() {

    }

    public function run(){
        # (re)set the array of session variables
        $_SESSION = array();

        # destroy the session
        session_destroy();

        # This controller doesn't have a view, it redirects the user to the home page
        header("Location: index.php");
        die();
    }

}
?>