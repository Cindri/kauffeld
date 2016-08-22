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

$this->view->assign("pageContent", $finalContent);