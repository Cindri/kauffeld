<?php

/**
 * Created by PhpStorm.
 * User: David
 * Date: 01.08.2016
 * Time: 11:47
 */
class Mittagstisch extends Model
{
    private $geschaeft;
    private $kalenderwoche;
    private $werbetext;
    private $date;
    private $kartenID;
    private $startDate;
    private $endDate;
    private $error = false;

    private $subpagesArray = array(
        "hauptgeschaeft" => "Hauptgeschäft Baden-Oos",
        "rheinstrasse" => "Zweigstelle Rheinstraße"
    );

    public function __construct($geschaeft, $date = "")
    {
        parent::__construct();
        $this->geschaeft = $this->getDbConn()->real_escape_string($geschaeft);
        $this->date = new DateTime();
        $this->date->createFromFormat("Y-m-d", $date);
        $this->kalenderwoche = $this->date->format("W");

        $sql = 'SELECT ID, startDate, endDate, werbetext, last_change FROM mittagskarten WHERE startDate <= \''.$this->date->format("Y-m-d").'\' AND endDate >= \''.$this->date->format("Y-m-d").'\' AND geschaeft = \''.$this->geschaeft.'\'';
        if ($res = $this->getDbConn()->query($sql)) {
            if ($res->num_rows == 0) {
                $this->error = View::errorBox("alert-warning", "Keine Karte angelegt", "Für die aktuelle Woche wurde noch keine Speisekarte angelegt. Schauen Sie bald wieder vorbei, wir tragen dies so bald wie möglich nach!");
            }
            else {
                $tableData = $res->fetch_object();
                $res->free();
                $this->werbetext = $tableData->werbetext;
                $this->kartenID = $tableData->ID;
                $this->startDate = new DateTime($tableData->startDate);
                $this->endDate = new DateTime($tableData->endDate);
            }
        }
        else {
            $this->werbetext = "Noch keine Mittagskarte für diese Zweigstelle vorhanden.";
            $this->kartenID = null;
            $this->startDate = new DateTime();
            $this->endDate = new DateTime();
        }
    }

    public function getGeschaeft()
    {
        return $this->geschaeft;
    }

    public function getSubpagesArray()
    {
        return $this->subpagesArray;
    }

    public function getKalenderwoche()
    {
        return $this->kalenderwoche;
    }

    public function getWerbetext()
    {
        return $this->werbetext;
    }

    public function getKartenID()
    {
        return $this->kartenID;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function getStartDateStr($format = "d.m.Y")
    {
        return $this->startDate->format($format);
    }

    public function getEndDateStr($format = "d.m.Y")
    {
        return $this->endDate->format($format);
    }

    public function getError() {
        return $this->error;
    }

    public function getOrderedMealsList() {

        $returnList = array("error" => false, "entries" => array());
        $dayWords = array(1 => "Montag", 2 => "Dienstag", 3 => "Mittwoch", 4 => "Donnerstag", 5 => "Freitag", 6 => "Samstag", 7 => "Sonntag", 99 => "");

        if (empty($this->kartenID)) {
            $returnList["error"] = View::errorBox("alert-warning", "Keine Mittagskarte vorhanden!", "Ein Fehler ist aufgetreten, sodass keine Mittagskarte erzeugt werden konnte. Sollten Sie diese Nachricht nach Aktualisierung der Seite erneut bekommen, nehmen Sie bitte <a href=\"".BASE_URL."kontakt\">Kontakt</a> zu uns auf.");
            return $returnList;
        }

        $sql = "SELECT type, day, title, description, price FROM mittagsspeisen WHERE kartenID = '$this->kartenID' ORDER BY day ASC, type ASC";
        $res = $this->getDbConn()->query($sql) OR die($this->getDbConn()->error);

        for ($i = 0; $meal = $res->fetch_object(); $i++) {
            if (empty($meal->title)) {
                continue;
            }
            $returnList["entries"][$i]['headline'] = empty($dayWords[$meal->day]) ? $meal->type : $dayWords[$meal->day];
            $returnList["entries"][$i]['title'] = $meal->title;
            $returnList["entries"][$i]['descr'] = $meal->description;
            $returnList["entries"][$i]['price'] = $meal->price;
        }

        $res->free();
        return $returnList;
    }
}