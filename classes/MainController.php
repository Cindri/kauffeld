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

    public function __construct($request)
    {
        $this->request = $request;
        $temp_tmpl = !empty($request['view']) ? $request['view'] : 'home';
        $this->page = trim(str_replace("/", "", $temp_tmpl));
        $this->view = new View();
    }

    public function display()
    {
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
        } else if (in_array($this->page, $GLOBALS['pages']['static'])) {
            $this->view->setTemplate("layout");
            $this->view->assign("activeLink", $this->page);
            switch ($this->page) {

                case "home":
                    include "classes/GetController/HomeController.php";
                    break;

                case "kontakt":
                case "impressum":
                    include "classes/GetController/ImpressumController.php";
                    break;

                case "aktuelles":
                    include "classes/GetController/AktuellesController.php";
                    break;

                case "wirueberuns":
                    include "classes/GetController/WirueberunsController.php";
                    break;
            }
            return $this->view->loadTemplate();
        } else if (in_array($this->page, $GLOBALS['pages']['admin'])) {
            switch ($this->page) {
                case "admin":
                    // Neben Laden der Templates auch Login-Funktionen!
                    $this->view->setTemplate("layout");
                    $this->view->assign("title", "Metzgerei Kauffeld - Login");
                    $this->view->assign("header", "Administration - Login");
                    $this->view->assign("hideNavi", true);

                    $getToken = empty($this->request['token']) ? "" : $this->request['token'];

                    $subpagesArray = array(
                        "mittagstisch?token=".$getToken => "Mittagstische bearbeiten",
                        "wochenkarte?token=".$getToken => "Wochenkarten bearbeiten",
                        "catering?token=".$getToken => "Catering bearbeiten"
                    );

                    $subpage = empty($this->request['subpage']) ? "" : trim($this->request['subpage']);
                    $this->view->assign("activeLink", $this->page);
                    $this->view->assign("subNavi_active", $subpage);
                    $this->view->assign("subNavi", $subpagesArray);


                    $login = new Login($getToken, $_SERVER['REMOTE_ADDR']);

                    if (empty($_GET['token'])) {
                        $loginView = new View();
                        $loginView->setTemplate("login");
                        $loginView->assign("error", NULL);

                        if (!empty($this->request['loginPass'])) {
                            if ($login->checkPw($this->request['loginPass'])) {
                                $token = $login->login($getToken);
                                if (!empty($token)) {
                                    header('Location: admin/?token=' . $token);
                                } else {
                                    $loginView->assign("error", $login->getError());
                                }
                            } else {
                                $loginView->assign("error", $login->getError());
                            }
                        }
                    } else {

                        // Token und zugehörige Eigenschaften prüfen
                        if (!$login->verifyToken()) {
                            $loginView = new View();
                            $loginView->setTemplate("login");
                            $loginView->assign("error", "Ungültiges Token! Bitte rufen Sie keine Admin-Seiten ohne Login aus der History auf oder tätigen Sie nach spätestens einer Stunde eine Eingabe, das System hält Sie ansonsten für offline und loggt Sie aus.");
                        } else {
                            // Initialisiere Admin-Views
                            $adminView = new View();
                            $adminView->setTemplate("admin/adminStart");

                            switch ($subpage) {
                                case "mittagstisch":
                                    $this->view->assign("title", "Metzgerei Kauffeld - Admin Mittagstisch");
                                    $this->view->assign("header", "Administration - Mittagstische");
                                    include "classes/AdminController/MittagstischAdminController.php";
                                    break;

                                case "wochenkarte":
                                    $this->view->assign("activeLink", $this->page);
                                    $this->view->assign("title", "Metzgerei Kauffeld - Admin Wochenkarten");
                                    $this->view->assign("header", "Administration - Wochenkarten");
                                    // KEINE Post-Verarbeitung hier! POST-Requests gehen mit Token-Überprüfung an einen Post-Controller, der mit header weiterleitet!
                                    $adminSubpageContent = "Hallo ich bin Testcontent für die Wochenkarte";
                                    break;
                                case "catering":
                                    $this->view->assign("activeLink", $this->page);
                                    $this->view->assign("title", "Metzgerei Kauffeld - Admin Catering");
                                    $this->view->assign("header", "Administration - Catering");
                                    // KEINE Post-Verarbeitung hier! POST-Requests gehen mit Token-Überprüfung an einen Post-Controller, der mit header weiterleitet!
                                    $adminSubpageContent = "Hallo ich bin Testcontent für das Catering";
                                    break;
                                default:
                                    $adminSubpageContent = "";
                            }
                            $adminView->assign("adminContent", $adminSubpageContent);
                            $adminView->assign("token", $login->getToken());
                        }
                    }

                    if (isset($loginView) && $loginView instanceof View) {
                        $pageContent = $loginView->loadTemplate();
                    } else if (isset($adminView) && $adminView instanceof View) {
                        $pageContent = $adminView->loadTemplate();
                    }

                    $this->view->assign("pageContent", $pageContent);
                    return $this->view->loadTemplate();
                    break;
            }
        } else if (in_array($this->page, $GLOBALS['pages']['post'])) {
            switch ($this->page) {
                case "postContactForm":
                    include "classes/PostController/ContactForm.php";
                    return $this->view->loadTemplate();
                    break;
                case "postMittagstisch":
                    include "classes/PostController/Mittagstisch.php";
                    return null;
                    break;
            }
        } else {
            $this->view->assign("page", $this->page);
            $this->view->setTemplate("error404");
            return $this->view->loadTemplate();
        }
    }
}