<?php
namespace TYPO3\JccAppointments\Command;

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
 * ReminderCommandController
 *
 * @package jcc_appointments
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ReminderCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {
	
	/**
	 * Ext Key
	 *
	 * @var string
	 */
	protected $extKey = 'jccappointments';

    /**
     * Settings
	 *
     * @var array
     */
    protected $settings = array();
	
	/**
	 * Configuration Manager
	 *
	 * @var mixed
	 */
	protected $configurationManager;
	
	/**
	 * Appointment Repository
	 *
	 * @var mixed
	 */
	protected $appointmentRepository;
	
	/**
	 * Sms Repository
	 *
	 * @var mixed
	 */
	protected $smsRepository;

    /**
     * Initialize the controller.
     * @return void
     */
    protected function initializeCommand() {
		
		// initialize extbase configurationManager
        $this->configurationManager = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
		
		// import settings
       	$this->settings = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
		
		// import repositories
		$this->appointmentRepository = $this->objectManager->get('TYPO3\\JccAppointments\\Domain\\Repository\\AppointmentRepository');
		$this->smsRepository = $this->objectManager->get('TYPO3\\JccAppointments\\Domain\\Repository\\SmsRepository');
    }

    /**
	 * Sms Reminder Command
	 *
	 * @return void
	 */
    public function smsReminderCommand() {
			
		// initialize command
		$this->initializeCommand();
		
		// if sms notifications are enabled
		if($this->settings['sms']['enabled']) {
		
			// prepare interval
			$interval = $this->settings['sms']['hour_interval'] * 60 * 60;
			
			// limit
			$limit = $this->settings['sms']['limit'];
			
			// find appointments that should receive a sms message
			$appointments = $this->appointmentRepository->findUnsendSms($interval, $limit);
			
			if($appointments) {
				
				// loop through appointments
				foreach($appointments as $appointment) {
					
					if($appointment->getClientMobilePhone()) {
						
						// render message
						$variables = array(
							'appointment' => $appointment
						);
						$view = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
						$view->setFormat('text');
						$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
						$templateRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']);
						$templatePathAndFilename = $templateRootPath.'Sms/Reminder.html';
						$view->setTemplatePathAndFilename($templatePathAndFilename);
						$view->assignMultiple($variables);
						$smsMessage = $view->render();
						
						// send SMS
						$this->_sendSms($appointment, $smsMessage);
					}				
				}
			}
			return TRUE;
		}
		return FALSE;
    }
	
	/**
	 * Send SMS
	 *
	 * @param object $appointment
	 * @param string $message
	 * @return boolean
	 */
	private function _sendSms($appointment, $message) {
		
		// prepare post data
		$postData = array (
            'username'     => $this->settings['sms']['mollie_login'],
            'password'     => $this->settings['sms']['mollie_password'],
            'destination'  => $appointment->getClientMobilePhone(),
            'sender'       => $this->settings['confirmation']['sender']['name'],
            'body'         => $message,
			'responseType' => 'SIMPLE',
        );
		
		// post data
		$result = $this->_httpPost('https://api.messagebird.com/api/sms', $postData);
		
		// if message was send succesfully
		if($result == '01') {
			
			// update appointment
			$appointment->setSmsSend(TRUE);	
			$appointment->setSmsSendDate(time());
			$this->appointmentRepository->update($appointment);
			
			// prepare name
			$fullName = '';
			if($appointment->getClientInitials())
				$fullName = $appointment->getClientInitials().' ';
			if($appointment->getClientInsertions())
				$fullName .= $appointment->getClientInsertions().' ';
			if($appointment->getClientLastName())
				$fullName .= $appointment->getClientLastName();
			$fullName = trim($fullName);
			
			// save sms
			$sms = new \TYPO3\JccAppointments\Domain\Model\Sms;
			$sms->setRecipientName($fullName);
			$sms->setRecipientNumber($appointment->getClientMobilePhone());
			$sms->setSenderName($this->settings['confirmation']['sender']['name']);
			$sms->setMessage($message);
			$sms->setAppId($appointment->getAppId());
			$sms->setStatus($result);
			$sms->setSendDate(time());
			$this->smsRepository->add($sms);						
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Http Post
	 *
	 * @param string $url
	 * @param array $postData
	 * @return string
	 */
	private function _httpPost($url, $postData) {		
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
        return $result;
    }
}