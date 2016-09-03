<?php
$this->view->assign("header", "Ihr Kontakt zu uns");
$this->view->assign("title", "Metzgerei Kauffeld - Kontakt/Impressum");
@$this->view->assign("headImg", "img/kontakt.jpg");
$contentView = new View();
$contentView->setTemplate("impressum");
$contentView->setTmplExt(".phtml");
$this->view->assign("pageContent", $contentView->loadTemplate());