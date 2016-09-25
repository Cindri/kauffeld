<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 01.08.2016
 * Time: 13:16
 */

require_once("config.php");

require_once('classes/MainController.php');
require_once('classes/Model.php');
    require_once ('classes/models/Mittagstisch.php');
    require_once ('classes/models/Catering.php');
    require_once ('classes/models/Wochenkarte.php');
require_once('classes/View.php');

require_once "classes/FileHandler.php";
require_once "classes/Login.php";

function htmlCallback(&$item) {
    $item = htmlspecialchars($item);
}

// $_GET und $_POST zusammenfasen
$request = array_merge($_GET, $_POST);
// Controller erstellen
$controller = new Controller($request);
// Inhalt der Webanwendung ausgeben.
echo $controller->display();