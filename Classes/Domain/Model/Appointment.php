<?php
namespace Ucreation\JccAppointments\Domain\Model;

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
 * Class Appointment
 *
 * @package Ucreation\JccAppointments
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class Appointment extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var integer
	 */
	protected $appId;
	
	/**
	 * @var integer
	 */
	protected $appTime;
	
	/**
	 * @var string
	 */
	protected $secretHash;

	/**
	 * @var boolean
	 */
	protected $sms = FALSE;
	
	/**
	 * @var boolean
	 */
	protected $smsSend = FALSE;
	
	/**
	 * @var int
	 */
	protected $smsSendDate;
	
	/**
	 * @var boolean
	 */
	protected $mailSend = FALSE;
	
	/**
	 * @var boolean
	 */
	protected $closed = FALSE;
	
	/**
	 * @var string
	 */
	protected $clientId;
	
	/**
	 * @var string
	 */
	protected $clientInitials;
	
	/**
	 * @var string
	 */
	protected $clientInsertions;
	
	/**
	 * @var string
	 */
	protected $clientLastName;
	
	/**
	 * @var string
	 */
	protected $clientSex;
	
	/**
	 * @var integer
	 */
	protected $clientDateOfBirth;
	
	/**
	 * @var string
	 */
	protected $clientStreet;
	
	/**
	 * @var string
	 */
	protected $clientStreetNumber;
	
	/**
	 * @var string
	 */
	protected $clientPostalCode;
	
	/**
	 * @var string
	 */
	protected $clientCity;
	
	/**
	 * @var string
	 */
	protected $clientPhone;
	
	/**
	 * @var string
	 */
	protected $clientMobilePhone;
	
	/**
	 * @var string
	 */
	protected $clientEmail;
	
	/**
	 * @var string
	 */
	protected $locationName;
	
	/**
	 * @var string
	 */
	protected $locationAddress;
	
	/**
	 * @var string
	 */
	protected $locationPostalCode;
	
	/**
	 * @var string
	 */
	protected $locationPhone;
	
	/**
	 * Get App Id
	 *
	 * @return integer
	 */
	public function getAppId() {
		return $this->appId;
	}

	/**
	 * Set App Id
	 *
	 * @param integer $appId
	 * @return void
	 */
	public function setAppId($appId) {
		$this->appId = $appId;
	}
	
	/**
	 * Get App Time
	 *
	 * @return integer
	 */
	public function getAppTime() {
		return $this->appTime;
	}

	/**
	 * Set App Time
	 *
	 * @param integer $appTime
	 * @return void
	 */
	public function setAppTime($appTime) {
		$this->appTime = $appTime;
	}
	
	/**
	 * Get Secret Hash
	 *
	 * @return string
	 */
	public function getSecretHash() {
		return $this->secretHash;
	}

	/**
	 * Set Secret Hash
	 *
	 * @param string $secretHash
	 * @return void
	 */
	public function setSecretHash($secretHash) {
		$this->secretHash = $secretHash;
	}
	
	/**
	 * Get Sms
	 *
	 * @return boolean
	 */
	public function getSms() {
		return $this->sms;
	}

	/**
	 * Set Sms
	 *
	 * @param boolean $sms
	 * @return void
	 */
	public function setSms($sms) {
		$this->sms = $sms;
	}

	/**
	 * Is Sms
	 *
	 * @return boolean
	 */
	public function isSms() {
		return $this->getSms();
	}
	
	/**
	 * Get Sms Send
	 *
	 * @return boolean
	 */
	public function getSmsSend() {
		return $this->smsSend;
	}

	/**
	 * Set Sms Send
	 *
	 * @param boolean $smsSend
	 * @return void
	 */
	public function setSmsSend($smsSend) {
		$this->smsSend = $smsSend;
	}

	/**
	 * Is Sms Send
	 *
	 * @return boolean
	 */
	public function isSmsSend() {
		return $this->getSmsSend();
	}
	
	/**
	 * Get Sms Send Date
	 *
	 * @return integer
	 */
	public function getSmsSendDate() {
		return $this->smsSendDate;
	}

	/**
	 * Set Sms Send Date
	 *
	 * @param integer $smsSendDate
	 * @return void
	 */
	public function setSmsSendDate($smsSendDate) {
		$this->smsSendDate = $smsSendDate;
	}
	
	/**
	 * Get Mail Send
	 *
	 * @return boolean
	 */
	public function getMailSend() {
		return $this->mailSend;
	}

	/**
	 * Set Mail Send
	 *
	 * @param boolean $mailSend
	 * @return void
	 */
	public function setMailSend($mailSend) {
		$this->mailSend = $mailSend;
	}

	/**
	 * Is Mail Send
	 *
	 * @return boolean
	 */
	public function isMailSend() {
		return $this->getMailSend();
	}
	
	/**
	 * Get Closed
	 *
	 * @return boolean
	 */
	public function getClosed() {
		return $this->closed;
	}

	/**
	 * Set Closed
	 *
	 * @param boolean $closed
	 * @return void
	 */
	public function setClosed($closed) {
		$this->closed = $closed;
	}

	/**
	 * Is Closed
	 *
	 * @return boolean
	 */
	public function isClosed() {
		return $this->getClosed();
	}
	
	/**
	 * Get Client Id
	 *
	 * @return string
	 */
	public function getClientId() {
		return $this->clientId;
	}

	/**
	 * Set Client Id
	 *
	 * @param string $clientId
	 * @return void
	 */
	public function setClientId($clientId) {
		$this->clientId = $clientId;
	}
	
	/**
	 * Get Client Initials
	 *
	 * @return string
	 */
	public function getClientInitials() {
		return $this->clientInitials;
	}

	/**
	 * Set Client Initials
	 *
	 * @param string $clientInitials
	 * @return void
	 */
	public function setClientInitials($clientInitials) {
		$this->clientInitials = $clientInitials;
	}
	
	/**
	 * Get Client Insertions
	 *
	 * @return string
	 */
	public function getClientInsertions() {
		return $this->clientInsertions;
	}

	/**
	 * Set Client Insertions
	 *
	 * @param string $clientInsertions
	 * @return void
	 */
	public function setClientInsertions($clientInsertions) {
		$this->clientInsertions = $clientInsertions;
	}
	
	/**
	 * Get Client Last Name
	 *
	 * @return string
	 */
	public function getClientLastName() {
		return $this->clientLastName;
	}

	/**
	 * Set Client Last Name
	 *
	 * @param string $clientLastName
	 * @return void
	 */
	public function setClientLastName($clientLastName) {
		$this->clientLastName = $clientLastName;
	}
	
	/**
	 * Get Client Sex
	 *
	 * @return string
	 */
	public function getClientSex() {
		return $this->clientSex;
	}

	/**
	 * Set Client Sex
	 *
	 * @param string $clientSex
	 * @return void
	 */
	public function setClientSex($clientSex) {
		$this->clientSex = $clientSex;
	}
	
	/**
	 * Get Client Date Of Birth
	 *
	 * @return integer
	 */
	public function getClientDateOfBirth() {
		return $this->clientDateOfBirth;
	}

	/**
	 * Set Client Date Of Birth
	 *
	 * @param integer $clientDateOfbirth
	 * @return void
	 */
	public function setClientDateOfBirth($clientDateOfBirth) {
		$this->clientDateOfBirth = $clientDateOfBirth;
	}
	
	/**
	 * Get Client Street
	 *
	 * @return string
	 */
	public function getClientStreet() {
		return $this->clientStreet;
	}

	/**
	 * Set Client Street
	 *
	 * @param string $clientStreet
	 * @return void
	 */
	public function setClientStreet($clientStreet) {
		$this->clientStreet = $clientStreet;
	}
	
	/**
	 * Get Client Street Number
	 *
	 * @return string
	 */
	public function getClientStreetNumber() {
		return $this->clientStreetNumber;
	}

	/**
	 * Set Client Street Number
	 *
	 * @param string $clientStreetNumber
	 * @return void
	 */
	public function setClientStreetNumber($clientStreetNumber) {
		$this->clientStreetNumber = $clientStreetNumber;
	}
	
	/**
	 * Get Client Postal Code
	 *
	 * @return string
	 */
	public function getClientPostalCode() {
		return $this->clientPostalCode;
	}

	/**
	 * Set Client Postal Code
	 *
	 * @param string $clientPostalCode
	 * @return void
	 */
	public function setClientPostalCode($clientPostalCode) {
		$this->clientPostalCode = $clientPostalCode;
	}
	
	/**
	 * Get Client City
	 *
	 * @return string
	 */
	public function getClientCity() {
		return $this->clientCity;
	}

	/**
	 * Set Client City
	 *
	 * @param string $clientCity
	 * @return void
	 */
	public function setClientCity($clientCity) {
		$this->clientCity = $clientCity;
	}
	
	/**
	 * Get Client Phone
	 *
	 * @return string
	 */
	public function getClientPhone() {
		return $this->clientPhone;
	}

	/**
	 * Set Client Phone
	 *
	 * @param string $clientPhone
	 * @return void
	 */
	public function setClientPhone($clientPhone) {
		$this->clientPhone = $clientPhone;
	}
	
	/**
	 * Get Client Mobile Phone
	 *
	 * @return string
	 */
	public function getClientMobilePhone() {
		return $this->clientMobilePhone;
	}

	/**
	 * Set Client Mobile Phone
	 *
	 * @param string $clientMobilePhone
	 * @return void
	 */
	public function setClientMobilePhone($clientMobilePhone) {
		$this->clientMobilePhone = $clientMobilePhone;
	}
	
	/**
	 * Get Client Email
	 *
	 * @return string
	 */
	public function getClientEmail() {
		return $this->clientEmail;
	}

	/**
	 * Set Client Email
	 *
	 * @param string $clientEmail
	 * @return void
	 */
	public function setClientEmail($clientEmail) {
		$this->clientEmail = $clientEmail;
	}
	
	/**
	 * Get Location Name
	 *
	 * @return string
	 */
	public function getLocationName() {
		return $this->locationName;
	}

	/**
	 * Set Location Name
	 *
	 * @param string $locationName
	 * @return void
	 */
	public function setLocationName($locationName) {
		$this->locationName = $locationName;
	}
	/**
	 * Get Location Address
	 *
	 * @return string
	 */
	public function getLocationAddress() {
		return $this->locationAddress;
	}

	/**
	 * Set Location Address
	 *
	 * @param string $locationAddress
	 * @return void
	 */
	public function setLocationAddress($locationAddress) {
		$this->locationAddress = $locationAddress;
	}
	/**
	 * Get Location Postal Code
	 *
	 * @return string
	 */
	public function getLocationPostalCode() {
		return $this->locationPostalCode;
	}

	/**
	 * Set Location Postal Code
	 *
	 * @param string $locationPostalCode
	 * @return void
	 */
	public function setLocationPostalCode($locationPostalCode) {
		$this->locationPostalCode = $locationPostalCode;
	}
	/**
	 * Get Location Phone
	 *
	 * @return string
	 */
	public function getLocationPhone() {
		return $this->locationPhone;
	}

	/**
	 * Set Location Phone
	 *
	 * @param string $locationPhone
	 * @return void
	 */
	public function setLocationPhone($locationPhone) {
		$this->locationPhone = $locationPhone;
	}
	
}