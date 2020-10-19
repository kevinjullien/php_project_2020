<?php


class ContactController
{
    public function __construct()
    {

    }

    public function run()
    {
        $message = '';
        $messageFieldContent = '';
        if (!empty($_POST)) {


            if (empty($_SESSION['authenticated']) && empty($_POST['email']))
                $mailHelp = 'Entrez un email non vide!';
            if (empty($_POST['message']))
                $messageHelp = 'Entrez un message non vide!';
            else
                $messageFieldContent = $_POST['message'];
            if (!isset($mailHelp) & !isset($messageHelp)) {
                $to = EMAIL;
                $messageFieldContent = $_POST['message'];

                if (!empty($_SESSION['authenticated']))
                    $fromEmail = $_POST['email'];
                else
                    $fromEmail = $_SESSION['email'];

                $headers = 'From: ' . $fromEmail;

                if ($this->match($fromEmail)) {
                    if (mail($to, 'Message', $messageFieldContent, $headers))
                        $message = 'Vos informations ont été transmises avec succès.';
                    else
                        $message = 'Vos informations n\'ont pas été transmises.';

                } else
                    $emailHelp = 'Veuillez entrer une adresse email correcte';
            }
        }

        require_once(VIEW_PATH . 'contact.php');
    }

    public function match($value)
    {
        if (preg_match('/^([a-zA-Z0-9]+(([\.\-\_]?[a-zA-Z0-9]+)+)?)\@(([a-zA-Z0-9]+[\.\-\_])+[a-zA-Z]{2,4})$/', $value)) {
            return true;
        } else
            return false;
    }

}