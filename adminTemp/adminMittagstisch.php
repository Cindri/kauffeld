<?php

include "../config.php";
$dayWords = array(1 => "Montag", 2 => "Dienstag", 3 => "Mittwoch", 4 => "Donnerstag", 5 => "Freitag", 6 => "Samstag", 7 => "Sonntag", 99 => "");
function echoWochentage($name, $wochentag) {
    echo '<select name="'.$name.'">';
    for ($i = 1; $i >= 7; $i++) {
        if ($wochentag == $i) {
            $active = " selected";
            echo '<option value="'.$i.'"'.$active.'>'.$dayWords[$i].'</option>';
        }
    }
    echo '</select>';
}

function echoFormFields($feld1, $feld2, $table) {
    echo $feld1;
    if ($table == "hauptgeschaeft") {
        echo $feld1.'<hr/>'.$feld2;
    }
}

$dbConn = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
$dbConn->query("SET NAMES 'utf8'");
$dateSource = new DateTime();

@$tisch = $_GET['tisch'];

if (!empty($_POST)) {
    if ($tisch == "hauptgeschaeft") {

        // Datum für Mittagstisch updaten
        $newStartDate = new DateTime($_POST['startDate']);
        $newEndDate = new DateTime($_POST['endDate']);

        $sDDb = $newStartDate->format("Y-m-d");
        $eDDb = $newEndDate->format("Y-m-d");
        $werbetextDb = $_POST['werbetext'];
        $sql = "UPDATE mittagskarten SET startDate = '$sDDb', endDate = '$eDDb', werbetext = '$werbetextDb', last_change = NOW() WHERE geschaeft = 'hauptgeschaeft'";
        $dbConn->query($sql);

        // Speisen updaten
        for ($i = 1; $i <= 12; $i++) {
            $sql = "UPDATE mittagsspeisen SET
                   title = '".$_POST['title'][$i]."', description = '".$_POST['description'][$i]."', price = '".$_POST['price'][$i]."' WHERE ID = '$i'";
            $dbConn->query($sql);
        }
    }
    else {
        // Datum für Mittagstisch updaten
        $newStartDate = new DateTime($_POST['startDate']);
        $newEndDate = new DateTime($_POST['endDate']);

        $sDDb = $newStartDate->format("Y-m-d");
        $eDDb = $newEndDate->format("Y-m-d");
        $werbetextDb = $_POST['werbetext'];
        $sql = "UPDATE mittagskarten SET startDate = '$sDDb', endDate = '$eDDb', werbetext = '$werbetextDb', last_change = NOW() WHERE geschaeft = 'rheinstrasse'";
        $dbConn->query($sql);

        // Speisen updaten
        for ($i = 13; $i <= 18; $i++) {
            $sql = "UPDATE mittagsspeisen SET
                   title = '".$_POST['title'][($i-12)]."', description = '".$_POST['description'][($i-12)]."', price = '".$_POST['price'][($i-12)]."' WHERE ID = '".$i."'";
            $dbConn->query($sql);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
</head>
<body>
    <div class="wrapper">
    <h1>Mittagstische bearbeiten</h1>
        Welcher Tisch soll bearbeitet werden?<br/>
        <a href="adminMittagstisch.php?tisch=hauptgeschaeft">Hauptgeschäft Oos</a>&nbsp;&nbsp;&nbsp;<a href="adminMittagstisch.php?tisch=rheinstrasse">Hauptgeschäft Oos</a>
        <?php
        if (!empty($tisch)) {
            $sql = "SELECT ID, startDate, endDate, werbetext FROM mittagskarten WHERE geschaeft = '$tisch'";
            $resKarte = $dbConn->query($sql) OR die($dbConn->error);
            $rowKarte = $resKarte->fetch_object();
            $kartenID = $rowKarte->ID;
            $startDate = new DateTime($rowKarte->startDate);
            $endDate = new DateTime($rowKarte->endDate);

            echo '<h1>Mittagskarte '.$tisch.' vom '.$startDate->format('d.m.Y').' bis '.$endDate->format("d.m.Y").'</h1>';
            echo '<form action="adminMittagstisch.php?tisch='.$tisch.'" method="POST" accept-charset="UTF-8">';
            echo 'Gültig vom <input type="text" name="startDate" value="'.$startDate->format('d.m.Y').'" /> bis zum <input type="text" name="endDate" value="'.$endDate->format('d.m.Y').'"><br/><br/>';
            echo '<table><tr><th>Wochentag</th><th>Speisen (Titel und Subline)</th><th>Preis</th></tr>';
            $sql = "SELECT ID, type, day, title, description, price FROM mittagsspeisen WHERE kartenID = '".$kartenID."' ORDER BY day ASC, type ASC";
            $resSpeisen = $dbConn->query($sql);
            $speisen = array();

            for ($i = 1; $i <= 12; $i++) {
                $speisen[$i] = array('headline' => "", 'title' => "", 'descr' => "", 'price' => "");
            }

            for ($i = 1; $meal = $resSpeisen->fetch_object(); $i++) {
                $speisen[$i]['headline'] = empty($dayWords[$meal->day]) ? $meal->type : $dayWords[$meal->day];
                $speisen[$i]['day'] = $meal->day;
                $speisen[$i]['title'] = $meal->title;
                $speisen[$i]['descr'] = $meal->description;
                $speisen[$i]['price'] = $meal->price;
            }

            if ($tisch == "hauptgeschaeft") {

                for ($i = 1; $i <= 6; $i++) {
                    $z = $i*2;
                    echo '<tr><td>' . $speisen[($z-1)]['headline'] . '<input type="hidden" name="tag['.($z-1).']" value="'.$speisen[$z-1]['day'].'"></td><td><input size="100" type="text" name="title['.($z-1).']" value="' . $speisen[($z-1)]['title'] . '"><br/><input type="text" size="100" name="description[' . ($z-1) . ']" value="' . $speisen[($z-1)]['descr'] . '"></td><td><input type="text" name="price[' . ($z-1) . ']" value="' . $speisen[($z-1)]['price'] . '"></td></tr>';
                    echo '<tr><td>' . $speisen[$z]['headline'] . '<input type="hidden" name="tag['.($z).']" value="'.$speisen[$z]['day'].'"></td><td><input size="100" type="text" name="title[' . $z . ']" value="' . $speisen[$z]['title'] . '"><br/><input type="text" size="100" name="description[' . $z . ']" value="' . $speisen[$z]['descr'] . '"/></td><td><input type="text" name="price[' . $z . ']" value="' . $speisen[$z]['price'] . '"></td></tr>';
                    echo '<tr><td colspan="3"><hr></td></tr>';
                }
            }
            else {
                for ($i = 1; $i <= 6; $i++) {
                    echo '<tr><td>' . $speisen[$i]['headline'] . '</td><td><input type="text" size="100" name="title[' . $i . ']" value="' . $speisen[$i]['title'] . '"><br/>
                    <input type="text" size="100" name="description[' . $i . ']" value="' . $speisen[$i]['descr'] . '"></td><td><input type="text" name="price[' . $i . ']" value="' . $speisen[$i]['price'] . '"></td></tr>';

                }
            }
            echo '<tr><td>Werbetext:</td><td colspan="2"><textarea name="werbetext" cols="80" rows="6">'.$rowKarte->werbetext.'</textarea></td></tr>';
            echo '<tr><td>Absenden:</td><td colspan="2"><input type="submit" value="Absenden"/></td></tr>';

            echo '</form>';
        }
        ?>
    </div>
</body>
</html>
