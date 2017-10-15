<?php

class MaintenanceException extends Exception
{
	public $errorCode;

	public function parseSOAPFault(SoapFault $o_SoapFault){
		$this->message = $o_SoapFault->getMessage();
		$this->code = $o_SoapFault->getCode();
		$this->file = $o_SoapFault->getFile();
		$this->line = $o_SoapFault->getLine();
		$this->trace = $o_SoapFault;

		$m_Detail = $o_SoapFault->detail;
		$a_Detail = (array)$m_Detail;
		$o_SE = $a_Detail['MaintenanceException'];
		$this->errorCode = $o_SE->errorCode;
	}
}