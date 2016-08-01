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

    private static $pages = array(
        "dynamic" => array(
            "mittagstisch",
            "wochenangebot",
            "catering"
        ),
        "static" => array(
            "home"
        ),
        "admin" => array(

        ),
        "post" => array()
    );

    public function __construct($request){
        $this->request = $request;
        $this->template = !empty($request['view']) ? $request['view'] : 'default';
        $this->view = new View();
    }

    public function display() {
        $contentView = new View();
        if (in_array($this->template, Controller::$pages["dynamic"])) {
            return self::displayDynamic($this->template);
        }
        else if (in_array($this->template, Controller::$pages['static'])) {
            return self::displayStatic($this->template);
        }
        else if (in_array($this->template, Controller::$pages['admin'])) {

        }
        else if (in_array($this->template, Controller::$pages['post'])) {

        }
        else {
            return self::displayStatic("error404");
        }
    }

    private function displayStatic($template) {
        $this->template = $template;
        switch ($template) {

            case "home":
                $this->view->setTemplate("layout.phtml");

                // View für Unterseite generieren
                $contentView = new View();

                // Allgemeine Seitenangaben
                $this->view->assign("title", "Metzgerei Kauffeld - Herzlich Willkommen!");
                $this->view->assign("message", "Alles in Ordnung. Dies repräsentiert zugewiesene Daten für die Startseite.");
                $this->view->assign("testData", Model::getLocale());

                // Seitenspezifische Daten
                $contentView->setTemplate("home.phtml");
                $contentView->assign("adText", Model::getAdText());

                // Content in eine Variable laden
                $this->view->assign("pageContent", $contentView->loadTemplate());

                return $this->view->loadTemplate();
                break;
            default:
                $this->view->setTemplate($template);
                $this->view->setFileExt(".html");
                return $this->view->loadTemplate();
        }
    }

    private function displayDynamic($template) {

    }
}