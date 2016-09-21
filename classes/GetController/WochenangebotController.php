<?php
$data = new Wochenkarte();
$this->view->assign("header", "Unser Wochenangebot");
@$this->view->assign("title", "Metzgerei Kauffeld - Wochenangebot");
@$this->view->assign("headImg", "img/3_wochenangebot.jpg");

$entries = $data->getEntries();

if (!empty($entries['addData']['error'])) {
    $finalContent = $entries['addData']['error'];
}
else {
    $pageContent = new View();
    $pageContent->assign("entries", $entries);

    $pageContent->assign("debug", $entries);

    $pageContent->setTemplate("wochenangebot");
    $pageContent->setTmplExt(".phtml");

    $finalContent = $pageContent->loadTemplate();
}
$finalContent .= '<div class="container" style="margin-top:20px; font-size:18pt;">Hier können Sie sich am Verteiler für die Mittagsmenüs und der Wochenkarte anmelden: <a href="'.BASE_URL.'newsletter">Zur Anmeldung</a></div>';
$this->view->assign("pageContent", $finalContent);