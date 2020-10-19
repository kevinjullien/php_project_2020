<?php


class Venue
{
    private $_id;
    private $_title;
    private $_photo;
    private $_address;
    private $_type; //E for event, P for place
    private $_submitter;
    private $_start_datetime;
    private $_end_datetime;


    public function __construct($id, $title, $photo, $address, $type, $submitter, $start_datetime, $end_datetime)
    {
        $this->_id = $id;
        $this->_title = $title;
        $this->_photo = $photo;
        $this->_address = $address;
        $this->_type = $type;
        $this->_submitter = $submitter;
        $this->_start_datetime = $start_datetime;
        $this->_end_datetime = $end_datetime;
    }


    public function getId()
    {
        return $this->_id;
    }


    public function getTitle()
    {
        return $this->_title;
    }


    public function getPhoto()
    {
        return $this->_photo;
    }


    public function getAddress()
    {
        return $this->_address;
    }


    public function getType()
    {
        return $this->_type;
    }


    public function getSubmitter()
    {
        return $this->_submitter;
    }


    public function getStartDatetime()
    {
        return $this->_start_datetime;
    }


    public function getEndDatetime()
    {
        return $this->_end_datetime;
    }

    public function html_getId()
    {
        return htmlspecialchars($this->_id);
    }

    public function html_getTitle()
    {
        return htmlspecialchars($this->_title);
    }

    public function html_getAddress()
    {
        return htmlspecialchars($this->_address);
    }


    public function html_getPhoto()
    {
        return htmlspecialchars($this->_photo);
    }


    public function html_getType()
    {
        return htmlspecialchars($this->_type);
    }


    public function html_getSubmitter()
    {
        return htmlspecialchars($this->_submitter);
    }


    public function html_getStartDatetime()
    {
        return htmlspecialchars($this->_start_datetime);
    }


    public function html_getEndDatetime()
    {
        return htmlspecialchars($this->_end_datetime);
    }


}