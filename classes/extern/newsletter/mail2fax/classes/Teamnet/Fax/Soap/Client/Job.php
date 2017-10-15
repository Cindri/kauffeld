<?php

require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/JobTypes.php';

/**
 * Client class to obtain or alter faxjobs using the Teamnet FaxAPI webservice
 * @author tingelhoff@teamnet.de
 * @copyright Teamnet GmbH
 * @version 1.0
 */
class Teamnet_Fax_Soap_Client_Job
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
			$this->oSOAP = new SoapClient( (string) $wsdl, array( 'classmap' => getFaxapiJobClassMap() ) );
		}
		catch ( SoapFault $exception ) {
			throw new ServiceException( $exception );
		}
		
	}

	/**
	 * Create a period object
	 *  
	 * @param string $sFrom Begin of the period formatted as ISO 8601 date
	 * @param string $sUntil End of the period formatted as ISO 8601 date
	 */
	public static function createPeriod( $sFrom, $sUntil ) {
		$oPeriod = new Period();
		$oPeriod->from = $sFrom;
		$oPeriod->until = $sUntil;
		return $oPeriod;
	}
	
	/**
	 * Gets a list of members associated to a specific job
	 * @param int $iJobID
	 * @return array of APIJobMemberList objects
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws AuthentificationException	if the given AuthKey is invalid
	 * @throws JobNotFoundException			if an job with the given ID was not found
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function getJobMembers($iJobID)
	{
		$oReq = new GetJobMembers();
		$oReq->authKey = $this->authKey;
		$oReq->jobId = $iJobID;

		$oResponse = new GetJobMembersResponse();

		try {
			$oResponse = $this->oSOAP->getJobMembers($oReq);
		} catch (SoapFault $e) {
			throw isset($e->detail) ? $this->_convertSoapFault($e) : $e;
		}

		return $oResponse->apiJobMemberList->apiJobMemberList;
	}


	/**
	 * Gets the costs of a specific job
	 * @param int $iJobID
	 * @return float|null
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws AuthentificationException	if the given AuthKey is invalid
	 * @throws JobNotFoundException			if an job with the given ID was not found
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function getJobCosts($iJobID)
	{
		$oReq = new GetJobCosts();
		$oReq->authKey = $this->authKey;
		$oReq->jobId = $iJobID;

		$oResponse = new GetJobCostsResponse();

		try {
			$oResponse = $this->oSOAP->getJobCosts($oReq);
		} catch (SoapFault $e) {
			throw isset($e->detail) ? $this->_convertSoapFault($e) : $e;
		}

		return $oResponse->costs;
	}


	/**
	 * Gets the current status of a specific job
	 * @param int $iJobID JobID
	 * @return APIJob
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws AuthentificationException	if the given AuthKey is invalid
	 * @throws JobNotFoundException			if an job with the given ID was not found
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function getJob($iJobID)
	{
		$oReq = new GetJob();
		$oReq->authKey = $this->authKey;
		$oReq->jobId = $iJobID;

		$oResponse = new GetJobResponse();

		try {
			$oResponse = $this->oSOAP->getJob($oReq);
		} catch (SoapFault $e) {
			throw isset($e->detail) ? $this->_convertSoapFault($e) : $e;
		}

		return $oResponse->apiJob;
	}


	/**
	 *
	 * @param int $iJobID JobID
	 * @return APIJobProfile
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws AuthentificationException	if the given AuthKey is invalid
	 * @throws JobNotFoundException			if an job with the given ID was not found
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function getJobProfile($iJobID)
	{
		$oReq = new GetJobProfile();
		$oReq->authKey = $this->authKey;
		$oReq->jobId = $iJobID;

		$oResponse = new GetJobProfileResponse();

		try {
			$oResponse = $this->oSOAP->getJobProfile($oReq);
		} catch (SoapFault $e) {
			throw isset($e->detail) ? $this->_convertSoapFault($e) : $e;
		}

		return $oResponse->apiJobProfile;
	}


	/**
	 * Returns a list of APIJob objects matching the delivered search parameters
	 * @param string|null	$sTitle
	 * @param string|null	$sStatus
	 * @param Period|null	$oPeriod
	 * @return array						APIJob objects
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws AuthentificationException	if the given AuthKey is invalid
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function getList($sTitle = null, $sStatus = null, Period $oPeriod = null)
	{
		$oReq = new GetList();
		$oReq->authKey = $this->authKey;
		$oReq->jobTitle = $sTitle;
		$oReq->status = $sStatus;
		$oReq->period = $oPeriod;

		$jobList = new JobList();

		try {
			$jobList = $this->oSOAP->getList($oReq);
		} catch (SoapFault $e) {
			throw isset($e->detail) ? $this->_convertSoapFault($e) : $e;
		}

		return $jobList->jobList->jobList;
	}


	/**
	 * Returns a list of APIJobMember objects matching the delivered search parameters
	 * @param string|null	$sTitle
	 * @param string|null	$sStatus
	 * @param Period|null	$oPeriod
	 * @return array						APIJobMember objects
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws AuthentificationException	if the given AuthKey is invalid
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function getMembers($sTitle = null, $sStatus = null, Period $oPeriod = null)
	{
		$oReq = new GetMembers();
		$oReq->authKey = $this->authKey;
		$oReq->jobTitle = $sTitle;
		$oReq->status = $sStatus;
		$oReq->period = $oPeriod;

		$jobList = new GetMembersResponse();

		try {
			$jobList = $this->oSOAP->getMembers($oReq);
		} catch (SoapFault $e) {
			throw isset($e->detail) ? $this->_convertSoapFault($e) : $e;
		}

		return $jobList->apiJobMemberList->apiJobMemberList;
	}



	/**
	 * Gets the current transport state of a specific job
	 * @param int $iJobID
	 * @return string	the current transport status
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws AuthentificationException	if the given AuthKey is invalid
	 * @throws JobNotFoundException			if an job with the given ID was not found
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function getTransportStatus($iJobID)
	{
		$oReq = new GetTransportStatus();
		$oReq->authKey = $this->authKey;
		$oReq->jobId = $iJobID;

		$oResponse = new GetTransportStatusResponse();

		try {
			$oResponse = $this->oSOAP->getTransportStatus($oReq);
		} catch (SoapFault $e) {
			throw isset($e->detail) ? $this->_convertSoapFault($e) : $e;
		}

		return $oResponse->transportStatus;
	}


	/**
	 * Alters the transport status of a specific job
	 * @param int		$iJobID
	 * @param string	$sTransportStatus	the transport status to set
	 * @return null
	 * @throws ServiceException				if an internal error in the fax service occured
	 * @throws MaintenanceException			if the fax service is currently down for maintenance
	 * @throws ParameterException			if parameters are invalid
	 * @throws AuthentificationException	if the given AuthKey is invalid
	 * @throws JobNotFoundException			if an job with the given ID was not found
	 * @throws SoapFault					if another SOAP related error occured
	 */
	public function setTransportStatus($iJobID, $sTransportStatus)
	{
		$oReq = new SetTransportStatus();
		$oReq->authKey = $this->authKey;
		$oReq->jobId = $iJobID;
		$oReq->transportStatus = $sTransportStatus;

		try {
			$this->oSOAP->setTransportStatus($oReq);
		} catch (SoapFault $e) {
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
