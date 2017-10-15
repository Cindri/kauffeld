<?php
$mysqli = new mysqli("wp126.webpack.hosteurope.de", "db1091580-kauf", "%;XDQd!q@zpr", "db1091580-kauffeld");
$mysqli->query("SET NAMES 'utf8'");

$sql = "SELECT rcf_faxNumber, rcf_weekOffer, rcf_butcheryMain, rcf_butcheryRhein FROM mkd_recipients_fax";
$result = $mysqli->query($sql);
while ($row = $result->fetch_object()) {
    $sql = "UPDATE newsletter SET willWochenkarte = '".$row->rcf_weekOffer."', willHauptgeschaeft = '".$row->rcf_butcheryMain."', willRheinstrasse = '".$row->rcf_butcheryRhein."' WHERE fax = '".$row->rcf_faxNumber."'";
    $mysqli->query($sql);
    echo 'Datensatz für Faxnummer '.$row->rcf_faxNumber.' übertragen<br/>';
}