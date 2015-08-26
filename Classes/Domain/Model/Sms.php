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
 * Class Sms
 *
 * @package Ucreation\JccAppointments
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class Sms extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var string
	 */
	protected $recipientName;

	/**
	 * @var string
	 */
	protected $recipientNumber;

	/**
	 * @var string
	 */
	protected $senderName;

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @var integer
	 */
	protected $appId;

	/**
	 * @var string
	 */
	protected $status;

	/**
	 * @var integer
	 */
	protected $sendDate;

	/**
	 * Get Recipient Name
	 *
	 * @return string
	 */
	public function getRecipientName() {
		return $this->recipientName;
	}

	/**
	 * Set Recipient Name
	 *
	 * @param string $recipientName
	 * @return void
	 */
	public function setRecipientName($recipientName) {
		$this->recipientName = $recipientName;
	}

	/**
	 * Get Recipient Number
	 *
	 * @return string
	 */
	public function getRecipientNumber() {
		return $this->recipientNumber;
	}

	/**
	 * Set Recipient Number
	 *
	 * @param string $recipientNumber
	 * @return void
	 */
	public function setRecipientNumber($recipientNumber) {
		$this->recipientNumber = $recipientNumber;
	}

	/**
	 * Get Sender Name
	 *
	 * @return string
	 */
	public function getSenderName() {
		return $this->senderName;
	}

	/**
	 * Set Sender Name
	 *
	 * @param string $senderName
	 * @return void
	 */
	public function setSenderName($senderName) {
		$this->senderName = $senderName;
	}

	/**
	 * Get Message
	 *
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Set Message
	 *
	 * @param string $message
	 * @return void
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

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
	 * Get Status
	 *
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Set Status
	 *
	 * @param string $status
	 * @return void
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * Get Send Date
	 *
	 * @return integer
	 */
	public function getSendDate() {
		return $this->sendDate;
	}

	/**
	 * Set Send Date
	 *
	 * @param integer $sendDate
	 * @return void
	 */
	public function setSendDate($sendDate) {
		$this->sendDate = $sendDate;
	}

}