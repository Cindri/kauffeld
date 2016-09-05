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
        "impressum",
        "newsletter"
    ),
    "admin" => array(
        "admin"
    ),
    "post" => array(
        "postContactForm",
        "postMittagstisch",
        "postWochenkarte",
        "postCatering",
        "NewsletterRegister",
        "newsletterConfirm"
    )
);

define("BASE_URL", "http://metzgerei-kauffeld.de/");

/*
 * DB-Konfiguration 1&1
define("SQL_HOST", "db644506159.db.1and1.com");
define("SQL_USER", "dbo644506159");
define("SQL_PASS", "%;XDQd!q@zpr");
define("SQL_DB", "db644506159");
*/

define("SQL_HOST", "wp126.webpack.hosteurope.de");
define("SQL_USER", "db1091580-kauf");
define("SQL_PASS", "%;XDQd!q@zpr");
define("SQL_DB", "db1091580-kauffeld");

define("ADMIN_PASS", "testzugang2016");
define("CONTACT_MAIL", "info@metzgerei-kauffeld.de");