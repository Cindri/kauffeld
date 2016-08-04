<?php
$this->view->assign("title", "Metzgerei Kauffeld - Herzlich Willkommen!");
$this->view->assign("header", "");
$this->view->assign("hideNavi", true);

$contentView = new View();
$contentView->setTemplate("home");
$contentView->assign("adText", "blabla Ad-Text");

$this->view->assign("pageContent", $contentView->loadTemplate());