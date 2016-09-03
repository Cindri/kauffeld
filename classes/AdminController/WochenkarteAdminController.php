<?php

$adminSubpage = new View();
$adminSubpage->setTemplate("admin/adminWochenkarte");

$data = new Model();

$this->view->assign("activeLink", $this->page);
$this->view->assign("title", "Metzgerei Kauffeld - Admin Wochenkarte");
$this->view->assign("header", "Administration - Wochenkarte");

if (!empty($this->request['delete'])) {
    $data->dbDelete("wochenkarten", $this->request['delete']);
    $data->dbDeleteMultiple("wochenangebot", "kartenID", $this->request['delete']);
}

// Neue Karte anlegen, falls gewünscht
if (!empty($this->request['create'])) {

    $createCols = array(
        'startDate' => '1990-01-01',
        'endDate' => '1990-01-02',
    );

    if (intval($insertId = $data->createTable("wochenkarten", $createCols)) == 0) {
        $adminSubpage->assign("error", View::errorBox("alert-danger", "Fehler beim Erstellen der Karten."));
    } else {

        for ($i = 1; $i <= 6; $i++) {

            // Angebote erzeugen
            $createCols = array(
                'kartenID' => $insertId,
                'type' => 'Angebot'
            );
            if ($data->createTable("wochenangebot", $createCols) == 0) {
                $adminSubpage->assign("error", View::errorBox("alert-danger", "Fehler beim Erstellen der Speisen der Karte #$insertId."));
            }
        }

        $createCols = array(
            'kartenID' => $insertId,
            'type' => 'Salat der Woche'
        );
        if ($data->createTable("wochenangebot", $createCols) == 0) {
            $adminSubpage->assign("error", View::errorBox("alert-danger", "Fehler beim Erstellen der Speisen der Karte #$insertId."));
        }
    }
}


$allCards = $data->getWholeTable("wochenkarten", "startDate DESC");

$table = array();
$mealsData = new Wochenkarte();

for ($i = 0; $i < count($allCards)-1; $i++) {
    $table[$i]['id'] = $allCards[$i]->ID;
    $table[$i]['startDate'] = new DateTime($allCards[$i]->startDate);
    $table[$i]['endDate'] = new DateTime($allCards[$i]->endDate);
    $table[$i]['werbetext'] = $allCards[$i]->werbetext;
}

foreach ($table as $key1 => $tableModel) {
    if (!empty($tableModel['id'])) {
        if ($this->request['edit'] == $tableModel['id']) {
            $adminSubpage->assign("editID", $tableModel['id']);

            $allMeals = $mealsData->getEntries("", $tableModel['id']);

            if (empty($allMeals['addData']['error'])) {
                foreach ($allMeals['entries'] as $key => $value) {
                    $editTableData[$key]['type'] = $value['type'];
                    $editTableData[$key]['title'] = $value['title'];
                    $editTableData[$key]['descr'] = $value['desc'];
                    $editTableData[$key]['price'] = $value['price'];
                    $editTableData[$key]['unit'] = $value['unit'];
                    $editTableData[$key]['ID'] = $key;
                }
                $adminSubpage->assign("editTable", $editTableData);
            } else {
                $adminSubpage->assign("error", $allMeals['addData']['error']);
            }

        }
        $tableData[$key1]['ID'] = $tableModel['id'];
        $tableData[$key1]['startDate'] = $tableModel['startDate'];
        $tableData[$key1]['endDate'] = $tableModel['endDate'];
        $tableData[$key1]['werbetext'] = $tableModel['werbetext'];
        $adminSubpage->assign("tableData", $tableData);
    }
}
$this->view->assign("token", $login->getToken());
$adminSubpage->assign("token", $login->getToken());
$adminSubpageContent = $adminSubpage->loadTemplate();
