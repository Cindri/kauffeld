<?php

/**
 * Created by PhpStorm.
 * User: David
 * Date: 01.08.2016
 * Time: 11:52
 */
class Catering extends Model
{
    private $type;

    public $subpagesArray = array(
        "fingerfood" => "Fingerfood",
        "antipasti" => "Antipasti",
        "feinkost" => "Feinkostsalate",
        "suppen" => "Suppen und Eintöpfe",
        "fleischgerichte" => "Warme Fleischgerichte",
        "beilagen" => "Beilagen und Saucen",
        "gerichte" => "Warme Gerichte",
        "desserts" => "Desserts",
        "buffet" => "Büffetvorschläge"
    );

    public function __construct($type)
    {
        parent::__construct();
        if (empty($type)) {
            $this->type = "fingerfood";
        }
        else {
            $this->type = $this->getDbConn()->real_escape_string($type);
        }
    }

    public function getEntries($marked = true, $order = "ID ASC") {
        $return = array();

        $whereStmt = $marked ? "display = '1'" : "1";

        $sql = "SELECT ID, title, description, price, unit FROM catering WHERE subpage = '".$this->type."' AND ".$whereStmt." ORDER BY ".$order;
        if (!$res = $this->getDbConn()->query($sql)) {
            $return['error'] = View::errorBox("alert-danger", "MySQL-Error", "Ein schwerwiegender Fehler beim Auslesen der Datenbank ist aufgetreten. Sollten Sie diese Nachricht nach Aktualisierung der Seite erneut bekommen, nehmen Sie bitte <a href=\"".BASE_URL."kontakt\">Kontakt</a> zu uns auf.<br/><br/>Technische Meldung für den Administrator:<br/>".$this->getDbConn()->error);
        }
        else {
            if ($res->num_rows != 0) {
                while ($row = $res->fetch_object()) {
                    $return[$row->ID]['title'] = $row->title;
                    $return[$row->ID]['desc'] = $row->description;
                    $return[$row->ID]['price'] = $row->price;
                    $return[$row->ID]['unit'] = $row->unit;
                }
            }
            else {
                $return['error'] = View::errorBox("alert-warning", "Keine Einträge vorhanden.", "Für die Kategorie \"".$this->subpagesArray[$this->type]."\" werden momentan keine Speisen angeboten. Bleiben Sie auf dem Laufenden und schauen Sie bald wieder vorbei, das Angebot wird wahrscheinlich gerade erstellt!");
            }
        }
        return $return;
    }
}