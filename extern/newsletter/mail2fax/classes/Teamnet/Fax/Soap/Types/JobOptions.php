<?php

class JobOptions
{
	public $costcenter = null;
	public $demoRecipient = null;
	public $ecodiscount = null;
	public $faxheader = null;
	public $faxheaderFrom = null;
	public $stationid = null;
	public $timetosend = null;
	
	public function __toString() {
		return 'JobOptions[costcenter:'.$this->costcenter
			.',ecodiscount:'.$this->ecodiscount
			.',faxheader:'.$this->faxheader
			.',faxheaderFrom:'.$this->faxheaderFrom
			.',stationid:'.$this->stationid
			.',timetosend:'.$this->timetosend.']';
	}
}