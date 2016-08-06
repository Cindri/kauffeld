<?php
class Wochenkarte extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEntries($sqlStartDate = "CURDATE()") {
        $return = array();
        $sql = "SELECT wochenkarten.startDate, wochenkarten.endDate, wochenkarten.werbetext, wochenangebot.ID, wochenangebot.title, wochenangebot.description, wochenangebot.price, wochenangebot.unit, wochenangebot.type FROM wochenkarten RIGHT JOIN wochenangebot ON wochenkarten.ID = wochenangebot.kartenID WHERE wochenkarten.startDate <= $sqlStartDate AND wochenkarten.endDate >= $sqlStartDate ORDER BY wochenkarten.startDate DESC, wochenangebot.type ASC, wochenangebot.ID ASC";
        if (!$res = $this->getDbConn()->query($sql)) {
            $return['addData']['error'] = View::errorBox("alert-danger", "MySQL-Error", "Ein schwerwiegender Fehler beim Auslesen der Datenbank ist aufgetreten. Sollten Sie diese Nachricht nach Aktualisierung der Seite erneut bekommen, nehmen Sie bitte <a href=\"".BASE_URL."kontakt\">Kontakt</a> zu uns auf.<br/><br/>Technische Meldung für den Administrator:<br/>".$this->getDbConn()->error);
        }
        else {
            if ($res->num_rows != 0) {
                while ($row = $res->fetch_object()) {

                    if (!isset($start)) {
                        $start = new DateTime($row->startDate);
                        $end = new DateTime($row->endDate);
                        $return['addData']['start'] = $start->format("d.m.Y");
                        $return['addData']['end'] = $end->format("d.m.Y");
                    }

                    if (!isset($werbetext)) {
                        $return['addData']['werbetext'] = $row->werbetext;
                    }
                    $return['entries'][$row->ID]['type'] = $row->type;
                    $return['entries'][$row->ID]['title'] = $row->title;
                    $return['entries'][$row->ID]['desc'] = $row->description;
                    $return['entries'][$row->ID]['price'] = $row->price;
                    $return['entries'][$row->ID]['unit'] = $row->unit;
                }
            }
            else {
                $return['addData']['error'] = View::errorBox("alert-warning", "Keine Einträge vorhanden.", "Das Wochenangebot für diese Woche ist noch nicht fertig zusammengestellt. Spätestens zu Beginn der nächsten Woche wird hier unser Wochenangebot präsentiert. Falls Sie dringende Fragen zu unseren Menüs haben, <a href=\"".BASE_URL."kontakt\">kontaktieren</a> Sie uns, wir helfen Ihnen gerne weiter!");
            }
        }
        return $return;
    }
}