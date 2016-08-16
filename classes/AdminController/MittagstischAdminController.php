<?php
$adminSubpage = new View();
$adminSubpage->setTemplate("admin/adminMittagstisch");

// KEINE Post-Verarbeitung hier! POST-Requests gehen mit Token-Überprüfung an einen Post-Controller, der mit header weiterleitet!

$data = new Model();
if (!empty($this->request['delete'])) {
    $data->dbDelete("mittagskarten", $this->request['delete']);
    $data->dbDeleteMultiple("mittagsspeisen", "kartenID", $this->request['delete']);
}
$reqGeschaeft = empty($this->request['geschaeft']) ? "hauptgeschaeft" : $this->request['geschaeft'];

// Zur Übersicht erst alle Karten (Tische) holen
$allTableData = $data->getWholeTable("mittagskarten", "startDate DESC");
if (empty($allTableData['msg'])) {

    // Für jeden Tisch dieses Geschäfts ein Kartenobjekt erzeugen
    for ($i = 0; $i < (count($allTableData) - 1); $i++) {
        $tables[$i] = new Mittagstisch($reqGeschaeft, "", $allTableData[$i]->ID);
    }
} else {
    $adminSubpage->assign("error", View::errorBox("alert-danger", "Fehler beim Auslesen der Karten", $allTableData['msg']));
}

// Die Daten jeder Karte stehen jetzt als "Mittagstisch"-Objekte im Array $tables bereit.
// Jetzt abhängig davon, ob ein Einzeltisch zur Bearbeitung ausgewählt wurde, nur die Kartenliste oder auch Kartendetails anzeigen!


$editKartenID = empty($this->request['edit']) ? 0 : intval($this->request['edit']);


foreach ($tables as $key => $value) {
    if ($value->getKartenID() == $editKartenID) {
        $editKarteData = $value->getOrderedMealsList($value->getKartenID());
        $editKarteMeals = $editKarteData['entries'];
        $editKarteError = $editKarteData['error'];
    }
    $tableList[$key]['ID'] = $value->getKartenID();
    $tableList[$key]['KW'] = $value->getKalenderwoche();
    $tableList[$key]['startDate'] = $value->getStartDate();
    $tableList[$key]['endDate'] = $value->getEndDate();
    $tableList[$key]['werbetext'] = $value->getWerbetext();
}

$adminSubpage->assign("tableList", $tableList);
$adminSubpage->assign("token", $login->getToken());
$adminSubpage->assign("geschaeft", $reqGeschaeft);

if (isset($editKarteMeals)) {
    $adminSubpage->assign("editID", $editKartenID);
    $adminSubpage->assign("editMeals", $editKarteMeals);
    $adminSubpage->assign("editError", $editKarteError);
}

$this->view->assign("token", $login->getToken());
$adminSubpageContent = $adminSubpage->loadTemplate();