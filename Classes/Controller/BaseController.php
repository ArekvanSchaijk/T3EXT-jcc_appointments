<?php
namespace TYPO3\JccAppointments\Controller;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * BaseController
 *
 * @package jcc_appointments
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class BaseController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	
	/**
	 * @var SoapClient $api
	 */ 
	protected $api;
	
	/**
	 * @var array $userSession
	 */
	protected $session;
	
	/**
	 * @var array $data
	 */
	protected $data = array();
	
	/**
	 * @var string $extKey
	 */
	protected $extKey = 'tx_jccappointments';
	
	/**
	 * @var string $extensionName
	 */
	protected $extensionName = 'JccAppointments';
	
	/**
	 * @var integer $steps
	 */
	protected $steps = 5;
	
	/**
	 * @var boolean $stepValidation
	 */
	protected $stepValidation = TRUE;
	
	/**
	 * @var array $params
	 */
	protected $params;
	
	/**
	 * @const integer CANCEL_NO_SECRETHASH_GIVEN
	 */
	const CANCEL_NO_SECRETHASH_GIVEN = 1;
	
	/**
	 * @const integer CANCEL_INVALID_SECRETHASH
	 */
	const CANCEL_INVALID_SECRETHASH = 2;
	
	/**
	 * @const integer CANCEL_APPOINTMENT_EXPIRED
	 */
	const CANCEL_APPOINTMENT_EXPIRED = 3;
	
	/**
	 * @const integer CANCEL_ALREADY_CLOSED
	 */
	const CANCEL_ALREADY_CLOSED = 4;
	
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		
		$this->session = $this->getUserSession();
	}
	
	/**
	 * Initialize Action
	 *
	 * @return void
	 */
	public function initializeAction() {
		
		$this->params = $this->request->getArguments();
		
		// checks if the session is expired
		if($this->isUserSessionExpired()) {
			
			// removes the current session
			$this->removeUserSession();
			
			// adds a flash message
			if($this->settings['general']['sessionExpiredMessage'])			
				$this->flashMessageContainer->add(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('session.expired.message', $this->extensionName));
				
			// redirects to step 1
			$this->redirect(NULL);
			exit;
		}
	}
	
	/**
	 * Is Session Expired
	 *
	 * @return boolean
	 */
	protected function isUserSessionExpired() {
		
		$isExpired = FALSE;
		
		if(
			// checks if the setting is set
			$this->settings['general']['sessionLifetime'] &&
			// checks if the timestamp is available
			$this->session['timestamp'] &&
			// calculated if the session is expired
			time() - $this->session['timestamp'] > $this->settings['general']['sessionLifetime']
		) {
			
			$isExpired = TRUE;
		}	
			
		return $isExpired;
	}

	/**
	 * Api
	 *
	 * @return SoapClient $api
	 */
	protected function api() {
		
		// initialize SoapClient if not loaded yet
		if(!$this->api) {
			
			try {
			
				$this->api = new \SoapClient($this->settings['soap']['wsdl']);
				
			} catch(SoapFault $e) {
				
				// if the "service unavailable" page id is set we have to forward trough it
				if($this->settings['soap']['serviceUnavailablePid']) {
					
					$this->pageRedirect($this->settings['soap']['serviceUnavailablePid']);
				
				} else {
					
					throw new \Exception('The SOAP service is currently unavailable');
				}
			}
		}
		
		return $this->api;
	}
	
	/**
	 * Get User Data
	 *
	 * @return void
	 */
	protected function getUserSession() {
		
		/** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $TSFE */
		global $TSFE;
		
		return $TSFE->fe_user->getKey('ses', $this->extKey);
	}
	
	/**
	 * Set User Data
	 *
	 * @param array $data
	 * @return void
	 */
	protected function setUserSession($data) {
		
		/** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $TSFE */
		global $TSFE;
		
		// sets the timestamp (calculated the age of the session)
		if(!is_null($data))
			$data['timestamp'] = time();
		
		$this->session = $data;
		
		$TSFE->fe_user->setKey('ses', $this->extKey, $data);
		$TSFE->fe_user->storeSessionData();
	}
	
	/**
	 * Remove User Session
	 *
	 * @return void
	 */
	protected function removeUserSession() {
		
		$this->session = NULL;
		
		// save session data
		$this->saveSessionData();
	}
	
	/**
	 * Save Session Data
	 *
	 * @return void
	 */
	protected function saveSessionData() {
		
		$this->setUserSession($this->session);
	}
	
	/**
	 * Add Product To Session
	 *
	 * @param integer $productId
	 * @param object $product
	 * @return void
	 */
	protected function addProductToSession($productId, $product) {
	
		// define `products` as array if it doesnt exist yet
		if(!$this->session['products'])
			$this->session['products'] = array();
			
		// add product
		$this->session['products'][] = $this->renderProductDetailArray($productId, $product);

		// save session data
		$this->saveSessionData();
	}
	
	/**
	 * Render Product Detail Array
	 *
	 * @param integer $productId
	 * @param object $product
	 * @return array
	 */
	protected function renderProductDetailArray($productId, $product, $encodeRequisites = FALSE) {
		
		$productDetails = array(
			'uid'			=> $productId,
			'name'			=> $product->description,
			'duration'		=> $product->appDuration,
			'requisites'	=> $product->requisites,
		);
		
		if($encodeRequisites)
			$productDetails['requisites'] = $this->cleanProductRequisites(base64_decode($productDetails['requisites']));
		
		return $productDetails;
	}
	
	/**
	 * Get Products
	 *
	 * @return array
	 */
	protected function getProducts() {
		
		return $this->session['products'];	
	}
	
	/**
	 * Get Products And Render Requisites
	 *
	 * @return array
	 */
	protected function getProductsAndRenderRequisites() {
		
		$productArray = array();
		
		// foreach session products
		foreach($this->session['products'] as $product) {
			
			$product['requisites'] = $this->cleanProductRequisites(base64_decode($product['requisites']));
			$productArray[] = $product;
		}
		
		
		return $productArray;
	}
	
	/**
	 * Remove Product Key From Session
	 *
	 * @param integer $key
	 * @return void
	 */
	protected function removeProductKeyFromSession($key) {
		
		unset($this->session['products'][$key]);
		$this->saveSessionData();
	}
	
	/**
	 * Is Products In Session
	 *
	 * @return boolean
	 */
	protected function isProductsInSession() {
		
		$isProducts = FALSE;
		
		// check session if products exists
		if($this->session['products'] && count($this->session['products']) > 0)
			$isProducts = TRUE;
			
		return $isProducts;
	}
	
	/**
	 * Get Product Id List
	 *
	 * @return string
	 */
	protected function getProductIdList() {
		
		$productList = '';
		
		// foreach products
		foreach($this->session['products'] as $product) {
			
			$productList .= $product['uid'].',';	
		}
		
		// right trim seperator
		$productList = rtrim($productList, ',');

		
		return $productList;
	}
	
	/**
	 * Get First Product From Session
	 * 
	 * @return integer
	 */
	protected function getFirstProductFromSession() {
		
		return array_shift(array_values($this->session['products']));
	}
	
	/**
	 * Get Session Products
	 *
	 * @return array
	 */
	protected function getSessionProducts() {
		
		return $this->session['products'];
	}
	
	/**
	 * Get Products Duration
	 *
	 * @return integer
	 */
	protected function getProductsDuration() {

        $productsDuration = 0;

        // foreach products
        foreach($this->session['products'] as $product) {

            // append product duration to total productsDuration
            $productsDuration += $product['duration'];
        }
		
		return $productsDuration;
	}
	
	/**
	 * Add Location To Session
	 *
	 * @param integer $locationUid
	 * @return void
	 */
	protected function addLocationToSession($locationUid) {
		
		$this->session['location'] = $locationUid;
		
		// save session data
		$this->saveSessionData();
	}
	
	/**
	 * Is Location In Session
	 *
	 * @return boolean
	 */
	protected function isLocationInSession() {
		
		$isLocation = FALSE;
		
		// check session if location exist
		if($this->session['location'])
			$isLocation = TRUE;
			
		return $isLocation;
	}
	
	/**
	 * Get Location
	 *
	 * @return integer
	 */
	protected function getLocation() {
		
		return $this->session['location'];
	}
	
	/**
	 * Add Day To Session
	 *
	 * @param string $day
	 * @return void
	 */
	protected function addDayToSession($day) {
		
		$this->session['day'] = $day;
		
		// save session data
		$this->saveSessionData();
	}
	
	/**
	 * Is Day In Session
	 *
	 * @return boolean
	 */
	protected function isDayInSession() {
		
		$isDay = FALSE;
		
		// determine if the day is set in the session
		if($this->session['day'])
			$isDay = TRUE;
			
		return $isDay;	
	}
	
	/**
	 * Get Day
	 *
	 * @return string
	 */
	protected function getDay() {
	
		return $this->session['day'];
	}
	
	/**
	 * Get Day Array
	 *
	 * @return array
	 */
	protected function getDayArray() {
		
		$timestamp = strtotime($this->session['day']);
		
		$dayArray = array(
			'date'			=> $this->session['day'],
			'day'			=> date('d', $timestamp),
			'dayNoZero'		=> date('j', $timestamp),
			'month'			=> date('m', $timestamp),
			'year'			=> date('Y', $timestamp),
			'dayOfTheWeek'	=> date('w', $timestamp),
			'timestamp'		=> $timestamp,
		);
		
		return $dayArray;
	}
	
	/**
	 * Add Day Time To Session
	 *
	 * @param string $time
	 * @return void
	 */
	protected function addDayTimeToSession($time) {
		
		$this->session['time'] = $time;
		
		// save session data
		$this->saveSessionData();	
	}
	
	/**
	 * Is Day Time In Session
	 *
	 * @return boolean
	 */
	protected function isDayTimeInSession() {
		
		$isDayTime = FALSE;
		
		// determine if the day is set in the session
		if($this->session['time'])
			$isDayTime = TRUE;
			
		return $isDayTime;	
	}
	
	/**
	 * Get Day Time
	 *
	 * @return string
	 */
	protected function getDayTime() {
		
		return $this->session['time'];	
	}
	
	/**
	 * Add Client Data To Session
	 *
	 * @param array $clientData
	 * @return void
	 */
	protected function addClientDataToSession($clientData) {
		
		$this->session['clientData'] = $clientData;
		
		// save session data
		$this->saveSessionData();
	}
	
	/**
	 * Is Client Data In Session
	 *
	 * @return boolean
	 */
	protected function isClientDataInSession() {
		
		$isClientData = FALSE;
		
		// determine if the client data is set in the session
		if($this->session['clientData'])
			$isClientData = TRUE;
			
		return $isClientData;
	}
	
	/**
	 * Get Client Data
	 *
	 * @return array
	 */
	protected function getClientData() {
		
		return $this->session['clientData'];
	}

	/**
	 * Add Secret Hash In Session
	 *
	 * @param integer $secretHash
	 * @return void
	 */	
	protected function addSecretHashInSession($secretHash) {
		
		$this->session['secretHash'] = $secretHash;
		
		// save session data
		$this->saveSessionData();
	}
	
	/**
	 * Get Secret Hash
	 *
	 * @return integer
	 */
	protected function getSecretHash() {
		
		return $this->session['secretHash'];
	}
	
	/**
	 * Data
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	protected function data($key, $value) {
		
		$this->data[$key] = $value;	
	}
	
	/**
	 * Get Data
	 *
	 * @return array
	 */
	protected function getData() {
		
		// bind some general data which can be used in the template to get general things working
		$this->bindGeneralData();

		return $this->data;	
	}
	
	/**
	 * Bind General Data
	 *
	 * @return void
	 */
	protected function bindGeneralData() {
		
		/** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $TSFE */
		global $TSFE;
		
		// binds the baseurl
		$this->data('baseUrl', $TSFE->baseUrl);
		
		// binds the current url of the page
		$this->data('serverRequestUri', $_SERVER['REQUEST_URI']);
	}
	
	/**
	 * Get Current Step
	 *
	 * @return integer
	 */
	protected function getCurrentStep() {
		
		$currentStep = 1;
		// determine and validate the current step in the user session
		if($this->session['step'])
			$currentStep = $this->session['step'];
		
		return $currentStep;
	}
	
	/**
	 * Prepare Current Step
	 *
	 * @return void
	 */
	protected function prepareCurrentStep() {
		
		// current step function
		$currentStepFunction = 'step'.$this->getCurrentStep().'Action';
		
		// call the current step function
		$this->$currentStepFunction();
	}
	
	/**
	 * Prepare Next Step
	 *
	 * @return void
	 */
	protected function prepareNextStep() {
		
		// current validation function
		$validateFunction = 'validateStep'.$this->getCurrentStep().'Action';
		
		// call $validateFunction if exist
		if(method_exists($this, $validateFunction))
			$this->$validateFunction();
	}
	
	/**
	 * Prepare Calendar Mode
	 *
	 * @return void
	 */
	protected function prepareCalendarMode() {
		
		// calendar mode function
		$calendarModeFunction = $this->settings['calendar']['mode'].'ModeCalendarAction';
		
		// call $calendarModeFunction if exist
		if(method_exists($this, $calendarModeFunction))
			$this->$calendarModeFunction();		
	}
	
	/**
	 * Build Month Array
	 *
	 * @return array
	 */
	protected function buildMonthArray() {
		
		$monthArray = array();
		
		$i = 0;		
		// loop the amount of month numbers
		while($i < $this->settings['calendar']['range']) {
			
			// get current year and month
			if($i == 0):
				$year = date('Y');
				$month = date ('m');	
			else:
				if($month == 12):
					$year = $year + 1;
					$month = 1;
				else:
					$month = $month + 1;
				endif;
			endif;
			$month = str_pad($month, 2, 0, STR_PAD_LEFT);
			$monthArray[] = array(
				'year'	=> $year,
				'month'	=> $month,
				'key'	=> $year.$month,
			);
			$i++;
		}
		
		return $monthArray;
	}
	
	/**
	 * Render Available Days Array
	 *
	 * @param array $dates
	 * @return array
	 */
	protected function renderAvailableDaysArray($dates) {
		
		$newDatesArray = array();
		
		// if $dates is not a array make one of it
		if(!is_array($dates))
			$dates = array($dates);
		
		$i = 0;
		// foreach $dates
		foreach($dates as $date) {
			
			$timestamp = strtotime($date);
			
			$newDatesArray[$i] = array(
				'date'			=> $date,
				'day'			=> date('d', $timestamp),
				'dayNoZero'		=> date('j', $timestamp),
				'month'			=> date('m', $timestamp),
				'year'			=> date('Y', $timestamp),
				'dayOfTheWeek'	=> date('w', $timestamp),
				'timestamp'		=> $timestamp,
				'times'			=> NULL,
			);
			
			// inject times in available days
			if($this->settings['calendar']['default_injectTimesInAvailableDays']) {
				
				// get available times of a day
				$newDatesArray[$i]['times'] = $this->getAvailableTimesByDate($date);
			}
			
			$i++;
		}
		
		return $newDatesArray;
	}
	
	/**
	 * Get Available Times By Date (note: 
	 *
	 * @param string $date
	 * @return object
	 */
	protected function getAvailableTimesByDate($date) {
		
		$newTimesArray = array();
		
		$times = $this->api()->getGovAvailableTimesPerDay(array(
			'date'			=> $date,
			'locationID'	=> $this->getLocation(),
			'productID'		=> $this->getProductIdList(),
			'appDuration'	=> $this->getProductsDuration(),
		));
		
		// render times array
		if($times->times)
			$newTimesArray = $this->renderTimesArray($times->times);
		
		return $newTimesArray;
	}
	
	/**
	 * Render Times Array
	 *
	 * @param object times
	 * @return array
	 */
	protected function renderTimesArray($times) {
		
		$newTimesArray = array();
		
		if($times) {
			
			// handles $times as an array
			if(is_array($times)) {
						
				foreach($times as $time) {
					$newTimesArray[] = $this->convertDateCompoundFormatAsTimeString($time);
				}
				
			// there is just a single time availble
			} else {
				
				$newTimesArray[] = $this->convertDateCompoundFormatAsTimeString($times);
			}
		}
			
		return $newTimesArray;
	}
	
	/**
	 * Is Month Allowed
	 *
	 * @param string $key
	 * @param null/array $monthArray
	 * @return boolean
	 */
	protected function isMonthAllowed($key, $monthArray = NULL) {
		
		// if $monthArray is NULL build month array
		if(is_null($monthArray)) {
			
			// build month array
			$monthArray = $this->buildMonthArray();	
		}
		
		$allowed = FALSE;
		// lets loop trough the month array and check if the given month is accepted
		foreach($monthArray as $month) {
			
			// match the key of the month with the given argument
			if($month['key'] == $key) {
				$allowed = TRUE;
				break;
			}
		}
		
		return $allowed;
	}
	
	/**
	 * Is Date Allowed
	 *
	 * @param string $date
	 * @param array $daysArray
	 * @return void
	 */
	protected function isDateAllowed($date, $daysArray) {
	
		$allowed = FALSE;
		
		// lets loop trough the days array and check if the given date is accepted
		foreach($daysArray as $day) {
			
			// match the date with the given date
			if($day['date'] == $date) {
				$allowed = TRUE;
				break;	
			}
			
		}
		
		return $allowed;
	}
	
	/**
	 * Get Active Day By Date
	 *
	 * @param string $date
	 * @param array $daysArray
	 * @return array
	 */
	protected function getActiveDayByDate($date, $daysArray) {
		
		$activeDay = NULL;
		
		// lets loop trough the days array and check if the given date is accepted
		foreach($daysArray as $day) {
			
			// match the date with the given date
			if($day['date'] == $date) {
				$activeDay = $day;
				break;	
			}
			
		}
		
		return $activeDay;
	}
	
	/**
	 * Prepare Previous Step
	 *
	 * @return void
	 */
	protected function preparePreviousStep() {
		
		// get current step
		$currentStep = $this->getCurrentStep();	
		$previousStep = $currentStep - 1;
		
		// backward modification function
		$backwardModificationFunction = 'backwardModificationStep'.$previousStep.'Action';
		
		// call $backwardModificationFunction  if exist
		if(method_exists($this, $backwardModificationFunction))
			$this->$backwardModificationFunction();
	}
	
	/**
	 * Forward Step
	 *
	 * @return void
	 */
	protected function forwardStep() {
		
		// get current step
		$currentStep = $this->getCurrentStep();
		$nextStep = $currentStep;
		
		// validate if the next step can be processed
		if($currentStep + 1 <= $this->steps)
			$nextStep = $nextStep + 1;
		
		// turn on the location step if the current step is 1 and locations are disabled
		if($currentStep == 1 && !$this->isLocation()) {
			
			$nextStep = 3;
			$this->session['location'] = $this->settings['location']['locationID'];
		}
			
		$this->session['step'] = $nextStep;
		
		// save session data
		$this->saveSessionData();
	}
	
	/**
	 * Backward Step
	 *
	 * @return void
	 */
	protected function backwardStep() {
		
		// get current step
		$currentStep = $this->getCurrentStep();
		$previousStep = $currentStep;
		
		// validate if the previous step can be processed
		if($currentStep > 1)
			$previousStep = $previousStep - 1;
			
		// turn on the location step if the current step is 3 and locations are disabled
		if($currentStep == 3 && !$this->isLocation())
			$previousStep = 1;
			
		$this->session['step'] = $previousStep;
		
		// save session data
		$this->saveSessionData();
	}
	
	/**
	 * Get Progress
	 *
	 * @return array
	 */
	protected function getProgress() {
		
		$progress = array();
		$currentStep = $this->getCurrentStep();
		
		$i = 1;
		// generate progress array
		while($i <= $this->steps) {
			
			$progress[$i] = array(
				'step'		=> $i,
				'label'		=> \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('progress.step'. $i, $this->extensionName),
				'active'	=> FALSE,
				'completed' => FALSE,
			);
			
			if($currentStep > $i)
				$progress[$i]['completed'] = TRUE;
			
			$i++;
		}
		
		// set current step on active
		if($progress[$currentStep])
			$progress[$currentStep]['active'] = TRUE;
			
		// remove the location step when its disabled by settings 
		if(!$this->isLocation())
			unset($progress[2]);
		
		return $progress;
	}
	
	/**
	 * Is Location
	 *
	 * @return boolean
	 */
	protected function isLocation() {
		
		$isLocation = TRUE;
		
		// returns FALSE when the location step is disabled and the locationID is given
		if($this->settings['location']['disable'] && !empty($this->settings['location']['locationID']))
			$isLocation = FALSE;
		
		return $isLocation;	
	}
	
	/**
	 * Render Product Array
	 *
	 * @param array $products
	 * @return array
	 */
	protected function renderProductArray($products) {
		
		$productsArray = array();

		if($products) {

			// render a new product array
			foreach($products as $product) {
				
				$productsArray[] = array(
					'uid'	=> $product->productId,
					// 'code'	=> $product->productCode,
					'name'	=> $product->productDesc,
				);
			}
		}
		
		// if the settings 'enableDisplayByAllowed' is set, we should remove unallowed items from the array
		if($this->settings['products']['enableDisplayByAllowed']) {
			
			$allowed = GeneralUtility::trimExplode(',', $this->settings['products']['allowed'], TRUE);
			
			foreach($productsArray as $key => $value) {
			
				if(!in_array($value['uid'], $allowed))
					unset($productsArray[$key]);
			}
		}
		
		// remove excluded products
		$excluded = GeneralUtility::trimExplode(',', $this->settings['products']['excluded'], TRUE);
		
		if($excluded) {
			
			foreach($productsArray as $key => $value) {
			
				if(in_array($value['uid'], $excluded))
					unset($productsArray[$key]);
			}
		}
		
		// excluded multi select products array
		$excludedMultiSelectProducts = GeneralUtility::trimExplode(',', $this->settings['products_multiselect']['excluded'], TRUE);
		
		// check if multi select is enabled and if there are products to be excluded from the multiselect feature
		if($this->settings['products_multiselect']['enabled'] && $excludedMultiSelectProducts) {

			foreach($productsArray as $key => $value) {
			
				if(in_array($value['uid'], $excludedMultiSelectProducts))
					$productsArray[$key]['excludeFromMultiSelect'] = TRUE;
			}
		}
		
		return $productsArray;
	}
	
	/**
	 * Render Multi Select Items Array
	 *
	 * @return array
	 */
	public function renderMultiSelectItemsArray() {
		
		// fallback when the value of this setting is incorrect
		if(!ctype_digit($this->settings['products_multiselect']['maxAmount']) || $this->settings['products_multiselect']['maxAmount'] == 0)
			$this->settings['products_multiselect']['maxAmount'] = 4;
				
		$multiSelectItems = array();
		foreach(range(1, $this->settings['products_multiselect']['maxAmount']) as $item) {
			$multiSelectItems[$item] = $item;	
		}
		
		return $multiSelectItems;
	}
	
	/**
	 * Render Location Array
	 *
	 * @param object/array $locations
	 * @return array
	 */
	protected function renderLocationArray($locations) {
		
		$locationsArray = array();
		$newLocationArray = array();
		
		// single location
		if(!is_array($locations)):
			$locationsArray[] = $locations;
		// multiple locations
		else:
			$locationsArray = $locations;
		endif;
		
		$i = 0;
		// handle locations and render a new location array
		foreach($locationsArray as $location) {
			
			// API get location details
			$locationDetails = $this->api()->getGovLocationDetails(array('locationID' => $location->locationID));
			$locationDetails = $locationDetails->locaties;
			
			$newLocationArray[$i] = array(
				'uid'			=> $location->locationID,
				'name'			=> $location->locationDesc,
				'address'		=> $locationDetails->address,
				'postalcode'	=> $locationDetails->postalcode,
				'city'			=> $locationDetails->city,
				'phone'			=> $locationDetails->telephone,
				'openingHours'	=> NULL,
			);
			
			// check settings if we have to extend the location array with their opening hours
			if($this->settings['location']['renderOpeningHours']) {
				
				// render opening hours by location
				$newLocationArray[$i]['openingHours'] = $this->renderOpeningHoursByLocation($location);
			}
			
			$i++;
		}

		return $newLocationArray;
	}
	
	/**
	 * Render Location Details Array
	 *
	 * @param object $location
	 * @return void
	 */
	protected function renderLocationDetailsArray($location) {
		
		$newLocationArray = array(
			'name'			=> $location->locationDesc,
			'address'		=> $location->address,
			'postalcode'	=> $location->postalcode,
			'city'			=> $location->city,
			'phone'			=> $location->telephone,
		);
		
		return $newLocationArray;
	}
	
	/**
	 * Render Opening Hours By Locations
	 * 
	 * @param object $location
	 * @return array
	 */
	protected function renderOpeningHoursByLocation($location) {
		
		$openingHoursArray = array();
		
		if($location->locationOpeningHours) {
			
			// loop opening hours days
			foreach($location->locationOpeningHours as $openingHours) {

				$openingHoursArray[] = array(
					'day'	=> $openingHours->day,
					'times'	=> $this->renderTimesByOpeningHours($openingHours),
				);
			}
		}
		
		return $openingHoursArray;
	}
	
	/**
	 * Render Times By Opening Hours
	 *
	 * @param object $times
	 * @return void
	 */
	protected function renderTimesByOpeningHours($times) {
		
		$timesArray = array();
		
		// from time 1 / till time 1
		if(strpos($times->fromTime1, '0001-01-01T00:00:00.0000000') === FALSE) {
			
			$timesArray[] = array(
				'from'	=> $this->convertDateCompoundFormatAsTimeString($times->fromTime1),
				'till'	=> $this->convertDateCompoundFormatAsTimeString($times->tillTime1),
			);
		}
		
		// from time 2 / till time 2
		if(strpos($times->fromTime2, '0001-01-01T00:00:00.0000000') === FALSE) {
			
			$timesArray[] = array(
				'from'	=> $this->convertDateCompoundFormatAsTimeString($times->fromTime2),
				'till'	=> $this->convertDateCompoundFormatAsTimeString($times->tillTime2),
			);
		}
		
		// from time 3 / till time 3
		if(strpos($times->fromTime3, '0001-01-01T00:00:00.0000000') === FALSE) {
			
			$timesArray[] = array(
				'from'	=> $this->convertDateCompoundFormatAsTimeString($times->fromTime3),
				'till'	=> $this->convertDateCompoundFormatAsTimeString($times->tillTime3),
			);
		}
		
		return $timesArray;
	}
	
	/**
	 * Send Confirmation Mail
	 *
	 * @return void
	 */
	protected function sendConfirmationMail() {
		
		// sender
		$sender = array($this->settings['confirmation']['sender']['email'] => $this->settings['confirmation']['sender']['name']);
		
		// recipients
		$recipients = array($this->session['clientData']['mail'] => $this->session['clientData']['fullName']);
		
		// API get location details
		$location = $this->api()->getGovLocationDetails(array('locationID' => $this->getLocation()));
		
		// cancel link
		$cancel = FALSE;
		$cancelUrl = '';
		if($this->settings['general']['enableCancelling'] && $this->settings['general']['cancelPid']) {
			
			$cancel = TRUE;
			$cancelUrl = $this->getFrontendUri($this->settings['general']['cancelPid'], array('tx_jccappointments_pi2' => array('secretHash' => $this->getSecretHash())));
		}
		
		// variables
		$variables = array(
			'products'		=> $this->getProductsAndRenderRequisites(),
			'clientData'	=> $this->session['clientData'],	
			'location'		=> $this->renderLocationDetailsArray($location->locaties),
			'date'			=> $this->getDayArray(),
			'time'			=> $this->getDayTime(),
			'cancel'		=> $cancel,
			'cancelUrl'		=> $cancelUrl,
		);

		// send mail
		if(!$this->sendMail($sender, $recipients, $this->settings['confirmation']['subject'], $this->settings['confirmation']['templatePath'], $variables))
			throw new \Exception('The confirmation email could not be sent');
	}
	
	/**
	 * Convert Date Compound Format As Time String
	 *
	 * @param string $timeFormat
	 * @return string
	 */
	protected function convertDateCompoundFormatAsTimeString($timeFormat) {
		
		$date = new \DateTime($timeFormat);
		return $date->format('H:i');
	}
	
	/**
	 * Is Step Validated
	 *
	 * @return boolean
	 */
	protected function isStepValidated() {
		
		return $this->stepValidation;
	}
	
	/**
	 * Validate Date
	 *
	 * @param string $date
	 * @return boolean
	 */
	protected function validateDate($date) {
		
		$isDate = FALSE;
		
		if($date && strlen($date) == 10 && strtotime($date))
			$isDate = TRUE;
			
		return $isDate;
	}
	
	/**
	 * Validate Year
	 *
	 * @param string $year
	 * @return boolean
	 */
	protected function validateYear($year) {
		
		$isYear = FALSE;
		
		if($year && ctype_digit($year) && strlen($year) == 4)
			$isYear = TRUE;
			
		return $isYear;
	}
	
	/**
	 * Validate Month
	 *
	 * @param string $month
	 * @return boolean
	 */
	protected function validateMonth($month) {
		
		$isMonth = FALSE;
		
		if($month && ctype_digit($month) && strlen($month) == 2)
			$isMonth = TRUE;
			
		return $isMonth;
	}
	
	/**
	 * Validate Year Month
	 *
	 * @param string $yearMonth
	 * @return boolean
	 */
	protected function validateYearMonth($yearMonth) {
		
		$isYearMonth = FALSE;
		
		if($yearMonth && ctype_digit($yearMonth) && strlen($yearMonth) == 6)
			$isYearMonth = TRUE;
			
		return $isYearMonth;
	}
	
	/**
	 * Validate Time
	 *
	 * @param string $time
	 * @return void
	 */
	protected function validateTime($time) {
		
		$isTime = FALSE;
		
		if($time && strlen($time) == 5 && ctype_digit(str_ireplace(':', '', $time)))
			$isTime = TRUE;
		
		return $isTime;
	}
	
	/**
	 * Set Validation Error
	 *
	 * @param string $languageKey
	 * @return void
	 */
	protected function setValidationError($languageKey) {
		
		$this->stepValidation = FALSE;
		$this->flashMessageContainer->add(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($languageKey, $this->extensionName));
	}
	
	/**
	 * Is Validation
	 *
	 * @param string $field
	 * @param string $value
	 * @return boolean
	 */
	protected function isValidation($field, $value) {
		
		$valitedIt = FALSE;
		
		// field is required OR is not required and not empty
		if($this->settings['clientdata']['requirements'][$field] || (!$this->settings['clientdata']['requirements'][$field] && !empty($value)))
			$valitedIt = TRUE;
			
		return $valitedIt;
	}
	
	/**
	 * Page Redirect
	 *
	 * @param integer $pid
	 * @return void
	 */
	protected function pageRedirect($pid) {
		
	    $cObj = GeneralUtility::makeInstance('tslib_cObj');
	    $url = $cObj->typoLink_URL(array('parameter' => $pid));
	    \TYPO3\CMS\Core\Utility\HttpUtility::redirect($url);
		exit;
	}
	
	/**
	 * Clean Product Requisites
	 *
	 * @param string $html
	 * @return string
	 */
	protected function cleanProductRequisites($html) {
		
		$html = strip_tags($html);
		$html = str_ireplace(
			array(
				'&nbsp;',
			),
			array(
				' ',
			),
			$html
		);
		$html = nl2br(trim($html));
		
		return $html;
	}
	
	/**
	 * Make Secret Hash
	 *
	 * @param string $string
	 * @param integer $length
	 * @return string
	 */
	protected function makeSecretHash($string, $length = 14) {
		
		$hash = sha1(md5($string).time());
		if($length)
			$hash = substr($hash, 0, $length);
			
		return $hash;
	}
	
	/**
	 * Send Mail
	 *

	 * @param array $recipients
	 * @param array $sender
	 * @param string $subject
	 * @param string $templatePath
	 * @param array $variables
	 * @return boolean 
	 */
    protected function sendMail(array $sender, array $recipients, $subject, $templatePath, array $variables) {
		
		$emailView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
		$emailView->setFormat('html');
		$emailView->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($templatePath));
		$emailView->setLayoutRootPath($layoutRootPath);
		$emailView->setPartialRootPath($partialRootPath);
		$emailView->assignMultiple($variables);
		$emailBody = $emailView->render();
		$mail = GeneralUtility::makeInstance('TYPO3\CMS\Core\Mail\MailMessage');
		$mail->setFrom($sender)
			  ->setTo($recipients)
			  ->setSubject($subject)
			  ->setReturnPath($this->settings['email']['returnPath'])
			  ->setBody($emailBody);
		
		// Plain text example
		$mail->setBody($emailBody, 'text/plain');
		
		// HTML Email
		$mail->setBody($emailBody, 'text/html');
		
		$mail->send();
		
		return $mail->isSent();
    }
	
	/**
	 * Get Frontend Uri
	 *
	 * @param integer $pageUid
	 * @param array $additionalParams
	 * @return string
	 */
	protected function getFrontendUri($pageUid, array $additionalParams = array()) {
		
		/** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $TSFE */
		global $TSFE;
		
		// website baseurl
		$baseUrl = rtrim($TSFE->baseUrl, '/').'/';
		
		// get uri builder
		$uriBuilder = $this->controllerContext->getUriBuilder();

		$uri = $uriBuilder
		// set target page uid
		->setTargetPageUid($pageUid)
		// set use cache hash
		->setUseCacheHash(TRUE)
		// set arguments
		->setArguments($additionalParams)
		// build
		->build();
			
		return rawurldecode($baseUrl.ltrim($uri, '/'));
	}
	
	/**
	 * Persist All
	 *
	 * @return void
	 */
	protected function persistAll() {
		// initialize persistanceManager
		$persistenceManager = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\PersistenceManagerInterface');
		// persistAll
		$persistenceManager->persistAll();	
	}
}