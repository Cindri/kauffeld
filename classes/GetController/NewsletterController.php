<?php
$this->view->assign("title", "Metzgerei Kauffeld - Newsletter-Anmeldung");
$this->view->assign("header", "Newsletter-Anmeldung");
// Load head img
$headImg = new FileHandler("img/6_wirueberuns.jpg", "image");
if (empty($headImg->error)) {
    $this->view->assign("headImg", $headImg->getUrl());
}
$pageContent = new View();
$pageContent->setTemplate("newsletter");
$this->view->assign("pageContent", $pageContent->loadTemplate());