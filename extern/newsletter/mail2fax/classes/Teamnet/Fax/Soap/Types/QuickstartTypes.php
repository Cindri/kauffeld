<?php


require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/AuthentificationException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/CheckAuthentificationRequest.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/CheckAuthentificationResponse.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/CheckVersionRequest.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/CheckVersionResponse.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/MaintenanceException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/ServiceException.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Types/ParameterException.php';


function getFaxapiQuickstartClassMap()
{
	return array(
		'AuthentificationException'		=> 'AuthentificationException',
		'CheckAuthentificationRequest'	=> 'CheckAuthentificationRequest',
		'CheckAuthentificationResponse'	=> 'CheckAuthentificationResponse',
		'CheckVersionRequest'			=> 'CheckVersionRequest',
		'CheckVersionResponse'			=> 'CheckVersionResponse',
		'MaintenanceException'			=> 'MaintenanceException',
		'ParameterException'			=> 'ParameterException',
		'ServiceException'				=> 'ServiceException'
	);
}
