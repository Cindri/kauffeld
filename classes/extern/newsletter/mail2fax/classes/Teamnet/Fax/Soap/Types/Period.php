<?php
class Period {
	public $from;
	public $until;
	
	public function __toString() {
		return 'Period[from:'.$this->from.',until:'.$this->until.']';
	}
}