<?php

$geschaeft = $this->request['geschaeft'];
$token = $this->request['token'];
$startDate = $this->request[''];

$data = new Model();
$sql1 = "UPDATE mittagskarten SET startDate = ";

header("Location: ".BASE_URL."admin/mittagstisch?geschaeft=".$geschaeft."&token=".$token);