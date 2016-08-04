<?php
$this->view->assign("header", "Wochenangebot");
$contentView = new View();
$contentView->setTemplate("impressum");
$contentView->setTmplExt(".html");
$this->view->assign("pageContent", $contentView->loadTemplate());