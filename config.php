<?php

global $pages;
$pages = array(
    "dynamic" => array(
        "mittagstisch",
        "mittagskarte",
        "wochenangebot",
        "catering"
    ),
    "static" => array(
        "home",
        "aktuelles",
        "wirueberuns",
        "kontakt",
        "impressum"
    ),
    "admin" => array(
        "admin"
    ),
    "post" => array(
        "postContactForm",
        "postMittagstisch"
    )
);

define("BASE_URL", "http://localhost/kauffeld/");

define("SQL_HOST", "localhost");
define("SQL_USER", "root");
define("SQL_PASS", "");
define("SQL_DB", "test");

define("ADMIN_PASS", "testzugang2016");