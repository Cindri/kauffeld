<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 12.08.2016
 * Time: 11:22
 */

class Login extends Model {
    private $loginTime;
    private $token;
    private $ip;
    private $dbId = "";

    private $error = "";

    public function __construct($token, $ip)
    {
        parent::__construct();
        $this->loginTime = new DateTime();
        $this->token = $token;
        $this->ip = $ip;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function randomString($length = 6) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function verifyToken() {
        $sql = "SELECT time, ip FROM login WHERE token = '".$this->token."'";
        $res = parent::getDbConn()->query($sql);

        if ($res->num_rows == 0) {
            $this->error = "Token existiert nicht in der Datenbank.";
            return false;
        }

        // Passt die zum Token übergebene ID zu der in der DB?
        $dbTokenInfo = $res->fetch_object();
        $this->dbId = $dbTokenInfo->ID;
        if ($this->ip != $dbTokenInfo->ip) {
            $this->error = "Die IP zum Token stimmen nicht überein.";
            return false;
        }
        $dateDb = new DateTime($dbTokenInfo->time);
        $diff = $this->loginTime->diff($dateDb, true);
        if (intval($diff->format("%s")) > 1800) {
            $this->error = "Zu viel Zeit seit dem letzten Aufruf. Login zurückgesetzt";
            return false;
        }
        return true;
    }

    public function logout() {
        if (parent::dbDelete("login", $this->dbId)) {
            return true;
        }
        return false;
    }

    public function checkPw($pw) {
        return hash_equals(password_hash(ADMIN_PASS, PASSWORD_BCRYPT), password_hash($pw, PASSWORD_BCRYPT));
    }
}