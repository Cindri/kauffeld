<?php
$this->view->assign("title", "Metzgerei Kauffeld - Mittagstisch");
@$this->view->assign("headImg", "img/2_mittagstisch.jpg");

$subpage = empty($this->request['subpage']) ? "" : trim($this->request['subpage']);

switch($subpage) {
    case "hauptgeschaeft":
    case "baden-oos":
    case "oos":
    case "":
        $mittagstisch = new Mittagstisch("hauptgeschaeft");
        $this->view->assign("header", "Mittagstisch Hauptgeschäft");
        @$this->view->assign("subNavi_active", "hauptgeschaeft");
        break;
    case "rheinstrasse":
    case "zweigstelle":
    case "weststadt":
        $mittagstisch = new Mittagstisch("rheinstrasse");
        $this->view->assign("header", "Mittagstisch Rheinstraße");
        @$this->view->assign("subNavi_active", "rheinstrasse");
        break;
    default:
        $this->view->assign("header", "Mittagstisch - Fehler!");
        $mittagstisch = null;
        $pageContent = View::errorBox("alert-danger", "Unbekannter Fehler!", "Bei der Initialisierung des Mittagstischs ist ein unbekannter Fehler aufgetreten. Möglicherweise wurde ein Link falsch eingegeben. Versuchen Sie, die Seite erneut zu laden. Sollte der Fehler weiterhin auftreten, nehmen Sie bitte <a href=\"".BASE_URL."kontakt\">Kontakt</a> zu uns auf.");
}

if (!empty($mittagstisch)) {
    $this->view->assign("subNavi", $mittagstisch->getSubpagesArray());
    $contentView = new View();
    $contentView->setTemplate("mittagstisch");
    if (!empty($mittagstisch->getError())) {
        $pageContent = $mittagstisch->getError();
    }
    else {
        if ($mittagstisch->getGeschaeft() == "hauptgeschaeft") {
            $contentView->assign("filiale", "Hauptgeschäft Baden-Oos");
        } else {
            $contentView->assign("filiale", "Filiale Rheinstraße");
        }
        $contentView->assign("startDate", $mittagstisch->getStartDateStr());
        $contentView->assign("endDate", $mittagstisch->getEndDateStr());
        $contentView->assign("kw", $mittagstisch->getKalenderwoche());

        $meals = $mittagstisch->getOrderedMealsList();

        if (empty($meals['error'])) {
            if (empty($meals['entries'])) {
                $pageContent = View::errorBox("alert-warning", "Noch keine Speisen eingetragen!", "Aktuell wurden noch keine Speisen für die aktuelle Mittagskarte eingetragen. Das tut uns leid. Wir werden zügig daran arbeiten, unser Angebot hier zu präsentieren!");
            } else {
                $contentView->assign("entries", $meals['entries']);
                $contentView->assign("werbetext", $mittagstisch->getWerbetext());
                $pageContent = $contentView->loadTemplate();
            }
        } else {
            $pageContent = $meals['error'];
        }
    }
}

// Content in eine Variable laden
$this->view->assign("pageContent", $pageContent);