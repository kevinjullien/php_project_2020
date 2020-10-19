<?php


class Keyword
{
    private $_id_keyword;
    private $_label;


    public function __construct($id_keyword, $label)
    {
        $this->_id_keyword = $id_keyword;
        $this->_label = $label;

    }


    public function getId()
    {
        return $this->_id_keyword;
    }


    public function getLabel()
    {
        return $this->_label;
    }




}