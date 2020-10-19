<?php


class Address
{
    private $_id;
    private $_country;
    private $_city;
    private $_postal_code;
    private $_street;
    private $_number;
    private $_latitude;
    private $_longitude;


    public function __construct($id, $country, $city, $postal_code, $street, $number, $latitude, $longitude)
    {
        $this->_id = $id;
        $this->_country = $country;
        $this->_city = $city;
        $this->_postal_code = $postal_code;
        $this->_street = $street;
        $this->_number = $number;
        $this->_latitude = $latitude;
        $this->_longitude = $longitude;
    }


    public function getId()
    {
        return $this->_id;
    }


    public function getCountry()
    {
        return $this->_country;
    }


    public function getCity()
    {
        return $this->_city;
    }


    public function getPostalCode()
    {
        return $this->_postal_code;
    }


    public function getStreet()
    {
        return $this->_street;
    }


    public function getNumber()
    {
        return $this->_number;
    }


    public function getLatitude()
    {
        return $this->_latitude;
    }


    public function getLongitude()
    {
        return $this->_longitude;
    }


    public function html_getId()
    {
        return htmlspecialchars($this->_id);
    }


    public function html_getCountry()
    {
        return htmlspecialchars($this->_country);
    }


    public function html_getCity()
    {
        return htmlspecialchars($this->_city);
    }


    public function html_getPostalCode()
    {
        return htmlspecialchars($this->_postal_code);
    }


    public function html_getStreet()
    {
        return htmlspecialchars($this->_street);
    }


    public function html_getNumber()
    {
        return htmlspecialchars($this->_number);
    }


    public function html_getLatitude()
    {
        return htmlspecialchars($this->_latitude);
    }


    public function html_getLongitude()
    {
        return htmlspecialchars($this->_longitude);
    }
}