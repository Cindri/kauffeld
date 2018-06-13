<?php

function newsletterChecked($val) {
    return (empty($val) ? "no" : "yes");
}

$this->view->assign("header", "Newsletter-Anmeldung");
$this->view->assign("title", "Metzgerei Kauffeld - Newsletter");
$this->view->assign("activeLink", "impressum");
$this->view->setTemplate("layout");

$pageContent = new View();
$pageContent->setTemplate("newsletter");

if (empty($this->request['submit']) || $this->request['submit'] != "Registrieren") {
    $this->view->assign("error", $this->view->errorBox("alert-danger", "Falscher Referer!", "Das Formular darf nur von der Original-Quelle abgesendet werden!"));
    $this->view->assign("pageContent", $pageContent->loadTemplate());
}
else {
    // Validate form
    // Entweder E-Mail oder Faxnummer (oder beides) müssen ausgefüllt sein
    if (empty(trim($this->request['email'])) AND empty($this->request['fax'])) {
        $error = true;
        $this->view->assign("error", $this->view->errorBox("alert-danger", "E-Mail oder Faxnummer angeben!", "Sie müssen zur Anmeldung entweder eine E-Mail-Adresse oder eine Faxnummer angeben!"));
        $this->view->assign("pageContent", $pageContent->loadTemplate());
    } else {
        // Wenn E-Mail: Ist gültige E-Mail?
        if (!empty(trim($this->request['email']))) {
            if (!filter_var(trim($this->request['email']), FILTER_VALIDATE_EMAIL)) {
                $error = true;
                $this->view->assign("error", $this->view->errorBox("alert-danger", "Ungültige E-Mail-Adresse!", "Die eingegebene E-Mail-Adresse entspricht nicht den gängigen Formaten. Bitte eine korrekte Adresse eingeben."));
                $this->view->assign("pageContent", $pageContent->loadTemplate());
            }
        }
    }

    // Es muss mindestens eine Karte ausgewählt sein
    if (empty($this->request['wochenkarte']) AND empty($this->request['hauptgeschaeft']) AND empty($this->request['rheinstrasse'])) {
        $error = true;
        $this->view->assign("error", $this->view->errorBox("alert-danger", "Wählen Sie eine Karte aus!", "Ihre Anmeldung kann nur verarbeitet werden, wenn Sie mindestens eine der 3 unten gelisteten Speisekarten auswählen."));
        $this->view->assign("pageContent", $pageContent->loadTemplate());
    }

    if (empty($error)) {

        // Nutzer in Datenbank eintragen, dazu Werte holen

        $data = new Model();
        $dbName = $data->getDbConn()->real_escape_string($this->request['name']);
        $dbStrasse = $data->getDbConn()->real_escape_string($this->request['adresse']);
        $dbStadt = $data->getDbConn()->real_escape_string($this->request['stadt']);
        $dbFax = $data->getDbConn()->real_escape_string($this->request['fax']);
        $dbEmail = $data->getDbConn()->real_escape_string($this->request['email']);
        $dbWillWochenkarte = newsletterChecked($this->request['wochenkarte']);
        $dbWillHauptgeschaeft = newsletterChecked($this->request['hauptgeschaeft']);
        $dbWillRheinstrasse = newsletterChecked($this->request['rheinstrasse']);

        $sql = "INSERT INTO newsletter (fax, email, strasse, name, stadt, willWochenkarte, willHauptgeschaeft, willRheinstrasse) VALUES (
        '".$dbFax."',
        '".$dbEmail."',
        '".$dbStrasse."',
        '".$dbName."',
        '".$dbStadt."',
        '".$dbWillWochenkarte."',
        '".$dbWillHauptgeschaeft."',
        '".$dbWillRheinstrasse."'
        )";

        $data->getDbConn()->query($sql) OR die($data->getDbConn()->error);

        $code = base64_encode($data->getDbConn()->insert_id);

        // Versende E-Mail an den Admin
        $message = '
        <html>
        <head></head>
        <body>
        <h2>Infomail an Admin des Info-Verteilers der Metzgerei Kauffeld</h2>
		<h3>Ein Kunde hat sich für den Info-Verteiler der Metzgerei Kauffeld eingetragen.</h3>
		<p>
		<b>Folgende Daten hat der Kunde angegeben:</b><br>
        E-Mail: ' . $dbEmail . '<br>
        Fax: ' . $dbFax . '<br>
        <p>
        <b>E-Mail-Anmeldung:</b><br>
		Sie müssen Sie nichts weiter tun, da der Kunde seine Anmeldung selbst über eine Bestätigungs-E-Mail bestätigen kann. Sie werden benachrichtigt, wenn der Kunde seine Anmeldung bestätigt hat.<br>
		<b>Die Bestätigungs-Mail bitte aufbewahren (egal, ob als Daten oder als Ausdruck)</b>
		<p>
		<b>Fax-Anmeldung:</b><br>
		Vorgefertigtes Fax „Mit der Bitte um Rückbestätigung“ an die obige Faxnummer senden.
        Kunde muss es unterschrieben zurückfaxen oder per Post senden. Diese Einwilligung ist seit der Einführung der DSGVO vom 25.05.2018 notwendig. <b>Das Bestätigungsfax bitte aufbewahren.</b>
        <p>
        Wenn der Kunde das Bestätigungsfax zurückgeschickt hat, klicken Sie bitte auf folgenden Link oder kopieren Ihn in die Adresszeile Ihres Browsers:<br>
        <a href="http://metzgerei-kauffeld.de/newsletterConfirm?code='.$code.'">Anmeldung bestätigen</a><br>
        <b>Ohne diese von Ihnen vorzunehmende Confirmation wird der Kunde keine Faxe bekommen.</b>
		<p>
		Mit freundlichen Grüßen<br>
		Webmailer auf www.metzgerei-kauffeld.de<br>
		Bei technischen Fragen wenden Sie sich direkt an Swen Panten.
		<p>
		Dies ist eine automatisch generierte E-Mail.
		</body>
		</html>
        ';
        $betreff = "Metzgerei Kauffeld - Neue Anmeldung am Newsletter";
        $header = 'From: newsletter@metzgerei-kauffeld.de' . "\r\n" .
            'Reply-To: info@metzgerei-kauffeld.de' . "\r\n" .
            'X-Mailer: PHP/' . phpversion() . "\r\n";
        $header .= "Content-Type: text/html; charset=UTF-8\n";
        mail(ADMIN_MAIL, $betreff, $message, $header);


        // Versende Registrierungs-E-Mail
        $empfaenger = $dbEmail;

        $message = '
            <h2>Metzgerei Kauffeld - Registrierung bestätigen</h2>
            Sehr geehrter Kunde,<br/>
            vielen Dank für Ihr Interesse an unserem Mittagskarten- und Wochenkartenverteiler!<br/>
            Um sich in den Verteiler einzutragen, nutzen Sie bitte folgenden Bestätigungs-Link:<br/>
            <a href="http://metzgerei-kauffeld.de/newsletterConfirm?code='.$code.'">Anmeldung bestätigen</a><br/><br/>
            Viele Grüße,<br/>
            Ihr Metzgerei-Kauffeld Team
        ';

        $betreff = "Metzgerei Kauffeld - Newsletter Anmeldung bestätigen";
        $nachricht = wordwrap($message, 75);
        $header = 'From: newsletter@metzgerei-kauffeld.de' . "\r\n" .
            'Reply-To: info@metzgerei-kauffeld.de' . "\r\n" .
            'X-Mailer: PHP/' . phpversion() . "\r\n";
        $header .= "Content-Type: text/html; charset=UTF-8\n";
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