<?php

require("../../../config.php");
require("../../Model.php");

$data = new Model();

header('Content-type: text/plain');

    if (!$_POST['handler'] == 'fghbjnrefdhegwqerivcniawie') {
        die('Zugriff verweigert.');
    }

    switch ($_POST['table']) {
        case 'newsletter':
            switch ($_POST['action']) {
                case "delete":
                    if ($data->dbDelete('newsletter', $_POST['id'])) {
                        echo 'Empfänger ID '.$_POST['id'].' erfolgreich gelöscht!';
                    } else {
                        echo 'Fehler beim Löschen des Eintrags!';
                    }
                    break;
                case "setConfirmed":
                    $confirm = empty($_POST['confirmed']) ? 0 : 1;
                    $sql = 'UPDATE newsletter SET confirmed = \''.$confirm.'\' WHERE ID = \''.$_POST['id'].'\'';
                    if ($data->getDbConn()->query($sql)) {
                        echo 'Status des Versand-Empfangs auf '.$confirm.' gesetzt.';
                    } else {
                        echo $data->getDbConn()->error;
                    }
                    break;
            }
            break;
        default:
            die('Schwerer Fehler. Abbruch.');
            break;
    }