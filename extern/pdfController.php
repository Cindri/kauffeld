<?php
if ($_GET['key'] != "seftgojktruh89ui2jn2l1iu3z894") {
    die("Falscher Key. Abbruch.");
}

$mysqli = new mysqli("wp126.webpack.hosteurope.de", "db1091580-kauf", "%;XDQd!q@zpr", "db1091580-kauffeld");
$mysqli->query("SET NAMES 'utf8'");

include "makePDF.php";

$pdfData = array();

$subpagesArray = array(
    "hauptgeschaeft" => "Hauptgeschäft",
    "rheinstrasse" => "Filiale Rheinstraße"
);

$adressenArray = array(
    "hauptgeschaeft" => "Wilhelm-Drapp-Str. 14, Baden-Baden, Tel. 07221-5046690",
    "rheinstrasse" => "Rheinstraße 79, Baden-Baden, Tel. 07221-17389",
    "daxlanden" => "Kastenwörtstraße 25, Tel. 0721-573834"
);

$oeffnungszeitenArray = array(
    "hauptgeschaeft" => "Mo.-Fr. 7.00-18.30 Uhr, Sa. 8.00-15.00 Uhr",
    "rheinstrasse" => "Mo.-Fr. 8.00-18.30 Uhr, Sa. 8.00-13.00 Uhr",
    "daxlanden" => "Mo.-Fr. 7.00-13.00, 15.00-18.00 Uhr, Mi. Mittag geschl., Sa. 6.30-12.30 Uhr"
);

switch ($_GET['page']) {
    case "wochenkarte":
        $kartenID = $mysqli->real_escape_string($_GET['card']);
        $pdfData['header'] = "Unser Wochenangebot";
        $pdfData['backgroundImage'] = "catering.jpg";
        $pdfData['adresse'] = '';
        $pdfData['oeffnungszeiten'] = '';

        $sql = "SELECT wochenangebot.*, wochenkarten.startDate, wochenkarten.endDate FROM wochenangebot RIGHT JOIN wochenkarten ON wochenangebot.kartenID = wochenkarten.ID WHERE wochenangebot.kartenID = '$kartenID' ORDER BY ID ASC";
        $result = $mysqli->query($sql) OR die($mysqli->error);
        $pdfData['folder'] = "wochenkarte";
        $pdfData['kartenID'] = $kartenID;
        while ($row = $result->fetch_object()) {
            $pdfData['entries'][$row->ID]['headline'] = $row->type;
            $pdfData['entries'][$row->ID]['title'] = $row->title;
            $pdfData['entries'][$row->ID]['description'] = $row->description;
            $pdfData['entries'][$row->ID]['price'] = $row->price;
            $pdfData['entries'][$row->ID]['unit'] = trim($row->unit);
            $pdfData['startDate'] = $row->startDate;
            $pdfData['endDate'] =   $row->endDate;
            $pdfData['werbetext'] = $row->werbetext;
        }

        $pdfData['footerAdressen'] = $adressenArray;
        $pdfData['footerZeiten'] = $oeffnungszeitenArray;

        $pdfData['entryMargin'] = 18;
        $pdfData['lineHeight'] = 14;
        $pdfData['entryHeadMargin'] = 24;

        $pdfData['fontSizeBigHead'] = 24;
        $pdfData['fontSizeEntryHead'] = 20;
        $pdfData['fontSizeSubhead'] = 14;
        $pdfData['fontSizeText'] = 13;


        break;
    case "catering":
        $subpagesArray = array(
            "fingerfood" => "Fingerfood",
            "antipasti" => "Antipasti",
            "feinkost" => "Feinkostsalate",
            "suppen" => "Suppen und Eintöpfe",
            "fleischgerichte" => "Warme Fleischgerichte",
            "beilagen" => "Beilagen und Saucen",
            "gerichte" => "Warme Gerichte",
            "desserts" => "Desserts",
            "buffet" => "Büffetvorschläge"
        );
        $pdfData['header'] = "Catering";
        $pdfData['backgroundImage'] = "catering.jpg";
        $subpage = $mysqli->real_escape_string($_GET['subpage']);
        $pdfData['folder'] = "catering";
        $pdfData['kartenID'] = "CateringAngebot";
        $pdfData['adresse'] = '';
        $pdfData['oeffnungszeiten'] = '';
        $sql = "SELECT * FROM catering WHERE displayPDF = '1'";
        $result = $mysqli->query($sql) OR die($mysqli->error);
        while ($row = $result->fetch_object()) {
            $pdfData['entries'][$row->ID]['headline'] = $subpagesArray[$row->subpage];
            $pdfData['entries'][$row->ID]['title'] = $row->title;
            $pdfData['entries'][$row->ID]['description'] = $row->description;
            $pdfData['entries'][$row->ID]['price'] = $row->price;
            $pdfData['entries'][$row->ID]['unit'] = trim($row->unit);
            $pdfData['startDate'] = null;
            $pdfData['endDate'] =   null;
            $pdfData['werbetext'] = null;
        }

        $pdfData['entryMargin'] = 28;
        $pdfData['lineHeight'] = 14;
        $pdfData['entryHeadMargin'] = 24;

        $pdfData['fontSizeBigHead'] = 24;
        $pdfData['fontSizeEntryHead'] = 20;
        $pdfData['fontSizeSubhead'] = 14;
        $pdfData['fontSizeText'] = 13;

        $pdfData['footerAdressen'] = $adressenArray;
        $pdfData['footerZeiten'] = $oeffnungszeitenArray;

        break;
    case "mittagstisch":

        $kartenID = $mysqli->real_escape_string($_GET['card']);
        $subpage = $mysqli->real_escape_string($_GET['subpage']);
        $pdfData['folder'] = "mittagstisch";
        $pdfData['adresse'] = $adressenArray[$subpage];
        $pdfData['mittagstisch'] = "Mittagsmenü verfügbar ab 11.15 Uhr";

        $dayWords = array(1 => "Montag", 2 => "Dienstag", 3 => "Mittwoch", 4 => "Donnerstag", 5 => "Freitag", 6 => "Samstag", 7 => "Sonntag", 99 => "");
        $pdfData['kartenID'] = $kartenID;

        $pdfData['header'] = "Mittagstisch ".$subpagesArray[$subpage];
        $pdfData['backgroundImage'] = "mittagstisch.jpg";
        $sql = "SELECT mittagskarten.*, mittagsspeisen.* FROM mittagskarten RIGHT JOIN mittagsspeisen ON mittagskarten.ID = mittagsspeisen.kartenID WHERE mittagskarten.ID = '$kartenID' ORDER BY mittagsspeisen.day ASC";
        $result = $mysqli->query($sql) OR die($mysqli->error);
        while ($row = $result->fetch_object()) {
            $pdfData['entries'][$row->ID]['headline'] = $dayWords[$row->day];
            if ($row->day == 99) {
                $pdfData['entries'][$row->ID]['headline'] = $row->type;
            }
            $pdfData['entries'][$row->ID]['title'] = $row->title;
            $pdfData['entries'][$row->ID]['description'] = $row->description;
            $pdfData['entries'][$row->ID]['price'] = $row->price;
            $pdfData['entries'][$row->ID]['unit'] = null;
            $pdfData['startDate'] = $row->startDate;
            $pdfData['endDate'] =   $row->endDate;
            $pdfData['werbetext'] = $row->werbetext;
        }

        // Schriftgrößen für Hauptgeschäft anpassen
        if ($subpage == "hauptgeschaeft") {
            $pdfData['fontSizeBigHead'] = 22;
            $pdfData['fontSizeEntryHead'] = 18;
            $pdfData['fontSizeSubhead'] = 12;
            $pdfData['fontSizeText'] = 11;

            $pdfData['entryMargin'] = 14;
            $pdfData['lineHeight'] = 11;
            $pdfData['entryHeadMargin'] = 20;
        } else {
            $pdfData['fontSizeBigHead'] = 24;
            $pdfData['fontSizeEntryHead'] = 20;
            $pdfData['fontSizeSubhead'] = 14;
            $pdfData['fontSizeText'] = 13;

            $pdfData['entryMargin'] = 18;
            $pdfData['lineHeight'] = 14;
            $pdfData['entryHeadMargin'] = 24;
        }


        break;

    default:
    break;
}

$link = createPDFOutput($pdfData);

if (empty($link['error'])) {

    switch ($_GET['page']) {
        case "mittagstisch":
            $sql = "UPDATE mittagskarten SET pdfUrl = '".$link['url']."' WHERE ID = '".$kartenID."'";
            break;
        case "wochenkarte":
            $sql = "UPDATE wochenkarten SET pdfUrl = '".$link['url']."' WHERE ID = '".$pdfData['kartenID']."'";
            break;
        case "catering":
            $sql = "UPDATE catering_meta SET pdfUrl = '".$link['url']."' WHERE type = '".$pdfData['kartenID']."'";
            break;
    }

    $mysqli->query($sql) OR die ($mysqli->error);

    header('Content-type: application/pdf');
    echo file_get_contents($link['url']);
}




