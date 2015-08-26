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
}
?>