<?php

require ("classes/models/Recipients.php");

$this->view->assign("activeLink", $this->page);
$this->view->assign("title", "Metzgerei Kauffeld - Admin Newsletter");
$this->view->assign("header", "Administration - Newsletter");

$dbconn = new Model();
$dbReturn = $dbconn->getWholeTable('newsletter', 'name ASC');

$adminSubpage = new View();
$adminSubpage->setTemplate("admin/adminNewsletter");
$adminSubpage->assign("entries", $cateringType);
$adminSubpage->assign("subNavi", $data->subpagesArray);
$adminSubpage->assign("activeLink", "admin/newsletter");

if (!empty($dbReturn['error'])) {
    $adminSubpage->assign("error", $dbReturn['error']);
}
else {
    $recipients = array();
    foreach ($dbReturn as $key => $value) {
        if ($key == "error") { continue; }
        $recipient = new Recipients();
        $recipient->setId($value->ID);
        $recipient->setFax($value->fax);
        $recipient->setEmail($value->email);
        $recipient->setStrasse($value->strasse);
        $recipient->setName($value->name);
        $recipient->setStadt($value->stadt);
        $recipient->setConfirmed(boolval($value->confirmed));
        $recipient->setWillHauptgeschaeft($recipient->strToBool($value->willHauptgeschaeft));
        $recipient->setWillRheinstrasse($recipient->strToBool($value->willRheinstrasse));
        $recipient->setWillWochenkarte($recipient->strToBool($value->willWochenkarte));
        $recipients[$key] = $recipient;

    }

//    print '<pre>';
//    var_dump($recipients);
//    print '</pre>';

    $adminSubpage->assign("entries", $recipients);
    $adminSubpage->assign("type", $cateringType);
}

$adminSubpage->assign("token", $login->getToken());
$adminSubpageContent = $adminSubpage->loadTemplate();