<?php

require_once 'config.inc.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Client/SendFax.php';
require_once INCLUDE_PATH.'Teamnet/Fax/Soap/Client/RecipientList.php';


// === Parameter des neuen Jobs ===

// Die Empfänger, werden vom Aufrufer mit Zeilenumbrüchen getrennt übergeben
$a_Recipients = isset($faxempf)
			  ? explode("\n", $faxempf) : null;

// Da die Empfängerliste aus einer Textarea kommen
// könnte, leere Einträge suchen und entfernen
$a_Recipients = array_values( array_diff($a_Recipients, array('')));

// Bezeichnung des neuen Jobs
$s_Jobtitle = isset($_REQUEST['title']) ? $_REQUEST['title'] : 'Wochenkarte am '.date("d_m");

// Die Faxvorlage wird im PDF Format übergeben
$i_Contenttype = 'application/pdf';


// Sendfax wrapper
$o_SendFax = new Teamnet_Fax_Soap_Client_SendFax( FAX_SENDFAX_WSDL, FAX_AUTHKEY );


// === Fax versenden ===

try {
	// Empfängerliste erzeugen
	$recipientList = new Teamnet_Fax_Soap_Client_RecipientList( $a_Recipients );
	// Faxauftrag abschicken
	$i_JobID = $o_SendFax->sendFaxToRecipientList( $s_Jobtitle, $i_Contenttype, $dateiinhalt, $recipientList );

	echo '
	<p><br><u>Fax-Empf&auml;nger:</u><p>
	';
	
	foreach ($a_Recipients as $sRecipient)
	{
		echo $sRecipient." (".$output[$sRecipient]['name']." - ".$output[$sRecipient]['strasse']." - ".$output[$sRecipient]['ort']." - ".$output[$sRecipient]['telefon']." )<br>";
	}

	echo '<p><br>JobID (bei Problemen aufschreiben!): '.$i_JobID.'<br>';
} catch (ParameterException $e) {
	echo 'Parameter '.$e->parameter.': '.$e->getMessage();
} catch (AuthentificationException $e) {
	echo $e->getMessage();
} catch (ServiceException $e) {
	echo 'Der API-Endpunkt meldet einen internen Fehler: '.
		 $e->getMessage();
} catch (MaintenanceException $e) {
	echo 'Wartungsarbeiten am API-Endpunkt: '.$e->getMessage();
} catch (LimitExceededException $e) {
	echo 'Es ist dieser Fehler: <br/>';
	echo $e->getMessage();
} catch (SoapFault $e) {
	echo 'SoapFault: '.$e->getMessage();
}