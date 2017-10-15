<?php

require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/AuthentificationException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/FaxRecipient.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/FaxRecipientList.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/LimitExceededException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/JobOptions.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/MaintenanceException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/ParameterException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/SendFaxToDistributionList.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/SendFaxToFaxnumber.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/SendFaxToFaxRecipientList.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/SendFaxResponse.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/ServiceException.php';


function getFaxapiSendfaxClassMap()
{
	return array(
		'AuthentificationException'	=> 'AuthentificationException',
		'FaxRecipient'				=> 'FaxRecipient',
		'FaxRecipientList'			=> 'FaxRecipientList',
		'JobOptions'				=> 'JobOptions',
	    'LimitExceededException'	=> 'LimitExceededException',
		'MaintenanceException'		=> 'MaintenanceException',
		'ParameterException'		=> 'ParameterException',
		'SendFaxToDistributionList'	=> 'SendFaxToDistributionList',
		'SendFaxToFaxnumber'		=> 'SendFaxToFaxnumber',
		'SendFaxToFaxRecipientList'	=> 'SendFaxToFaxRecipientList',
		'SendFaxResponse'			=> 'SendFaxResponse',
		'ServiceException'			=> 'ServiceException'
	);
}
