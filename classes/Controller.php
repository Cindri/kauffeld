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
    private $page = '';
    private $view = null;


    // ------ VERY IMPORTANT: ALL ALLOWED PATHS ARE SAVED HERE

    public function __construct($request){
        $this->request = $request;
        $temp_tmpl = !empty($request['view']) ? $request['view'] : 'home';
        $this->page = trim(str_replace("/", "", $temp_tmpl));
        $this->view = new View();
    }

    public function display() {


        // dynamic pages

        if (in_array($this->page, $GLOBALS['pages']['dynamic'])) {
            $this->view->assign("activeLink", $this->page);
            $this->view->setTemplate("layout");
            switch ($this->page) {
                case "mittagstisch":
                case "mittagskarte":
                    include "classes/GetController/MittagstischController.php";
                    break;

                case "wochenangebot":
                    include "classes/GetController/WochenangebotController.php";
                    break;

                case "catering":
                    include "classes/GetController/CateringController.php";
                    break;
            }
            return $this->view->loadTemplate();
        }


        // static pages

        else if (in_array($this->page, $GLOBALS['pages']['static'])) {
            $this->view->setTemplate("layout");
            $this->view->assign("activeLink", $this->page);
            switch ($this->page) {

                // ----------------- HOME ------------------
                case "home":
                    include "classes/GetController/HomeController.php";
                    break;

                // ----------------- IMPRESSUM ------------------
                case "kontakt":
                case "impressum":
                    include "classes/GetController/ImpressumController.php";
                    break;

                // ----------------- AKTUELLES ------------------
                case "aktuelles":
                    include "classes/GetController/AktuellesController.php";
                    break;


                case "wirueberuns":
                    include "classes/GetController/WirueberunsController.php";
                    break;

            }
            return $this->view->loadTemplate();
        }

        // Admin pages

        else if (in_array($this->page, $GLOBALS['pages']['admin'])) {

        }

        // POST handler

        else if (in_array($this->page, $GLOBALS['pages']['post'])) {
            switch ($this->page) {
                case "postContactForm":
                    include "classes/PostController/ContactForm.php";
                    return $this->view->loadTemplate();
                    break;
            }
        }
        else {
            $this->view->assign("page", $this->page);
            $this->view->setTemplate("error404");
            return $this->view->loadTemplate();
        }
    }
}