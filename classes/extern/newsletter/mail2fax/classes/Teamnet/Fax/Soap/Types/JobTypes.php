<?php

require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/APIJob.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/APIJobMember.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/APIJobMemberList.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/APIJobProfile.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/AuthentificationException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetJob.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetJobResponse.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetJobCosts.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetJobCostsResponse.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetJobMembers.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetJobMembersResponse.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetJobProfile.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetJobProfileResponse.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetList.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetListResponse.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetMembers.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetMembersResponse.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetTransportStatus.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/GetTransportStatusResponse.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/JobList.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/JobNotFoundException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/MaintenanceException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/ParameterException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/Period.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/ServiceException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/SetTransportStatus.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/SetTransportStatusResponse.php';



function getFaxapiJobClassMap() {
	return array(
		'APIJob'						=> 'APIJob',
		'APIJobMember'					=> 'APIJobMember',
		'APIJobMemberList'				=> 'APIJobMemberList',
		'APIJobProfile'					=> 'APIJobProfile',
		'AuthentificationException'		=> 'AuthentificationException',
		'GetFaxrecipientsReport'		=> 'GetFaxrecipientsReport',
		'GetJob'						=> 'GetJob',
		'GetJobCosts'					=> 'GetJobCosts',
		'GetJobCostsResponse'			=> 'GetJobCostsResponse',
		'GetJobMembers'					=> 'GetJobMembers',
		'GetJobMembersResponse'			=> 'GetJobMembersResponse',
		'GetJobProfile'					=> 'GetJobProfile',
		'GetJobProfileResponse'			=> 'GetJobProfileResponse',
		'GetJobResponse'				=> 'GetJobResponse',
		'GetList'						=> 'GetList',
		'GetMembers'					=> 'GetMembers',
		'GetMembersResponse'			=> 'GetMembersResponse',
		'GetListResponse'				=> 'GetListResponse',
		'GetTransportStatus'			=> 'GetTransportStatus',
		'GetTransportStatusResponse'	=> 'GetTransportStatusResponse',
		'JobList'						=> 'JobList',
		'JobNotFoundException'			=> 'JobNotFoundException',
		'MaintenanceException'			=> 'MaintenanceException',
		'ParameterException'			=> 'ParameterException',
		'Period'						=> 'Period',
		'ServiceException'				=> 'ServiceException',
		'SetTransportStatus'			=> 'SetTransportStatus',
		'SetTransportStatusResponse'	=> 'SetTransportStatusResponse'
	);
}
