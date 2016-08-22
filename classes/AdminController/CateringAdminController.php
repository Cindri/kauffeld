<?php
$this->view->assign("activeLink", $this->page);
$this->view->assign("title", "Metzgerei Kauffeld - Admin Catering");
$this->view->assign("header", "Administration - Catering");

// 1. Daten holen, 2. Daten an View übergeben 3. Formular ausgeben 4. Post-Handler mit Weiterleitung schreiben
$cateringType = empty($this->request['type']) ? "fingerfood" : $this->request['type'];
$data = new Catering($cateringType);
$werbetext = $data->getWerbetext();
$speisen = $data->getEntries(false, "ID ASC");

$adminSubpage = new View();
$adminSubpage->setTemplate("admin/adminCatering");
$adminSubpage->assign("entries", $cateringType);
$adminSubpage->assign("subNavi", $data->subpagesArray);
$adminSubpage->assign("subNavi_active", $data->getType());
$adminSubpage->assign("activeLink", "admin/catering");

if (!empty($speisen['error'])) {
    $adminSubpage->assign("error", $speisen['error']);
}
else {
    foreach ($speisen as $key => $value) {
        if ($key == "error") { continue; }
        $cateringMeals[$key]['title'] = $value['title'];
        $cateringMeals[$key]['descr'] = $value['desc'];
        $cateringMeals[$key]['price'] = $value['price'];
        $cateringMeals[$key]['display'] = $value['display'];
        $cateringMeals[$key]['unit'] = $value['unit'];
    }
    $adminSubpage->assign("entries", $cateringMeals);
    $adminSubpage->assign("type", $cateringType);
    $adminSubpage->assign("werbetext", $werbetext);
}

$adminSubpage->assign("token", $login->getToken());
$adminSubpageContent = $adminSubpage->loadTemplate();