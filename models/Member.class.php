<?php


class Member
{
    private $_id;
    private $_password;
    private $_email;
    private $_is_admin;
    private $_activate;
    private $_lastname;
    private $_firstname;


    public function __construct($id, $password, $email, $is_admin, $activate, $lastname, $firstname)
    {
        $this->_id = $id;
        $this->_password = $password;
        $this->_email = $email;
        $this->_is_admin = $is_admin;
        $this->_activate = $activate;
        $this->_lastname = $lastname;
        $this->_firstname = $firstname;
    }


    public function getId()
    {
        return $this->_id;
    }


    public function getPassword()
    {
        return $this->_password;
    }


    public function getEmail()
    {
        return $this->_email;
    }


    public function isAdmin()
    {
        return $this->_is_admin;
    }


    public function getActivate()
    {
        return $this->_activate;
    }


    public function getLastname()
    {
        return $this->_lastname;
    }


    public function getFirstname()
    {
        return $this->_firstname;
    }



    public function html_getId()
    {
        return htmlspecialchars($this->_id);
    }


    public function html_getPassword()
    {
        return htmlspecialchars($this->_password);
    }


    public function html_getEmail()
    {
        return htmlspecialchars($this->_email);
    }


    public function html_isAdmin()
    {
        return htmlspecialchars($this->_is_admin);
    }


    public function html_getActivate()
    {
        return htmlspecialchars($this->_activate);
    }


    public function html_getLastname()
    {
        return htmlspecialchars($this->_lastname);
    }


    public function html_getFirstname()
    {
        return htmlspecialchars($this->_firstname);
    }
}