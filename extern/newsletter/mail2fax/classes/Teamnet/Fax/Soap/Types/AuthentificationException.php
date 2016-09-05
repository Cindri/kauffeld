<?php

class AuthentificationException extends Exception
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
		$o_AE = $a_Detail['AuthentificationException'];
		$this->errorCode = $o_AE->errorCode;
	}
}