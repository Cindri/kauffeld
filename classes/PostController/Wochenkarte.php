<?php
$data = new Wochenkarte();
$tableID = $data->getDbConn()->real_escape_string($this->request['tableID']);

$token = $this->request['token'];
$startDate = new DateTime($this->request['startDate'][$tableID]);
$endDate = new DateTime($this->request['endDate'][$tableID]);
$werbetext = $this->request['werbetext'][$tableID];

$updateTable = array(
    'startDate' => $startDate->format("Y-m-d"),
    'endDate' => $endDate->format("Y-m-d"),
    'werbetext' => $werbetext
);

$data->updateTable("wochenkarten", $updateTable, $tableID);

$titleArr = $this->request['title'];
$descrArr = $this->request['descr'];
$priceArr = $this->request['price'];
$unitArr = $this->request['unit'];

foreach ($titleArr as $key => $value) {
    $updateTable = array(
        'title' => $data->getDbConn()->real_escape_string($value),
        'description' => $data->getDbConn()->real_escape_string($descrArr[$key]),
        'price' => $data->getDbConn()->real_escape_string($priceArr[$key]),
        'unit' => $data->getDbConn()->real_escape_string($unitArr[$key])
    );
    $data->updateTable('wochenangebot', $updateTable, $key);
}

header("Location: " . BASE_URL . "admin/wochenkarte?token=" . $token . "&edit=" . $tableID);