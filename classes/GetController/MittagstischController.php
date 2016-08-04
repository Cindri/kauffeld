<?php
$this->view->assign("header", "Mittagstisch");
if (!empty($this->request['subpage']) && trim($this->request['subpage']) == "rheinstrasse") {
    $this->pageData = new Mittagstisch("Rheinstrasse");
}
else {
    $this->pageData = new Mittagstisch("Hauptgeschäft");
}

$contentView = new View();

// Layout-Parameter
$this->view->assign("title", "Metzgerei Kauffeld - Herzlich Willkommen!");
$this->view->assign("message", "Alles in Ordnung. Dies repräsentiert zugewiesene Daten für die Startseite.");
$this->view->assign("testData", "Blabla Testdata");

// Seiten-Parameter

$contentView->setTemplate("mittagstisch");
$contentView->assign("adText", $this->pageData->getGeschaeft());
$contentView->assign("dbInfo", $this->pageData->getDbConn()->client_info);

// Content in eine Variable laden
$this->view->assign("pageContent", $contentView->loadTemplate());