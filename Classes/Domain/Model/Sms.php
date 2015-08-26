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
class Tx_JccAppointments_Domain_Model_Sms extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * recipientName
	 *
	 * @var string
	 */
	protected $recipientName;

	/**
	 * recipientNumber
	 *
	 * @var string
	 */
	protected $recipientNumber;

	/**
	 * senderName
	 *
	 * @var string
	 */
	protected $senderName;

	/**
	 * message
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * appId
	 *
	 * @var integer
	 */
	protected $appId;

	/**
	 * status
	 *
	 * @var string
	 */
	protected $status;

	/**
	 * sendDate
	 *
	 * @var integer
	 */
	protected $sendDate;

	/**
	 * Returns the recipientName
	 *
	 * @return string $recipientName
	 */
	public function getRecipientName() {
		return $this->recipientName;
	}

	/**
	 * Sets the recipientName
	 *
	 * @param string $recipientName
	 * @return void
	 */
	public function setRecipientName($recipientName) {
		$this->recipientName = $recipientName;
	}

	/**
	 * Returns the recipientNumber
	 *
	 * @return string $recipientNumber
	 */
	public function getRecipientNumber() {
		return $this->recipientNumber;
	}

	/**
	 * Sets the recipientNumber
	 *
	 * @param string $recipientNumber
	 * @return void
	 */
	public function setRecipientNumber($recipientNumber) {
		$this->recipientNumber = $recipientNumber;
	}

	/**
	 * Returns the senderName
	 *
	 * @return string $senderName
	 */
	public function getSenderName() {
		return $this->senderName;
	}

	/**
	 * Sets the senderName
	 *
	 * @param string $senderName
	 * @return void
	 */
	public function setSenderName($senderName) {
		$this->senderName = $senderName;
	}

	/**
	 * Returns the message
	 *
	 * @return string $message
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Sets the message
	 *
	 * @param string $message
	 * @return void
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * Returns the appId
	 *
	 * @return integer $appId
	 */
	public function getAppId() {
		return $this->appId;
	}

	/**
	 * Sets the appId
	 *
	 * @param integer $appId
	 * @return void
	 */
	public function setAppId($appId) {
		$this->appId = $appId;
	}

	/**
	 * Returns the status
	 *
	 * @return string $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Sets the status
	 *
	 * @param string $status
	 * @return void
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * Returns the sendDate
	 *
	 * @return integer $sendDate
	 */
	public function getSendDate() {
		return $this->sendDate;
	}

	/**
	 * Sets the sendDate
	 *
	 * @param integer $sendDate
	 * @return void
	 */
	public function setSendDate($sendDate) {
		$this->sendDate = $sendDate;
	}

}
?>