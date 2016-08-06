<?php
$data = new Wochenkarte();
$this->view->assign("header", "Wochenangebot");
@$this->view->assign("title", "Metzgerei Kauffeld - Wochenangebot");
@$this->view->assign("headImg", "img/3_wochenangebot.jpg");

$entries = $data->getEntries();

if (!empty($entries['error'])) {
    $this->view->assign("error", $entries['error']);
    $finalContent = '';
}
else {
    $pageContent = new View();
    $pageContent->assign("entries", $entries);

    $pageContent->setTemplate("wochenangebot");
    $pageContent->setTmplExt(".phtml");
    $finalContent = $pageContent->loadTemplate();
}

$this->view->assign("pageContent", $finalContent);