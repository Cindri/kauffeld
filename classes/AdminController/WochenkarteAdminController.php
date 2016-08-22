<?php
$this->view->assign("activeLink", $this->page);
$this->view->assign("title", "Metzgerei Kauffeld - Admin Wochenkarte");
$this->view->assign("header", "Administration - Wochenkarte");


$adminSubpage = new View();

if (!empty($this->request['delete'])) {
    $data->dbDelete("wochenkarten", $this->request['delete']);
    $data->dbDeleteMultiple("wochenangebot", "kartenID", $this->request['delete']);
}

$adminSubpage->setTemplate("admin/adminWochenkarte");

$data = new Wochenkarte();
$allCards = $data->getWholeTable("wochenkarten", "startDate DESC");
for ($i = 0; $i < count($allCards)-1; $i++) {
    $startDate = new DateTime($allCards[$i]->startDate);
    $endDate = new DateTime($allCards[$i]->endDate);
    $tableData[$i]['ID'] = $allCards[$i]->ID;
    $tableData[$i]['startDate'] = $startDate;
    $tableData[$i]['endDate'] = $endDate;
    $tableData[$i]['werbetext'] = $allCards[$i]->werbetext;
}
if (empty($allCards['msg'])) {
    $adminSubpage->assign("tableData", $tableData);
}

if (!empty($this->request['edit'])) {
    $adminSubpage->assign("editID", intval($this->request['edit']));
    $cardID = $data->getDbConn()->real_escape_string($this->request['edit']);
    $allMeals = $data->getEntries("", $cardID);
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
$this->view->assign("token", $login->getToken());
$adminSubpage->assign("token", $login->getToken());
$adminSubpageContent = $adminSubpage->loadTemplate();