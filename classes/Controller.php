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
            $this->view->setFileExt(".html");
            return $this->view->loadTemplate();
        }
    }

    private function displayStatic() {
        $this->view->setTemplate("layout");
        $this->view->assign("activeLink", $this->template);
        switch ($this->template) {
            case "home":
                // View für Unterseite generieren
                $contentView = new View();

                // Allgemeine Seitenangaben
                $this->view->assign("title", "Metzgerei Kauffeld - Herzlich Willkommen!");
                $this->view->assign("message", "Alles in Ordnung. Dies repräsentiert zugewiesene Daten für die Startseite.");
                $this->view->assign("testData", "Blabla Testdata");

                // Seitenspezifische Daten
                $contentView->setTemplate("home");
                $contentView->assign("adText", "blabla Ad-Text");

                // Content in eine Variable laden
                $this->view->assign("pageContent", $contentView->loadTemplate());
                break;
            case "impressum":
                $contentView = new View();
                $contentView->setTemplate("impressum");
                $contentView->setFileExt(".html");
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
                // View für Unterseite generieren
                $contentView = new View();

                // Allgemeine Seitenangaben
                $this->view->assign("title", "Metzgerei Kauffeld - Herzlich Willkommen!");
                $this->view->assign("message", "Alles in Ordnung. Dies repräsentiert zugewiesene Daten für die Startseite.");
                $this->view->assign("testData", "Blabla Testdata");

                // Seitenspezifische Daten

                $contentView->setTemplate("mittagstisch");
                $contentView->assign("adText", $this->pageData->getGeschaeft());
                $contentView->assign("dbInfo", $this->pageData->getDbConn()->client_info);

                // Content in eine Variable laden
                $this->view->assign("pageContent", $contentView->loadTemplate());
                break;

            case "wochenangebot":
                $contentView = new View();
                $contentView->setTemplate("impressum");
                $contentView->setFileExt(".html");
                $this->view->assign("pageContent", $contentView->loadTemplate());
                break;

            case "catering":
                break;
        }
        return $this->view->loadTemplate();
    }
}