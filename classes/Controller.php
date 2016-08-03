<?php

/**
 * Created by PhpStorm.
 * User: David
 * Date: 01.08.2016
 * Time: 11:19
 */
class Controller
{
    private $request = null;
    private $template = '';
    private $view = null;
    private $pageData = null;


    // ------ VERY IMPORTANT: ALL ALLOWED PATHS ARE SAVED HERE

    public function __construct($request){
        $this->request = $request;
        $this->template = strtolower(!empty($request['view']) ? $request['view'] : 'home');
        $this->view = new View();
    }

    public function display() {
        if (in_array($this->template, $GLOBALS['pages']['dynamic'])) {
            return self::displayDynamic();
        }
        else if (in_array($this->template, $GLOBALS['pages']['static'])) {
            return self::displayStatic();
        }
        else if (in_array($this->template, $GLOBALS['pages']['admin'])) {

        }
        else if (in_array($this->template, $GLOBALS['pages']['post'])) {

        }
        else {
            $this->view->setTemplate("error404");
            $this->view->setTmplExt(".html");
            return $this->view->loadTemplate();
        }
    }

    private function displayStatic() {
        $this->view->setTemplate("layout");
        $this->view->assign("activeLink", $this->template);
        switch ($this->template) {

            // ----------------- HOME ------------------
            case "home":
                $contentView = new View();

                $this->view->assign("title", "Metzgerei Kauffeld - Herzlich Willkommen!");
                $this->view->assign("message", "Alles in Ordnung. Dies repräsentiert zugewiesene Daten für die Startseite.");
                $this->view->assign("hideNavi", true);

                $contentView->setTemplate("home");
                $contentView->assign("adText", "blabla Ad-Text");

                $this->view->assign("pageContent", $contentView->loadTemplate());
                break;

            // ----------------- IMPRESSUM ------------------
            case "impressum":
                $contentView = new View();
                $contentView->setTemplate("impressum");
                $contentView->setTmplExt(".html");
                $this->view->assign("pageContent", $contentView->loadTemplate());
                break;

            // ----------------- AKTUELLES ------------------
            case "aktuelles":
                $this->view->assign("title", "Metzgerei Kauffeld - Aktuelles");

                // Load head img
                $headImg = new FileHandler("img/5_Aktuelles_Steak.jpg", "image");
                if (!empty($headImg->error)) {
                    $this->view->assign("error", $this->view->errorBox("alert-warning", "Bild nicht gefunden!", $headImg->error));
                }
                else {
                    $this->view->assign("headImg", $headImg->getUrl());
                }

                // Load content
                $contentView = new View();
                $contentView->setTemplate("aktuelles");
                $this->view->assign("pageContent", $contentView->loadTemplate());
                break;

        }
        return $this->view->loadTemplate();
    }

    private function displayDynamic() {
        $this->view->assign("activeLink", $this->template);
        $this->view->setTemplate("layout");
        switch ($this->template) {
            case "mittagstisch":
                if (!empty($this->request['subpage']) && trim($this->request['subpage']) == "rheinstrasse") {
                    $this->pageData = new Mittagstisch("Rheinstrasse");
                }
                else {
                    $this->pageData = new Mittagstisch("Hauptgeschäft");
                }

                $contentView = new View();

                // Layout-Parameter
                $this->view->assign("title", "Metzgerei Kauffeld - Herzlich Willkommen!");
                $this->view->assign("message", "Alles in Ordnung. Dies repräsentiert zugewiesene Daten für die Startseite.");
                $this->view->assign("testData", "Blabla Testdata");

                // Seiten-Parameter

                $contentView->setTemplate("mittagstisch");
                $contentView->assign("adText", $this->pageData->getGeschaeft());
                $contentView->assign("dbInfo", $this->pageData->getDbConn()->client_info);

                // Content in eine Variable laden
                $this->view->assign("pageContent", $contentView->loadTemplate());
                break;

            case "wochenangebot":
                $contentView = new View();
                $contentView->setTemplate("impressum");
                $contentView->setTmplExt(".html");
                $this->view->assign("pageContent", $contentView->loadTemplate());
                break;

            case "catering":
                break;
        }
        return $this->view->loadTemplate();
    }
}