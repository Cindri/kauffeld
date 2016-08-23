<?php
$data = new Mittagstisch($this->request['geschaeft']);
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

$data->updateTable("mittagskarten", $updateTable, $tableID);


$titleArr = $this->request['title'];
$descrArr = $this->request['descr'];
$priceArr = $this->request['price'];
$unitArr = $this->request['unit'];

foreach ($titleArr as $key => $value) {
    $updateTable = array(
        'title' => $value,
        'description' => $descrArr[$key],
        'price' => $priceArr[$key]
    );
    $data->updateTable('mittagsspeisen', $updateTable, $key);
}

header("Location: " . BASE_URL . "admin/mittagstisch?token=" . $token . "&edit=" . $tableID);
