<?php
$subpage = empty($this->request['subpage']) ? "" : $this->request['subpage'];
$data = new Catering($subpage);

$headImgArray = array(
    "fingerfood" => "4_fingerfood.jpg",
    "antipasti" => "4_antipasti.jpg",
    "feinkost" => "4_feinkostsalate.jpg",
    "suppen" => "4_suppen.jpg",
    "fleischgerichte" => "4_fleischgerichte.jpg",
    "beilagen" => "4_beilagen.jpg",
    "gerichte" => "4_warme_gerichte.jpg",
    "desserts" => "4_desserts.jpg",
    "buffet" => "4_buffet.jpg",
    "" => "4_fingerfood.jpg"
);
$this->view->assign("header", (empty($subpage) ? "Catering" : $data->subpagesArray[$subpage]));
@$this->view->assign("title", "Catering".(empty($subpage) ? "" : " - ".$data->subpagesArray[$subpage]));
@$this->view->assign("headImg", "img/".$headImgArray[$subpage]);
@$this->view->assign("subNavi_active", $subpage);
$this->view->assign("subNavi", $data->subpagesArray);

$pageContent = new View();

$entries = $data->getEntries();
if (!empty($entries['error'])) {
    $finalContent = $entries['error'];
}
else {
    $pageContent->assign("entries", $entries);

    $pageContent->setTemplate("catering");
    $pageContent->setTmplExt(".phtml");
    $finalContent = $pageContent->loadTemplate();

}

$this->view->assign("pageContent", $finalContent);