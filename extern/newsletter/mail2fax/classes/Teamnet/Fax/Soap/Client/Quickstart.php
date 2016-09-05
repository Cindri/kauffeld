<?php

require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/QuickstartTypes.php';

/**
 * Client class for QuickStart related tasks, using the Teamnet FaxAPI webservice
 * @author tingelhoff@teamnet.de
 * @copyright Teamnet GmbH
 * @version 1.0
 */
class Teamnet_Fax_Soap_Client_Quickstart
{
	/**
	 * @var SoapClient
	 */
	public $oSOAP;

	public function __construct( $wsdl ) {
		try {
			if ( !isset( $wsdl ) ) {
				throw new ErrorException( 'WSDL isn\'t set.' );
			}
			$this->oSOAP = new SoapClient( (string) $wsdl, array( 'classmap' => getFaxapiQuickstartClassMap() ));
		}
		catch ( SoapFault $exception ) {
			throw new ServiceException( $exception );
		}
	}


	/**
	 * Checks whether the given AuthKey is valid or not
	 * @param string $authkey
	 * @return bool
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function checkAuthentification($authkey)
	{
		$oCheckAuthRequest = new CheckAuthentificationRequest();
		$oCheckAuthRequest->authKey = $authkey;

		try {
			$this->oSOAP->checkAuthentification($oCheckAuthRequest);
		} catch (SoapFault $e) {
			if (! isset($e->detail))
				throw $e;

			$oException = $this->_convertSoapFault($e);

			if ($oException instanceof AuthentificationException)
				return false;

			throw $oException;
		}

		return true;
	}


	/**
	 * Used by QuickStart to check for a new version
	 * @param string		$sVersion
	 * @param string|null	$sSysInfo
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function checkVersion($sVersion, $sSysInfo = null)
	{
		$req = new CheckVersionRequest();
		$req->system = 'PHP';
		$req->qsVersion = $sVersion;
		$req->systemInfo = $sSysInfo;

		try {
			return $this->oSOAP->checkVersion($req);
		} catch (Exception $e) {
			throw isset($e->detail) ? $this->_convertSoapFault($e) : $e;
		}
	}


	/**
	 * @internal
	 * @param SoapFault $oSoapFault
	 */
	private function _convertSoapFault(SoapFault $oSoapFault)
	{
		$m_Detail = $oSoapFault->detail;
		$a_Detail = (array)$m_Detail;

		$s_ExceptionType = array_pop(array_keys($a_Detail));

		$o_Exception = new $s_ExceptionType();
		$o_Exception->parseSOAPFault($oSoapFault);

		return $o_Exception;
	}
}
