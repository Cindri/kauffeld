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
        $this->adText = "Weil Gutes eben gut schmeckt!";
    }

    // ----------------- GETTER UND SETTER --------------------- //

    public function getDbConn()
    {
        return $this->dbConn;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getAdText()
    {
        return $this->adText;
    }

    public function getValid()
    {
        return $this->valid;
    }

    public function setValid($valid)
    {
        $this->valid = $valid;
    }

    public function setAdText($adText)
    {
        $this->adText = $adText;
    }

    // ---------------------- GETTER UND SETTER ENDE ------------------------- //

    public function dbDelete($table, $id) {
        $table = $this->dbConn->real_escape_string($table);
        $id = $this->dbConn->real_escape_string($id);
        $sql = "DELETE FROM $table WHERE ID = '$id'";
        $this->dbConn->query($sql);
        if (empty($this->dbConn->error)) {
            return true;
        }
        return false;
    }

    public function getWholeTable($table, $sort = "ID ASC") {
        $return = array();
        $table = $this->dbConn->real_escape_string($table);
        $sort = $this->dbConn->real_escape_string($sort);
        $sql = "SELECT * FROM $table ORDER BY $sort";
        $res = $this->dbConn->query($sql);
        if (empty($this->dbConn->error)) {
            if ($res->num_rows != 0) {
                while ($row = $res->fetch_object()) {
                    $return[] = $row;
                    $return['msg'] = "";
                }
            } else {
                $return['msg'] = "Keine Datensätze vorhanden";
            }
        } else {
            $return['msg'] = $this->dbConn->error;
        }
        return $return;
    }
}