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

    public function __construct()
    {
        $this->dbConn = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
        $this->dbConn->query("SET NAMES 'utf8'");
        $this->locale = "de_DE";
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