<?php
$data = new Catering();
$type = $data->getDbConn()->real_escape_string($this->request['type']);

$werbetext = $this->request['werbetext'];
$data->getDbConn()->query("UPDATE catering_meta SET werbetext = '$werbetext' WHERE type = '$type'");

$titleArr = $this->request['title'];
$descrArr = $this->request['descr'];
$priceArr = $this->request['price'];
$unitArr = $this->request['unit'];
$displayArr = $this->request['display'];

foreach ($titleArr as $key => $value) {
    $updateTable = array(
        'title' => $data->getDbConn()->real_escape_string($value),
        'description' => $data->getDbConn()->real_escape_string($descrArr[$key]),
        'price' => $data->getDbConn()->real_escape_string($priceArr[$key]),
        'unit' => $data->getDbConn()->real_escape_string($unitArr[$key]),
        'display' => $displayArr[$key]
    );
    $data->updateTable('catering', $updateTable, $key);
}

header("Location: " . BASE_URL . "admin/catering?token=" . $this->request['token'] . "&type=" . $type);