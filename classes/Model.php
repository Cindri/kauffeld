<?php

/**
 * Created by PhpStorm.
 * User: David
 * Date: 01.08.2016
 * Time: 11:19
 */
class Model
{
    private $dbConn;
    private $locale;
    private $creationDate;
    private $valid;
    private $adText;

    public function __construct()
    {
        $this->dbConn = new mysqli("localhost", "root", "", "kauffeld");
        $this->locale = "de_DE";
        $this->creationDate = new DateTime();
        $this->valid = new DateTime();
        $this->adText = "";
    }

    public function getDbConn()
    {
        return $this->dbConn;
    }

}