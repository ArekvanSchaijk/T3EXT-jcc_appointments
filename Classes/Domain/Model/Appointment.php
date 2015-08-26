<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Arek van Schaijk <info@ucreation.nl>, Ucreation
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package jcc_appointments
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_JccAppointments_Domain_Model_Appointment extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * app id
	 *
	 * @var integer
	 */
	protected $appId;
	
	/**
	 * app time
	 *
	 * @var integer
	 */
	protected $appTime;
	
	/**
	 * secret hash
	 *
	 * @var string
	 */
	protected $secretHash;

	/**
	 * sms
	 *
	 * @var boolean
	 */
	protected $sms = false;
	
	/**
	 * sms send
	 *
	 * @var boolean
	 */
	protected $smsSend = false;
	
	/**
	 * sms send date
	 *
	 * @var int
	 */
	protected $smsSendDate;
	
	/**
	 * mail send
	 *
	 * @var boolean
	 */
	protected $mailSend = false;
	
	/**
	 * closed
	 *
	 * @var boolean
	 */
	protected $closed = false;
	
	/**
	 * Client ID
	 *
	 * @var string
	 */
	protected $clientId = false;
	
	/**
	 * Client Initials
	 *
	 * @var string
	 */
	protected $clientInitials = false;
	
	/**
	 * Client Insertions
	 *
	 * @var string
	 */
	protected $clientInsertions = false;
	
	/**
	 * Client Last Name
	 *
	 * @var string
	 */
	protected $clientLastName = false;
	
	/**
	 * Client Sex
	 *
	 * @var string
	 */
	protected $clientSex;
	
	/**
	 * Client Date Of Birth
	 *
	 * @var int
	 */
	protected $clientDateOfBirth = false;
	
	/**
	 * Client Street
	 *
	 * @var string
	 */
	protected $clientStreet = false;
	
	/**
	 * Client Street Number
	 *
	 * @var string
	 */
	protected $clientStreetNumber = false;
	
	/**
	 * Client Postal Code
	 *
	 * @var string
	 */
	protected $clientPostalCode = false;
	
	/**
	 * Client City
	 *
	 * @var string
	 */
	protected $clientCity = false;
	
	/**
	 * Client Phone
	 *
	 * @var string
	 */
	protected $clientPhone = false;
	
	/**
	 * Client Mobile Phone
	 *
	 * @var string
	 */
	protected $clientMobilePhone = false;
	
	/**
	 * Client Email
	 *
	 * @var string
	 */
	protected $clientEmail = false;
	
	/**
	 * Location Name
	 *
	 * @var string
	 */
	protected $locationName = false;
	
	/**
	 * Location Address
	 *
	 * @var string
	 */
	protected $locationAddress = false;
	
	/**
	 * Location Postal Code
	 *
	 * @var string
	 */
	protected $locationPostalCode = false;
	
	/**
	 * Location Phone
	 *
	 * @var string
	 */
	protected $locationPhone = false;
	
	/**
	 * Returns the app id
	 *
	 * @return integer $appId
	 */
	public function getAppId() {
		return $this->appId;
	}

	/**
	 * Sets the app id
	 *
	 * @param integer $appId
	 * @return void
	 */
	public function setAppId($appId) {
		$this->appId = $appId;
	}
	
	/**
	 * Returns the app time
	 *
	 * @return integer $appTime
	 */
	public function getAppTime() {
		return $this->appTime;
	}

	/**
	 * Sets the app time
	 *
	 * @param integer $appTime
	 * @return void
	 */
	public function setAppTime($appTime) {
		$this->appTime = $appTime;
	}
	
	/**
	 * Returns the secret hash
	 *
	 * @return string $secretHash
	 */
	public function getSecretHash() {
		return $this->secretHash;
	}

	/**
	 * Sets the secret hash
	 *
	 * @param string $secretHash
	 * @return void
	 */
	public function setSecretHash($secretHash) {
		$this->secretHash = $secretHash;
	}
	
	/**
	 * Returns the sms
	 *
	 * @return boolean $sms
	 */
	public function getSms() {
		return $this->sms;
	}

	/**
	 * Sets the sms
	 *
	 * @param boolean $sms
	 * @return void
	 */
	public function setSms($sms) {
		$this->sms = $sms;
	}

	/**
	 * Returns the boolean state of sms
	 *
	 * @return boolean
	 */
	public function isSms() {
		return $this->getSms();
	}
	
	/**
	 * Returns the sms send
	 *
	 * @return boolean $smsSend
	 */
	public function getSmsSend() {
		return $this->smsSend;
	}

	/**
	 * Sets the sms send
	 *
	 * @param boolean $smsSend
	 * @return void
	 */
	public function setSmsSend($smsSend) {
		$this->smsSend = $smsSend;
	}

	/**
	 * Returns the boolean state of sms send
	 *
	 * @return boolean
	 */
	public function isSmsSend() {
		return $this->getSmsSend();
	}
	
	/**
	 * Returns the sms send date
	 *
	 * @return integer $smsSendDate
	 */
	public function getSmsSendDate() {
		return $this->smsSendDate;
	}

	/**
	 * Sets the sms send date
	 *
	 * @param integer $smsSendDate
	 * @return void
	 */
	public function setSmsSendDate($smsSendDate) {
		$this->smsSendDate = $smsSendDate;
	}
	
	/**
	 * Returns the mail send
	 *
	 * @return boolean $mailSend
	 */
	public function getMailSend() {
		return $this->mailSend;
	}

	/**
	 * Sets the mail send
	 *
	 * @param boolean $mailSend
	 * @return void
	 */
	public function setMailSend($mailSend) {
		$this->mailSend = $mailSend;
	}

	/**
	 * Returns the boolean state of mail send
	 *
	 * @return boolean
	 */
	public function isMailSend() {
		return $this->getMailSend();
	}
	
	/**
	 * Returns the closed
	 *
	 * @return boolean $closed
	 */
	public function getClosed() {
		return $this->closed;
	}

	/**
	 * Sets the closed
	 *
	 * @param boolean $closed
	 * @return void
	 */
	public function setClosed($closed) {
		$this->closed = $closed;
	}

	/**
	 * Returns the boolean state of closed
	 *
	 * @return boolean
	 */
	public function isClosed() {
		return $this->getClosed();
	}
	
	/**
	 * Returns the client id
	 *
	 * @return string $clientId
	 */
	public function getClientId() {
		return $this->clientId;
	}

	/**
	 * Sets the client id
	 *
	 * @param string $clientId
	 * @return void
	 */
	public function setClientId($clientId) {
		$this->clientId = $clientId;
	}
	
	/**
	 * Returns the client initials
	 *
	 * @return string $clientInitials
	 */
	public function getClientInitials() {
		return $this->clientInitials;
	}

	/**
	 * Sets the client initials
	 *
	 * @param string $clientInitials
	 * @return void
	 */
	public function setClientInitials($clientInitials) {
		$this->clientInitials = $clientInitials;
	}
	
	/**
	 * Returns the client insertions
	 *
	 * @return string $clientInsertions
	 */
	public function getClientInsertions() {
		return $this->clientInsertions;
	}

	/**
	 * Sets the client insertions
	 *
	 * @param string $clientInsertions
	 * @return void
	 */
	public function setClientInsertions($clientInsertions) {
		$this->clientInsertions = $clientInsertions;
	}
	
	/**
	 * Returns the client last name
	 *
	 * @return string $clientLastName
	 */
	public function getClientLastName() {
		return $this->clientLastName;
	}

	/**
	 * Sets the client last name
	 *
	 * @param string $clientLastName
	 * @return void
	 */
	public function setClientLastName($clientLastName) {
		$this->clientLastName = $clientLastName;
	}
	
	/**
	 * Returns the client sex
	 *
	 * @return string $clientSex
	 */
	public function getClientSex() {
		return $this->clientSex;
	}

	/**
	 * Sets the client last name
	 *
	 * @param string $clientSex
	 * @return void
	 */
	public function setClientSex($clientSex) {
		$this->clientSex = $clientSex;
	}
	
	/**
	 * Returns the client date of birth
	 *
	 * @return int $clientDateOfBirth
	 */
	public function getClientDateOfBirth() {
		return $this->clientDateOfBirth;
	}

	/**
	 * Sets the client date of birth
	 *
	 * @param int $clientDateOfbirth
	 * @return void
	 */
	public function setClientDateOfBirth($clientDateOfBirth) {
		$this->clientDateOfBirth = $clientDateOfBirth;
	}
	
	/**
	 * Returns the client street
	 *
	 * @return string $clientStreet
	 */
	public function getClientStreet() {
		return $this->clientStreet;
	}

	/**
	 * Sets the client street
	 *
	 * @param string $clientStreet
	 * @return void
	 */
	public function setClientStreet($clientStreet) {
		$this->clientStreet = $clientStreet;
	}
	
	/**
	 * Returns the client street number
	 *
	 * @return string $clientStreetNumber
	 */
	public function getClientStreetNumber() {
		return $this->clientStreetNumber;
	}

	/**
	 * Sets the client street number
	 *
	 * @param string $clientStreetNumber
	 * @return void
	 */
	public function setClientStreetNumber($clientStreetNumber) {
		$this->clientStreetNumber = $clientStreetNumber;
	}
	
	/**
	 * Returns the client postal code
	 *
	 * @return string $clientPostalCode
	 */
	public function getClientPostalCode() {
		return $this->clientPostalCode;
	}

	/**
	 * Sets the client postal code
	 *
	 * @param string $clientPostalCode
	 * @return void
	 */
	public function setClientPostalCode($clientPostalCode) {
		$this->clientPostalCode = $clientPostalCode;
	}
	
	/**
	 * Returns the client city
	 *
	 * @return string $clientCity
	 */
	public function getClientCity() {
		return $this->clientCity;
	}

	/**
	 * Sets the client city
	 *
	 * @param string $clientCity
	 * @return void
	 */
	public function setClientCity($clientCity) {
		$this->clientCity = $clientCity;
	}
	
	/**
	 * Returns the phone
	 *
	 * @return string $clientPhone
	 */
	public function getClientPhone() {
		return $this->clientPhone;
	}

	/**
	 * Sets the client phone
	 *
	 * @param string $clientPhone
	 * @return void
	 */
	public function setClientPhone($clientPhone) {
		$this->clientPhone = $clientPhone;
	}
	
	/**
	 * Returns the client mobile phone
	 *
	 * @return string $clientMobilePhone
	 */
	public function getClientMobilePhone() {
		return $this->clientMobilePhone;
	}

	/**
	 * Sets the client mobile phone
	 *
	 * @param string $clientMobilePhone
	 * @return void
	 */
	public function setClientMobilePhone($clientMobilePhone) {
		$this->clientMobilePhone = $clientMobilePhone;
	}
	
	/**
	 * Returns the client email
	 *
	 * @return string $clientEmail
	 */
	public function getClientEmail() {
		return $this->clientEmail;
	}

	/**
	 * Sets the client email
	 *
	 * @param string $clientEmail
	 * @return void
	 */
	public function setClientEmail($clientEmail) {
		$this->clientEmail = $clientEmail;
	}
	
	/**
	 * Returns the location name
	 *
	 * @return string $locationName
	 */
	public function getLocationName() {
		return $this->locationName;
	}

	/**
	 * Sets the location name
	 *
	 * @param string $locationName
	 * @return void
	 */
	public function setLocationName($locationName) {
		$this->locationName = $locationName;
	}
	/**
	 * Returns the location address
	 *
	 * @return string $locationAddress
	 */
	public function getLocationAddress() {
		return $this->locationAddress;
	}

	/**
	 * Sets the location address
	 *
	 * @param string $locationAddress
	 * @return void
	 */
	public function setLocationAddress($locationAddress) {
		$this->locationAddress = $locationAddress;
	}
	/**
	 * Returns the location postal code
	 *
	 * @return string $locationPostalCode
	 */
	public function getLocationPostalCode() {
		return $this->locationPostalCode;
	}

	/**
	 * Sets the location postal code
	 *
	 * @param string $locationPostalCode
	 * @return void
	 */
	public function setLocationPostalCode($locationPostalCode) {
		$this->locationPostalCode = $locationPostalCode;
	}
	/**
	 * Returns the location phone
	 *
	 * @return string $locationPhone
	 */
	public function getLocationPhone() {
		return $this->locationPhone;
	}

	/**
	 * Sets the location phone
	 *
	 * @param string $locationPhone
	 * @return void
	 */
	public function setLocationPhone($locationPhone) {
		$this->locationPhone = $locationPhone;
	}
}
?>