<?php

/**
 * Represents a list of recipients, expected
 * by Teamnet_Fax_Soap_Client_SendFax::sendFaxToRecipientList
 * @author tingelhoff@teamnet.de
 * @copyright Teamnet GmbH
 * @version 1.0
 */
class Teamnet_Fax_Soap_Client_RecipientList
{
	private $aList;


	public function __construct( $recipientList = array() ) {
		$list = array();
		foreach( $recipientList as $recipient ) {
			if ( $recipient instanceof FaxRecipient ) {
				$list[] = $recipient;
			}
			else if ( is_array( $recipient ) ) {
				$faxRecipient = new FaxRecipient();
				if ( array_key_exists( $recipient, 0 ) ) {
					$faxRecipient->faxnumber = $recipient[0];
				}
				else if ( array_key_exists( $recipient, 'faxnumber' ) ) {
					$faxRecipient->faxnumber = $recipient['faxnumber'];
				}
				if ( array_key_exists( $recipient, 1 ) ) {
					$faxRecipient->name = $recipient[1];
				}
				else if ( array_key_exists( $recipient, 'name' ) ) {
					$faxRecipient->name = $recipient['name'];
				}
				if ( array_key_exists( $recipient, 2 ) ) {
					$faxRecipient->customerReference = $recipient[2];
				}
				else if ( array_key_exists( $recipient, 'customerReference' ) ) {
					$faxRecipient->customerReference = $recipient['customerReference'];
				}
				$list[] = $faxRecipient;
			}
			else if ( is_string( $recipient ) ) {
				$faxRecipient = new FaxRecipient();
				$faxRecipient->faxnumber = $recipient;
				$list[] = $faxRecipient;
			}
			else {
				throw new ErrorException( sprintf( "Unsupport type [%s] to construct a RecipientList.", get_class( $recipientList ) ) );
			}
		}
		$this->aList = $list;
	}

	/**
	 * Adds an recipient to this list
	 * @param string		$sFaxNumber
	 * @param string|null	$sName
	 * @param string|null	$sCustomerReference
	 * @return RecipientList fluent interface
	 */
	public function addRecipient( $sFaxNumber, $sName = null, $sCustomerReference = null ) {
		$oRecipient = new FaxRecipient();
		$oRecipient->faxnumber = $sFaxNumber;
		$oRecipient->name = $sName;
		$oRecipient->customerReference = $sCustomerReference;
		$this->add( $oRecipient );

		return $this;
	}


	public function add( $oRecipient ) {
		$this->aList[] = $oRecipient;
		return $this;
	}


	/**
	 * @return array FaxRecipient objects
	 */
	public function getRecipients() {
		return $this->aList;
	}

}