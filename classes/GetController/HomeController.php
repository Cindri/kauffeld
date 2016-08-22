<?php
$this->view->assign("title", "Metzgerei Kauffeld - Herzlich Willkommen!");
$this->view->assign("header", "");
$this->view->assign("hideNavi", true);

$contentView = new View();
$contentView->setTemplate("home");

$this->view->assign("pageContent", $contentView->loadTemplate());