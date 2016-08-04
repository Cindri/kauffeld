<?php

$this->view->assign("header", "Ihr Kontakt zu uns");
$this->view->assign("title", "Metzgerei Kauffeld - Kontakt/Impressum");
$this->view->assign("activeLink", "");
$this->view->setTemplate("layout");

$pageContent = new View();
$pageContent->setTemplate("impressum");

foreach ($this->request as $key => $value) {
    if (strpos($key, "_") != false) {
        $postKey = explode("_", $key);
        if ($postKey[0] == "contactForm") {
            $pageContent->assign($postKey[0] . "_" . $postKey[1], $value);
        }
    }
}

if (empty($this->request['contactForm_submit']) || $this->request['contactForm_submit'] != "Absenden") {
    $this->view->assign("error", $this->view->errorBox("alert-danger", "Falscher Referer!", "Das Formular darf nur von der Original-Quelle abgesendet werden!"));
    $this->view->assign("pageContent", $pageContent->loadTemplate());
}
else {

    // Validate form
    if (!filter_var(trim($this->request['contactForm_email']), FILTER_VALIDATE_EMAIL)) {
        $this->view->assign("error", $this->view->errorBox("alert-danger", "Ungültige E-Mail-Adresse!", "Die eingegebene E-Mail-Adresse entspricht nicht den gängigen Formaten. Bitte eine korrekte Adresse eingeben."));
        $this->view->assign("pageContent", $pageContent->loadTemplate());
    }
    else if (trim($this->request['contactForm_msg']) == "") {
        $this->view->assign("error", $this->view->errorBox("alert-danger", "Leere Nachricht!", "Bitte keine leeren Nachrichten verschicken."));
        $this->view->assign("pageContent", $pageContent->loadTemplate());
    }
    else {
        // check for empty subject line, but still send the message if its empty
        if (trim($this->request['contactForm_subject']) == "") {
            $this->view->assign("error", $this->view->errorBox("alert-warning", "Kein Betreff angegeben!", "Ihre Nachricht wird versendet, jedoch wurde kein Betreff angegeben. Dies kann z.B. dazu führen, dass Ihre Nachricht in unserem Spamordner landet und nicht oder verspätet bearbeitet wird. Um sicher zu gehen, versenden Sie die gleiche Nachricht noch einmal mit Betreff."));
        }

        // Everything is fine, start email delivery
        $empfaenger = 'davidpeter1337@gmail.com';
        $betreff = trim(substr($this->request['contactForm_subject'], 0, 255));
        $nachricht = wordwrap($this->request['contactForm_msg'], 75);
        $header = 'From: kontakt@metzgerei-kauffeld.de' . "\r\n" .
            'Reply-To: info@metzgerei-kauffeld.de' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        if (!mail($empfaenger, $betreff, $nachricht, $header)) {
            $this->view->assign("error", $this->view->errorBox("alert-danger", "Unbekannter Fehler beim Mailversand!", "Ihre Nachricht konnte nicht gesendet werden. Bitte versuchen Sie es später noch einmal, rufen Sie uns an oder schreiben Sie uns eine E-Mail."));
        }
        else {
            if (empty($this->view->_['error'])) {
                $this->view->assign("error", $this->view->errorBox("alert-success", "Mail erfolgreich versendet!", "Ihre Nachricht wurde erfolgreich versendet und sollte in wenigen Minuten bei uns eintreffen!"));
            }
            else {
                $this->view->_['error'] .= $this->view->errorBox("alert-success", "Mail erfolgreich versendet!", "Ihre Nachricht wurde erfolgreich versendet. Bitte beachten Sie dennoch die sonstigen Meldungen!");
            }
        }
    }
    $this->view->assign("pageContent", $pageContent->loadTemplate());
}
