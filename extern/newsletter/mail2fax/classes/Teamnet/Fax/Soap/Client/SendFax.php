<?php

require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/SendfaxTypes.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Client/RecipientList.php';

/**
 * Client class to send faxes using the Teamnet FaxAPI webservice
 * @author tingelhoff@teamnet.de
 * @copyright Teamnet GmbH
 * @version 1.0
 */
class Teamnet_Fax_Soap_Client_SendFax
{
	/**
	 * @var SoapClient
	 */
	private $oSOAP;
	private $authKey;

	public function __construct( $wsdl, $authKey ) {
		try {
			if ( !isset( $wsdl ) ) {
				throw new ErrorException( 'WSDL isn\'t set.' );
			}
			$this->authKey = $authKey;
			$this->oSOAP = new SoapClient( (string) $wsdl, array( 'classmap' => getFaxapiSendfaxClassMap() ) );
		}
		catch ( SoapFault $exception ) {
			throw new ServiceException( $exception );
		}
	}


	/**
	 * Sends a fax to one specified recipient
	 * @param string			$sJobTitle
	 * @param string			$sContentType
	 * @param string			$sContent
	 * @param string			$sFaxnumber		Faxnummer
	 * @param JobOptions|null	$oJobOptions
	 * @return int JobID
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws AuthentificationException	if the given AuthKey is invalid
	 * @throws LimitExceededException		if you try to send to many jobs in a short time
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function sendFaxToFaxnumber($sJobTitle, $sContentType, $sContent,
									   $sFaxnumber, JobOptions $oJobOptions = null)
	{
		$oResponse = new SendFaxResponse();

		$oReq = new SendFaxToFaxnumber();
		$oReq->authKey = $this->authKey;
		$oReq->jobTitle = $sJobTitle;
		$oReq->contentType = $sContentType;
		$oReq->content = $sContent;
		$oReq->faxnumber = $sFaxnumber;
		$oReq->jobOptions = $oJobOptions;

		try {
			$oResponse = $this->oSOAP->sendFaxToFaxnumber($oReq);
		} catch (SoapFault $e) {
			throw isset($e->detail) ? $this->_convertSoapFault($e) : $e;
		}

		return $oResponse->jobId;
	}


	/**
	 * Sends a fax to a list of recipients
	 * @param string								$sJobTitle
	 * @param string|int							$mContentType
	 * @param string								$sContent
	 * @param Teamnet_Fax_Soap_Client_RecipientList	$aRecipientList	Array von FaxRecipient objekten
	 * @param JobOptions|null						$oJobOptions
	 * @return int JobID
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws AuthentificationException	if the given AuthKey is invalid
	 * @throws LimitExceededException		if you try to send to many jobs in a short time
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function sendFaxToRecipientList($sJobTitle, $mContentType, $sContent,
										   Teamnet_Fax_Soap_Client_RecipientList $oRecipientList,
										   JobOptions $oJobOptions = null)
	{
		$oResponse = new SendFaxResponse();

		$oReq = new SendFaxToFaxRecipientList();
		$oReq->authKey = $this->authKey;
		$oReq->jobTitle = $sJobTitle;
		$oReq->contentType = $mContentType;
		$oReq->content = $sContent;
		$oReq->faxRecipientList = $oList = new FaxRecipientList();
		$oReq->jobOptions = $oJobOptions;

		$oList->faxRecipient = $oRecipientList->getRecipients();

		try {
			$oResponse = $this->oSOAP->sendFaxToFaxRecipientList($oReq);
		} catch (SoapFault $e) {
			throw isset($e->detail) ? $this->_convertSoapFault($e) : $e;
		}
		return $oResponse->jobId;
	}


	/**
	 * Sends a fax to a distribution list
	 * @param string			$sJobTitle
	 * @param string|int		$mContentType
	 * @param string			$sContent
	 * @param int				$iDistributionListId	ID of distribution list
	 * @param JobOptions|null	$oJobOptions
	 * @return int JobID
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws AuthentificationException	if the given AuthKey is invalid
	 * @throws LimitExceededException		if you try to send to many jobs in a short time
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function sendFaxToDistributionList($sJobTitle, $mContentType, $sContent,
											  $iDistributionListId, JobOptions $oJobOptions = null)
	{
		$oResponse = new SendFaxResponse();

		$oReq = new SendFaxToDistributionList();
		$oReq->authKey = $this->authKey;
		$oReq->jobTitle = $sJobTitle;
		$oReq->contentType = $mContentType;
		$oReq->content = $sContent;
		$oReq->distributionListId = $iDistributionListId;
		$oReq->jobOptions = $oJobOptions;

		try {
			$oResponse = $this->oSOAP->sendFaxToDistributionList($oReq);
		} catch (SoapFault $e) {
			throw isset($e->detail) ? $this->_convertSoapFault($e) : $e;
		}
		return $oResponse->jobId;
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
