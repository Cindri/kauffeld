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

    public function getToken() {
        return $this->token;
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
        $sql = "SELECT ID, time, ip FROM login WHERE token = '".$this->token."'";
        if (!$res = parent::getDbConn()->query($sql)) {
            $this->error = "SQL-Fehler: ".parent::getDbConn()->error;
            return false;
        }

        if ($res->num_rows == 0) {
            $this->error = "Token existiert nicht in der Datenbank. Bitte nicht über alte Links in der History oder über manuelle Token-Eingaben einloggen.";
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
        if (sha1(ADMIN_PASS) == sha1($pw)) {
            return true;
        } else {
            $this->error = "Das eingegebene Passwort stimmt nicht mit dem Admin-Passwort überein.";
            return false;
        }
    }

    public function login() {
        $time = $this->loginTime->format("Y-m-d H:i:s");
        $sql = "SELECT token FROM login WHERE ip = '" . $_SERVER['REMOTE_ADDR'] . "' AND TIMESTAMPDIFF(MINUTE, time, NOW()) < 60";
        $res = parent::getDbConn()->query($sql);
        if ($res->num_rows > 0) {
            $row = $res->fetch_object();
            $token = $row->token;
            $this->updateTime($token);
            return $token;
        } else {
            $token = $this->randomString(20);
            $sql = "INSERT INTO login (time, ip, token) VALUES ('$time', '" . $_SERVER['REMOTE_ADDR'] . "', '$token')";
            if ($this->getDbConn()->query($sql)) {
                return $token;
            }
        }
    }

    public function updateTime($token) {
        $time = $this->loginTime->format("Y-m-d H:i:s");
        $sql = "UPDATE login SET time = '$time' WHERE token = '$token'";
        if ($this->getDbConn()->query($sql)) {
            return true;
        }
        $this->error = "Session konnte nicht erneuert werden (Timer für Logout nach einer Stunde wurde nicht zurückgesetzt).";
        return false;
    }
}