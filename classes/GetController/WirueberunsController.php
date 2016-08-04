<?php
$this->view->assign("header", "Wir über uns");
// Load head img
$headImg = new FileHandler("img/6_wirueberuns.jpg", "image");
if (empty($headImg->error)) {
    $this->view->assign("headImg", $headImg->getUrl());
}
$pageContent = new View();
$pageContent->setTemplate("wirueberuns");
$this->view->assign("pageContent", $pageContent->loadTemplate());