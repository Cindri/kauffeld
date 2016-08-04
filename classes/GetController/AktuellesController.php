<?php

$this->view->assign("title", "Metzgerei Kauffeld - Aktuelles");
$this->view->assign("header", "Aktuelles");

// Load head img
$headImg = new FileHandler("img/5_Aktuelles_Steak.jpg", "image");
if (empty($headImg->error)) {
    $this->view->assign("headImg", $headImg->getUrl());
}

// Load content
$contentView = new View();
$contentView->setTemplate("aktuelles");
$this->view->assign("pageContent", $contentView->loadTemplate());
?>