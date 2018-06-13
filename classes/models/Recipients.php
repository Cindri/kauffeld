<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 25.09.2016
 * Time: 12:44
 */

class Recipients {
    protected $id = 0;

    protected $fax = '';

    protected $email = '';

    protected $confirmed = false;

    protected $date_register = null;

    protected $date_confirmed = null;

    protected $date_unregister = null;

    protected $willWochenkarte = false;

    protected $willHauptgeschaeft = false;

    protected $willRheinstrasse = false;

    protected $fromDb = false;

    public function __construct($id = 0, Model $model = null){
        if (!empty($id)) {
            if ($model != null) {
                $result = $model->getDbConn()->query('SELECT * FROM newsletter WHERE ID = \''.$model->getDbConn()->escape_string(strval($id)).'\'');
                $row = $result->fetch_object();
                $this->id = $row->ID;
                $this->fax = $row->fax;
                $this->email = $row->email;
                $this->confirmed = boolval($row->confirmed);
                $this->date_register = new DateTime($row->date_register);
                $this->date_confirmed = new DateTime($row->date_confirmed);
                $this->date_unregister = new DateTime($row->date_unregister);
                $this->willWochenkarte = $this->strToBool($row->willWochenkarte);
                $this->willRheinstrasse = $this->strToBool($row->willRheinstrasse);
                $this->willHauptgeschaeft = $this->strToBool($row->willHauptgeschaeft);
                $this->fromDb = true;
            }
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return boolean
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param boolean $confirmed
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    /**
     * @return DateTime|null
     */
    public function getDateRegister()
    {
        return $this->date_register;
    }

    /**
     * @param DateTime|null $date_register
     */
    public function setDateRegister($date_register)
    {
        $this->date_register = $date_register;
    }

    /**
     * @return DateTime|null
     */
    public function getDateConfirmed()
    {
        return $this->date_confirmed;
    }

    /**
     * @param DateTime|null $date_confirmed
     */
    public function setDateConfirmed($date_confirmed)
    {
        $this->date_confirmed = $date_confirmed;
    }

    /**
     * @return DateTime|null
     */
    public function getDateUnregister()
    {
        return $this->date_unregister;
    }

    /**
     * @param DateTime|null $date_unregister
     */
    public function setDateUnregister($date_unregister)
    {
        $this->date_unregister = $date_unregister;
    }



    /**
     * @return boolean
     */
    public function isWillWochenkarte()
    {
        return $this->willWochenkarte;
    }

    /**
     * @param boolean $willWochenkarte
     */
    public function setWillWochenkarte($willWochenkarte)
    {
        $this->willWochenkarte = $willWochenkarte;
    }

    /**
     * @return boolean
     */
    public function isWillHauptgeschaeft()
    {
        return $this->willHauptgeschaeft;
    }

    /**
     * @param boolean $willHauptgeschaeft
     */
    public function setWillHauptgeschaeft($willHauptgeschaeft)
    {
        $this->willHauptgeschaeft = $willHauptgeschaeft;
    }

    /**
     * @return boolean
     */
    public function isWillRheinstrasse()
    {
        return $this->willRheinstrasse;
    }

    /**
     * @param boolean $willRheinstrasse
     */
    public function setWillRheinstrasse($willRheinstrasse)
    {
        $this->willRheinstrasse = $willRheinstrasse;
    }

    /**
     * @return boolean
     */
    public function isFromDb()
    {
        return $this->fromDb;
    }

    /**
     * @param boolean $fromDb
     */
    public function setFromDb($fromDb)
    {
        $this->fromDb = $fromDb;
    }



    public function strToBool($string) {
        switch ($string) {
            case "yes":
                return true;
            default:
                return false;
        }
    }
}