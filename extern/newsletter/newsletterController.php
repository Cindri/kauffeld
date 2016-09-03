<?php
$type = $_GET['type'];
$mysqli = new mysqli("wp126.webpack.hosteurope.de", "db1091580-kauf", "%;XDQd!q@zpr", "db1091580-kauffeld");
$mysqli->query("SET NAMES 'utf8'");

if ($_GET['key'] != "UyuJCQRwT2C4XJp2hur1SWaC6DlwV3PTVMhtiqxv") {
    die("Keine manuellen Aufrufe!");
}

switch ($type) {

    case "mittagstisch":

        // Daten holen
        $subpage = $_GET['subpage'];

        // Ermittle neueste Karte
        $sql = "SELECT ID FROM mittagskarten WHERE geschaeft = '$subpage' ORDER BY startDate DESC";
        $result = $mysqli->query($sql);
        $row = $result->fetch_object();
        $cardID = $row->ID;

        $sql = "SELECT `pdfUrl` FROM `mittagskarten` WHERE `ID` = '$cardID'";
        $result = $mysqli->query($sql);
        if ($result->num_rows < 1) {
            die("Aktuelle Karte ist noch nicht gültig! (Das Start-Datum liegt vor dem jetzigen Datum)");
        }
        $row = $result->fetch_object();
        if (!empty($row->pdfUrl)) {
            $pdf = "../".$row->pdfUrl;
        } else {
            die("Noch kein PDF vorhanden! Bitte zuerst ein PDF für diese Karte erstellen!");
        }


        // Konfiguration
        $cardName = "Metzgerei_Kauffeld_Wochenmenü.pdf";
        $mailbetreff = "Metzgerei Kauffeld Wochenmenü";
        $mailstring = '
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8" />
            </head>
            <body>
                <h2>Metzgerei Kauffeld - Mittagskarte</h2>Sehr geehrte Abonnentin, sehr geehrter Abonnent,<br/>anbei erhalten Sie die von Ihnen abonnierte Menükarte(n) / Angebot(e) unseres Hauses als PDF-Dokument(e).<br/></br>Mit freundlichen Grüßen<br/>Ihr Kauffeld-Team
                <br><br>------------------------<br><br>
Falls Sie diesen Newsletter nicht mehr erhalten wollen, können Sie sich unter folgendem Link abmelden:<br>
<a href="http://extern.panten.de/kauffeld/newsletter/newsletterController.php?key=UyuJCQRwT2C4XJp2hur1SWaC6DlwV3PTVMhtiqxv&type=unregister&email=%%MAIL%%">Hier austragen</a>
            </body>
        </html>
        ';

        if ($subpage == "hauptgeschaeft") {
            $addWhere = "`willHauptgeschaeft` = 'yes'";
        } else {
            $addWhere = "`willRheinstrasse` = 'yes'";
        }
        break;
    case "wochenkarte":

        // Daten holen

        // Ermittle ID der neuesten Karte
        $sql = "SELECT ID FROM wochenkarten ORDER BY startDate DESC";
        $result = $mysqli->query($sql);
        $row = $result->fetch_object();
        $cardID = $row->ID;

        $addWhere = "`willWochenkarte` = 'yes'";
        $sql = "SELECT `pdfUrl` FROM `wochenkarten` WHERE `ID` = '$cardID'";
        $result = $mysqli->query($sql);
        if ($result->num_rows < 1) {
            die("Noch kein PDF vorhanden! Bitte zuerst ein PDF für diese Karte erstellen!");
        }
        $row = $result->fetch_object();
        $pdf = "../".$row->pdfUrl;

        // Konfiguration
        $cardName = "Metzgerei_Kauffeld_Wochenangebot.pdf";
        $mailbetreff = "Metzgerei Kauffeld Wochenangebot";
        $mailstring = '
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8" />
            </head>
            <body>
                <h2>Metzgerei Kauffeld - Wochenkarte</h2>Sehr geehrte Abonnentin, sehr geehrter Abonnent,<br/>anbei erhalten Sie die von Ihnen abonnierte Menükarte(n) / Angebot(e) unseres Hauses als PDF-Dokument(e).<br/></br>Mit freundlichen Grüßen<br/>Ihr Kauffeld-Team
                <br><br>------------------------<br><br>
Falls Sie diesen Newsletter nicht mehr erhalten wollen, können Sie sich unter folgendem Link abmelden:<br>
<a href="http://extern.panten.de/kauffeld/newsletter/newsletterController.php?key=UyuJCQRwT2C4XJp2hur1SWaC6DlwV3PTVMhtiqxv&type=unregister&email=%%MAIL%%">Hier austragen</a>
            </body>
        </html>
        ';

        break;
    case "catering":
        die("Catering soll nicht mehr versendet werden. Abbruch.");
        /*
        $addWhere = "1";
        // Daten holen
        $cardID = $_GET['subpage'];
        $sql = "SELECT `pdfUrl` FROM `catering_meta` WHERE `type` = '$cardID'";
        $result = $mysqli->query($sql);
        if ($result->num_rows < 1) {
            die("Noch kein PDF vorhanden! Bitte zuerst ein PDF für diese Karte erstellen!");
        }
        $row = $result->fetch_object();
        $pdf = "../".$row->pdfUrl;

        // Konfiguration
        $cardName = "Metzgerei_Kauffeld_Catering_Angebot.pdf";
        $mailbetreff = "Metzgerei Kauffeld - Catering-Angebot";
        $mailstring = '
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8" />
            </head>
            <body>
                <h2>Metzgerei Kauffeld - Catering-Angebote</h2>Sehr geehrte Abonnentin, sehr geehrter Abonnent,<br/>anbei erhalten Sie die von Ihnen abonnierte Menükarte(n) / Angebot(e) unseres Hauses als PDF-Dokument(e).<br/></br>Mit freundlichen Grüßen<br/>Ihr Kauffeld-Team
                <br><br>------------------------<br><br>
Falls Sie diesen Newsletter nicht mehr erhalten wollen, können Sie sich unter folgendem Link abmelden:<br>
<a href="http://extern.panten.de/kauffeld/newsletter/newsletterController.php?key=UyuJCQRwT2C4XJp2hur1SWaC6DlwV3PTVMhtiqxv&type=unregister&email=%%MAIL%%">Hier austragen</a>
            </body>
        </html>
        ';
        */
        break;
    case "unregister";
        $unregMail = $mysqli->real_escape_string($_GET['email']);
        $sql = "UPDATE `newsletter` SET `confirmed` = '0' WHERE `email` = '$unregMail'";
        $mysqli->query($sql) OR die ("Austragung hat leider nicht funktioniert, bitte kontaktieren Sie uns für einen manuellen Vorgang.");
        die("Sie wurden erfolgreich ausgetragen!");
        break;
}

// Starte Mailversand
$sql = "SELECT `email`, `name`, `stadt` FROM `newsletter` WHERE `confirmed` = '1' AND `email` != '' AND ".$addWhere;

$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc())
{
    if (!empty($row['email']))
    {
        $mailempf[] = $row['email'];
        $namen[] = $row['name'];
        $telefone[]= $row['telefon'];
    }
}

$mailempf[] = "d.peter@panten.de";


// Alle Empfänger ausgewählt. Starte den Versand jetzt.

// Dateiname
$dateiname_mail = $cardName;

$id = md5(uniqid(time()));
$dateiinhalt = file_get_contents($pdf);

foreach ($mailempf as $value) {
    // Absender Name und E-Mail Adresse
    $kopf = "From: info@metzgerei-kauffeld.de\n";
    $kopf .= "Reply-to: info@metzgerei-kauffeld.de\n";
    $kopf .= "MIME-Version: 1.0\n";
    $kopf .= "Content-Type: multipart/mixed; boundary=$id\n\n";
    $kopf .= "This is a multi-part message in MIME format\n";
    $kopf .= "--$id\n";
    $kopf .= "Content-Type: text/html; charset=UTF-8\n";
    $kopf .= "Content-Transfer-Encoding: 8bit\n\n";
    $kopf .= str_replace("%%MAIL%%", $value, $mailstring); // Inhalt der E-Mail (Body)
    $kopf .= "\n--$id";
    $kopf .= "\nContent-Type: application/pdf; name=$dateiname_mail\n";
    $kopf .= "Content-Transfer-Encoding: base64\n";
    $kopf .= "Content-Disposition: attachment; filename=$dateiname_mail\n\n";
    $kopf .= chunk_split(base64_encode($dateiinhalt));
    $kopf .= "\n--$id--";
    if (!imap_mail($value, $mailbetreff, wordwrap(str_replace("%%MAIL%%", $value, $mailstring), 70), $kopf))
    {
        echo "Kein erfolgreicher Versand an ".$value."</br>";
        continue;
    }
    else {
        echo "Erfolgreicher Versand an: ".$value."</br>";
    }
}


// Faxversand

$sql = "SELECT `fax` FROM `newsletter` WHERE `confirmed` = '1' AND `fax` != '' AND ".$addWhere;
$result = $mysqli->query($sql);
while ($row = $result->fetch_object()) {
    $faxempf .= $row->fax."\n";
}

require_once("sendPDFToRecipientList.php");
