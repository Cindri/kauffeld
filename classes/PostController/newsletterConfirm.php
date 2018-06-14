<?php
$data = new Model();
$id = $data->getDbConn()->real_escape_string(base64_decode($_GET['code']));

$sql = "UPDATE newsletter SET confirmed = '1', date_confirmed = '" . time() . "' WHERE ID = '$id'";
if ($data->getDbConn()->query($sql)) {
    header("Location: https://metzgerei-kauffeld.de/kontakt?externalMsg=newsletterConfirmed");
} else {
    header("Location: https://metzgerei-kauffeld.de/kontakt?externalMsg=newsletterConfirmed");
}

