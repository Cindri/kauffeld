<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 25.09.2016
 * Time: 18:39
 */

if (empty($_POST['edited']) || !is_array($_POST['edited'])) {
    die('Zugriff verweigert!');
}

function bool2words($bool) {
    return empty($bool) ? "no" : "yes";
}

$token = $this->request['token'];
$data = new Model();

foreach ($_POST['edited'] as $key => $value) {
    if (empty($value)) {
        continue;
    }

    $updateArray = array(
        'name' => $_POST['name'][$key],
        'confirmed' => !empty($_POST['confirmed'][$key]),
        'strasse' => $_POST['strasse'][$key],
        'stadt' => $_POST['stadt'][$key],
        'email' => $_POST['email'][$key],
        'fax' => $_POST['fax'][$key],
        'willWochenkarte' => bool2words(intval($_POST['willWochenkarte'][$key])),
        'willHauptgeschaeft' => bool2words(intval($_POST['willHauptgeschaeft'][$key])),
        'willRheinstrasse' => bool2words(intval($_POST['willRheinstrasse'][$key]))
    );


    if (!$data->updateTable('newsletter', $updateArray, $key)) {
        die('SQL-Fehler: '.$data->getDbConn()->error);
    }

    header('Location: ' . BASE_URL . 'admin/newsletter?token=' . $token);
}