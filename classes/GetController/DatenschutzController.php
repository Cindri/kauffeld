<?php
$this->view->assign("header", "Datenschutz");
$this->view->assign("title", "Metzgerei Kauffeld - Datenschutz");
@$this->view->assign("headImg", "img/kontakt.jpg");
$contentView = new View();
$contentView->setTemplate("datenschutz");
$contentView->setTmplExt(".phtml");
$this->view->assign("pageContent", $contentView->loadTemplate());