<?php


class Login
{
    public function __construct()
    {
    }

    public static function login($user){
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $user->getFirstname();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['id_member'] = $user->getId();
        if($user->isAdmin() == 1)
            $_SESSION['admin'] = true;
        else
            $_SESSION['admin'] = false;
    }

}
