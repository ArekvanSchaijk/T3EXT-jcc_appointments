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
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\JccAppointments\Utility\TemplateUtility;
use TYPO3\JccAppointments\Exception;

/**
 * BaseController
 */
class BaseController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	
	/**
	 * @const integer
	 */
	const	CANCEL_NO_SECRETHASH_GIVEN	= 1,
			CANCEL_INVALID_SECRETHASH	= 2,
			CANCEL_APPOINTMENT_EXPIRED	= 3,
			CANCEL_ALREADY_CLOSED		= 4;
	
	/**
	 * @var SoapClient
	 */ 
	protected $api;
	
	/**
	 * @var array
	 */
	protected $session;
	
	/**
	 * @var array
	 */
	protected $data = array();
	
	/**
	 * @var string
	 */
	static protected $extKey = 'tx_jccappointments';
	
	/**
	 * @var string
	 */
	static protected $extName = 'JccAppointments';
	
	/**
	 * @var integer
	 */
	protected $steps = 5;
	
	/**
	 * @var boolean
	 */
	protected $stepValidation = TRUE;
	
	/**
	 * @var array
	 */
	protected $params;
	
	/**
	 * @var array
	 */
	protected $takeoutTexts = NULL;
	
	/**
	 * @var TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
	 * @inject
	 */
	protected $persistenceManager = NULL;
	
	/**
	 * Takeout Text Repository
	 *
	 * @var \TYPO3\JccAppointments\Domain\Repository\TakeoutTextRepository
	 * @inject
	 */
	protected $takeoutTextRepository = NULL;
	
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
		if ($this->isUserSessionExpired()) {
			
			// removes the current session
			$this->removeUserSession();
			
			// adds a flash message
			if ($this->settings['general']['sessionExpiredMessage']) {		
				$this->addFrontendFlashMessage('session.expired.message');
			}
				
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
		// checks if the setting is set
		if ($this->settings['general']['sessionLifetime']
				// checks if the timestamp is available
				&& $this->session['timestamp']
					// calculated if the session is expired
					&& time() - $this->session['timestamp'] > $this->settings['general']['sessionLifetime']
		) {
			return TRUE;
		}	
			
		return FALSE;
	}

	/**
	 * Api
	 *
	 * @return SoapClient $api
	 */
	protected function api() {
		
		// initialize SoapClient if not loaded yet
		if (!$this->api) {
			
			try {
			
				$this->api = new \SoapClient($this->settings['soap']['wsdl']);

			} catch(SoapFault $e) {
				// if the "service unavailable" page id is set we have to forward trough it
				if ($this->settings['soap']['serviceUnavailablePid']) {
					self::pageRedirect($this->settings['soap']['serviceUnavailablePid']);
				} else {
					throw new Exception('The SOAP service is currently unavailable');
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
		
		/**
		 * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
		 */
		global $TSFE;
		
		return $TSFE->fe_user->getKey('ses', self::$extKey);
	}
	
	/**
	 * Set User Data
	 *
	 * @param array $data
	 * @return void
	 */
	protected function setUserSession($data) {
		
		/**
		 * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
		 */
		global $TSFE;
		
		// sets the timestamp (calculated the age of the session)
		if (!is_null($data)) {
			$data['timestamp'] = time();
		}
		
		$this->session = $data;
		
		$TSFE->fe_user->setKey('ses', self::$extKey, $data);
		$TSFE->fe_user->storeSessionData();
	}
	
	/**
	 * Remove User Session
	 *
	 * @return void
	 */
	protected function removeUserSession() {
		$this->session = NULL;
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
		if (!$this->session['products']) {
			$this->session['products'] = array();
		}
			
		// add product
		$this->session['products'][] = self::renderProductDetailArray($productId, $product);

		// save session data
		$this->saveSessionData();
	}
	
	/**
	 * Check Products That Cant Be Selected More Than Once
	 *
	 * @param integer $productId
	 * @return boolean
	 */
	protected function checkProductsThatCantBeSelectedMoreThanOnce($productId) {
		
		$canBeChosen = TRUE;
		
		// get products that can't be selected more than once
		if ($singleSelection = GeneralUtility::trimExplode(',', $this->settings['products']['singleSelection'], TRUE)) {
			
			// get product list from the current session
			$productsInSession = explode(',', $this->getProductIdList());
			
			// check if the product id is marked as product that cannot be selected more than once and already exists in the current session
			if (in_array($productId, $singleSelection) && in_array($productId, $productsInSession)) {
				$canBeChosen = FALSE;
			}
		}
		
		return $canBeChosen;
	}
	
	/**
	 * Render Product Detail Array
	 *
	 * @param integer $productId
	 * @param object $product
	 * @return array
	 */
	static protected function renderProductDetailArray($productId, $product, $encodeRequisites = FALSE) {

		$productDetails = array(
			'uid'			=> $productId,
			'name'			=> $product->description,
			'duration'		=> $product->appDuration,
			'requisites'	=> $product->requisites,
		);
		
		if ($encodeRequisites) {
			$productDetails['requisites'] = self::cleanProductRequisites(base64_decode($productDetails['requisites']));
		}
		
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
		foreach ($this->session['products'] as $product) {
			$product['requisites'] = self::cleanProductRequisites(base64_decode($product['requisites']));
			$productArray[] = $product;
		}
		
		return $productArray;
	}
	
	/**
	 * Get Takeout Texts
	 *
	 * @return array
	 */
	protected function getTakeoutTexts() {
		// lazy loading
		if (is_null($this->takeoutTexts)) {
			$this->takeoutTexts = array();
			// use 'takeout text' object
			if ($this->settings['products']['takeoutText']['useTypo3Object']) {
				$takeoutTextArray = array();
				$takeoutTexts = $this->takeoutTextRepository->findAll()->toArray();
				if ($takeoutTexts) {
					foreach ($takeoutTexts as $takeoutText) {
						$productId = $takeoutText->getProductId();
						if ($productId) {
							$takeoutTextArray[$productId] = $takeoutText->getText();	
						}
					}
				}
				foreach ($this->session['products'] as $product) {
					if (!isset($this->takeoutTexts[$product['uid']]) && $takeoutTextArray[$product['uid']]) {
						$product['requisites'] = $takeoutTextArray[$product['uid']];
						$this->takeoutTexts[$product['uid']] = $product;
					}
				}
			// use jcc requisites
			} else {
				foreach ($this->session['products'] as $product) {
					if (!isset($this->takeoutTexts[$product['uid']])) {
						$product['requisites'] = self::cleanProductRequisites(base64_decode($product['requisites']));
						if ($product['requisites']) {
							$this->takeoutTexts[$product['uid']] = $product;	
						}
					}
				}
			}
		}
		return $this->takeoutTexts;
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
		if ($this->session['products'] && count($this->session['products']) > 0) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Get Product Id List
	 *
	 * @return string
	 */
	protected function getProductIdList() {
		
		$productList = '';
		
		// foreach products
		foreach ($this->session['products'] as $product) {
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
        foreach ($this->session['products'] as $product) {

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
		$this->saveSessionData();
	}
	
	/**
	 * Is Location In Session
	 *
	 * @return boolean
	 */
	protected function isLocationInSession() {
		if ($this->session['location']) {
			return TRUE;
		}
		return FALSE;
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
		$this->saveSessionData();
	}
	
	/**
	 * Is Day In Session
	 *
	 * @return boolean
	 */
	protected function isDayInSession() {
		if ($this->session['day']) {
			return TRUE;
		}	
		return FALSE;	
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
		$durationInSeconds = $this->getProductsDuration() * 60;
		$this->session['endTime'] = date('H:i', strtotime($time) + $durationInSeconds);
		$this->saveSessionData();
	}

	/**
	 * Is Day Time In Session
	 *
	 * @return boolean
	 */
	protected function isDayTimeInSession() {
		if ($this->session['time']) {
			return TRUE;
		}
		return FALSE;	
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
	 * Get Day End Time
	 *
	 * @return string
	 */
	protected function getDayEndTime() {
		return $this->session['endTime'];	
	}
	
	/**
	 * Add Client Data To Session
	 *
	 * @param array $clientData
	 * @return void
	 */
	protected function addClientDataToSession($clientData) {
		$this->session['clientData'] = $clientData;
		$this->saveSessionData();
	}
	
	/**
	 * Is Client Data In Session
	 *
	 * @return boolean
	 */
	protected function isClientDataInSession() {	
		if ($this->session['clientData']) {
			return TRUE;	
		}
		return FALSE;
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
		$this->bindGeneralData();
		return $this->data;	
	}
	
	/**
	 * Bind General Data
	 *
	 * @return void
	 */
	protected function bindGeneralData() {
		
		/**
		 * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
		 */
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
		return ($this->session['step'] ? $this->session['step'] : 1);
	}
	
	/**
	 * Prepare Current Step
	 *
	 * @return void
	 */
	protected function prepareCurrentStep() {
		$currentStepFunction = 'step'.$this->getCurrentStep().'Action';
		$this->$currentStepFunction();
	}
	
	/**
	 * Prepare Next Step
	 *
	 * @return void
	 */
	protected function prepareNextStep() {
		$validateFunction = 'validateStep'.$this->getCurrentStep().'Action';
		if (method_exists($this, $validateFunction)) {
			$this->$validateFunction();
		}
	}
	
	/**
	 * Prepare Calendar Mode
	 *
	 * @return void
	 */
	protected function prepareCalendarMode() {
		$calendarModeFunction = $this->settings['calendar']['mode'].'ModeCalendarAction';
		if (method_exists($this, $calendarModeFunction)) {
			$this->$calendarModeFunction();
		}
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
		while ($i < $this->settings['calendar']['range']) {
			
			// get current year and month
			if ($i == 0) {
				$year = date('Y');
				$month = date ('m');	
			} else {
				if ($month == 12) {
					$year = $year + 1;
					$month = 1;
				} else {
					$month = $month + 1;
				}
			}
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
		if (!is_array($dates))
			$dates = array($dates);
		
		$i = 0;
		// foreach $dates
		foreach ($dates as $date) {
			
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
			if ($this->settings['calendar']['default_injectTimesInAvailableDays']) {
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
		if ($times->times) {
			$newTimesArray = self::renderTimesArray($times->times);
		}
		
		return $newTimesArray;
	}
	
	/**
	 * Render Times Array
	 *
	 * @param object times
	 * @return array
	 */
	static protected function renderTimesArray($times) {
		
		$newTimesArray = array();
		
		if ($times) {
			
			// handles $times as an array
			if (is_array($times)) {
						
				foreach ($times as $time) {
					$newTimesArray[] = self::convertDateCompoundFormatAsTimeString($time);
				}

			// there is just a single time availble
			} else {
				$newTimesArray[] = self::convertDateCompoundFormatAsTimeString($times);
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
		
		if (is_null($monthArray)) {
			$monthArray = $this->buildMonthArray();	
		}
		
		foreach ($monthArray as $month) {
			if ($month['key'] == $key) {
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Is Date Allowed
	 *
	 * @param string $date
	 * @param array $daysArray
	 * @return void
	 */
	static protected function isDateAllowed($date, $daysArray) {
		foreach ($daysArray as $day) {
			if ($day['date'] == $date) {
				return TRUE;
			}			
		}		
		return FALSE;
	}
	
	/**
	 * Get Active Day By Date
	 *
	 * @param string $date
	 * @param array $daysArray
	 * @return array
	 */
	static protected function getActiveDayByDate($date, $daysArray) {
		
		$activeDay = NULL;
		
		// lets loop trough the days array and check if the given date is accepted
		foreach ($daysArray as $day) {
			
			// match the date with the given date
			if ($day['date'] == $date) {
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
		if (method_exists($this, $backwardModificationFunction)) {
			$this->$backwardModificationFunction();
		}
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
		if ($currentStep + 1 <= $this->steps) {
			$nextStep = $nextStep + 1;
		}
		
		// turn on the location step if the current step is 1 and locations are disabled
		if ($currentStep == 1 && !$this->isLocation()) {
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
		if ($currentStep > 1) {
			$previousStep = $previousStep - 1;
		}
		
		// turn on the location step if the current step is 3 and locations are disabled
		if ($currentStep == 3 && !$this->isLocation()) {
			$previousStep = 1;
		}
			
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
		while ($i <= $this->steps) {
			
			$progress[$i] = array(
				'step'		=> $i,
				'label'		=> LocalizationUtility::translate('progress.step'. $i, self::$extName),
				'active'	=> FALSE,
				'completed' => FALSE,
			);
			
			if ($currentStep > $i) {
				$progress[$i]['completed'] = TRUE;
			}
			$i++;
		}
		
		// set current step on active
		if ($progress[$currentStep]) {
			$progress[$currentStep]['active'] = TRUE;
		}
		
		// remove the location step when its disabled by settings 
		if (!$this->isLocation()) {
			unset($progress[2]);
		}
		
		return $progress;
	}
	
	/**
	 * Is Location
	 *
	 * @return boolean
	 */
	protected function isLocation() {
		if ($this->settings['location']['disable'] && !empty($this->settings['location']['locationID'])) {
			return FALSE;
		}
		return TRUE;	
	}
	
	/**
	 * Render Product Array
	 *
	 * @param array $products
	 * @return array
	 */
	protected function renderProductArray($products) {
		
		$productsArray = array();

		if ($products) {

			// render a new product array
			foreach ($products as $product) {
				
				$productsArray[] = array(
					'uid'	=> $product->productId,
					// 'code'	=> $product->productCode,
					'name'	=> $product->productDesc,
				);
			}
		}
		
		// if the settings 'enableDisplayByAllowed' is set, we should remove unallowed items from the array
		if ($this->settings['products']['enableDisplayByAllowed']) {
			
			$allowed = GeneralUtility::trimExplode(',', $this->settings['products']['allowed'], TRUE);
			
			foreach ($productsArray as $key => $value) {
			
				if (!in_array($value['uid'], $allowed)) {
					unset($productsArray[$key]);
				}
			}
		}
		
		// remove excluded products
		$excluded = GeneralUtility::trimExplode(',', $this->settings['products']['excluded'], TRUE);
		
		if ($excluded) {
			
			foreach ($productsArray as $key => $value) {
			
				if (in_array($value['uid'], $excluded)) {
					unset($productsArray[$key]);
				}
			}
		}
		
		// excluded multi select products array
		$excludedMultiSelectProducts = GeneralUtility::trimExplode(',', $this->settings['products_multiselect']['excluded'], TRUE);
		
		// check if multi select is enabled and if there are products to be excluded from the multiselect feature
		if ($this->settings['products_multiselect']['enabled'] && $excludedMultiSelectProducts) {

			foreach ($productsArray as $key => $value) {
			
				if (in_array($value['uid'], $excludedMultiSelectProducts)) {
					$productsArray[$key]['excludeFromMultiSelect'] = TRUE;
				}
			}
		}
		
		return $productsArray;
	}
	
	/**
	 * Render Multi Select Items Array
	 *
	 * @return array
	 */
	protected function renderMultiSelectItemsArray() {
		
		// fallback when the value of this setting is incorrect
		if (!ctype_digit($this->settings['products_multiselect']['maxAmount']) || $this->settings['products_multiselect']['maxAmount'] == 0) {
			$this->settings['products_multiselect']['maxAmount'] = 4;
		}
		
		$multiSelectItems = array();
		foreach (range(1, $this->settings['products_multiselect']['maxAmount']) as $item) {
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
		if (!is_array($locations)) {
			$locationsArray[] = $locations;
		// multiple locations
		} else {
			$locationsArray = $locations;
		}
		
		$i = 0;
		// handle locations and render a new location array
		foreach ($locationsArray as $location) {
			
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
			if ($this->settings['location']['renderOpeningHours']) {
				$newLocationArray[$i]['openingHours'] = self::renderOpeningHoursByLocation($location);
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
	static protected function renderLocationDetailsArray($location) {
		
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
	static protected function renderOpeningHoursByLocation($location) {
		
		$openingHoursArray = array();
		
		if ($location->locationOpeningHours) {
			
			// loop opening hours days
			foreach ($location->locationOpeningHours as $openingHours) {

				$openingHoursArray[] = array(
					'day'	=> $openingHours->day,
					'times'	=> self::renderTimesByOpeningHours($openingHours),
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
	static protected function renderTimesByOpeningHours($times) {
		
		$timesArray = array();
		
		// from time 1 / till time 1
		if (strpos($times->fromTime1, '0001-01-01T00:00:00.0000000') === FALSE) {
			
			$timesArray[] = array(
				'from'	=> self::convertDateCompoundFormatAsTimeString($times->fromTime1),
				'till'	=> self::convertDateCompoundFormatAsTimeString($times->tillTime1),
			);
		}
		
		// from time 2 / till time 2
		if (strpos($times->fromTime2, '0001-01-01T00:00:00.0000000') === FALSE) {
			
			$timesArray[] = array(
				'from'	=> self::convertDateCompoundFormatAsTimeString($times->fromTime2),
				'till'	=> self::convertDateCompoundFormatAsTimeString($times->tillTime2),
			);
		}
		
		// from time 3 / till time 3
		if (strpos($times->fromTime3, '0001-01-01T00:00:00.0000000') === FALSE) {
			
			$timesArray[] = array(
				'from'	=> self::convertDateCompoundFormatAsTimeString($times->fromTime3),
				'till'	=> self::convertDateCompoundFormatAsTimeString($times->tillTime3),
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
		
		if ($this->settings['general']['enableCancelling'] && $this->settings['general']['cancelPid']) {
			$cancel = TRUE;
			$cancelUrl = $this->getFrontendUri($this->settings['general']['cancelPid'], array('tx_jccappointments_pi2' => array('secretHash' => $this->getSecretHash())));
		}
		
		// variables
		$variables = array(
			'products'		=> $this->getProductsAndRenderRequisites(),
			'takeoutTexts'	=> $this->getTakeoutTexts(),
			'clientData'	=> $this->session['clientData'],	
			'location'		=> self::renderLocationDetailsArray($location->locaties),
			'date'			=> $this->getDayArray(),
			'time'			=> $this->getDayTime(),
			'endTime'		=> $this->getDayEndTime(),
			'cancel'		=> $cancel,
			'cancelUrl'		=> $cancelUrl,
		);
		
		// subject
		$subject = $this->settings['confirmation']['subject'];
		if ($this->settings['confirmation']['useFluidTemplateSubject']) {
			$subject = TemplateUtility::render(
				array(
					TemplateUtility::CONFIG_TEMPLATEFILEPATH => $this->settings['confirmation']['subjectTemplatePath'],
				),
				$variables
			);
			$subject = trim($subject);
		}
		
		// send mail
		if (!$this->sendMail($sender, $recipients, $subject, $this->settings['confirmation']['templatePath'], $variables)) {
			throw new Exception('The confirmation email could not be sent');
		}
	}
	
	/**
	 * Send Cancelled ConfirmationMail
	 *
	 * @param \TYPO3\JccAppointments\Domain\Model\Appointment $appointment
	 * @return void
	 */
	protected function sendCancelledConfirmationMail(\TYPO3\JccAppointments\Domain\Model\Appointment $appointment) {
		
		// sender
		$sender = array($this->settings['confirmation']['sender']['email'] => $this->settings['confirmation']['sender']['name']);
		
		// client full name
		$clientFullName = $appointment->setClientInitials();
		$clientFullName .= ($appointment->getClientInsertions() ? ' '.$appointment->getClientInsertions() : NULL);
		$clientFullName .= ($appointment->getClientLastName() ? ' '.$appointment->getClientLastName() : NULL);
		$clientFullName = trim($clientFullName);
		
		// recipients
		$recipients = array($appointment->getClientEmail() => $clientFullName);
		
		// variables
		$variables = array(
			'appointment' => $appointment,
		);
		
		// subject
		$subject = $this->settings['confirmation']['cancellation']['subject'];
		if ($this->settings['confirmation']['cancellation']['useFluidTemplateSubject']) {
			$subject = TemplateUtility::render(
				array(
					TemplateUtility::CONFIG_TEMPLATEFILEPATH => $this->settings['confirmation']['cancellation']['subjectTemplatePath'],
				),
				$variables
			);
			$subject = trim($subject);
		}
		
		// send mail
		if (!$this->sendMail($sender, $recipients, $subject, $this->settings['confirmation']['cancellation']['templatePath'], $variables)) {
			throw new Exception('The cancellation confirmation email could not be sent');
		}
	}
	
	/**
	 * Convert Date Compound Format As Time String
	 *
	 * @param string $timeFormat
	 * @return string
	 */
	static protected function convertDateCompoundFormatAsTimeString($timeFormat) {
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
	static protected function validateDate($date) {
		if ($date && strlen($date) == 10 && strtotime($date)) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Validate Year
	 *
	 * @param string $year
	 * @return boolean
	 */
	static protected function validateYear($year) {
		if ($year && ctype_digit($year) && strlen($year) == 4) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Validate Month
	 *
	 * @param string $month
	 * @return boolean
	 */
	static protected function validateMonth($month) {
		if ($month && ctype_digit($month) && strlen($month) == 2) {
			return TRUE;	
		}
		return FALSE;
	}
	
	/**
	 * Validate Year Month
	 *
	 * @param string $yearMonth
	 * @return boolean
	 */
	static protected function validateYearMonth($yearMonth) {
		if ($yearMonth && ctype_digit($yearMonth) && strlen($yearMonth) == 6) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Validate Time
	 *
	 * @param string $time
	 * @return void
	 */
	static protected function validateTime($time) {
		if ($time && strlen($time) == 5 && ctype_digit(str_ireplace(':', '', $time))) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Set Validation Error
	 *
	 * @param string $languageKey
	 * @return void
	 */
	protected function setValidationError($languageKey) {
		$this->stepValidation = FALSE;
		$this->flashMessageContainer->add(LocalizationUtility::translate($languageKey, self::$extName));
	}
	
	/**
	 * Is Validation
	 *
	 * @param string $field
	 * @param string $value
	 * @return boolean
	 */
	protected function isValidation($field, $value) {
		if ($this->settings['clientdata']['requirements'][$field] || (!$this->settings['clientdata']['requirements'][$field] && !empty($value))) {
			return TRUE;
		}			
		return FALSE;
	}
	
	/**
	 * Page Redirect
	 *
	 * @param integer $pid
	 * @return void
	 */
	static protected function pageRedirect($pid) {
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
	static protected function cleanProductRequisites($html) {
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
	static protected function makeSecretHash($string, $length = 14) {
		$hash = sha1(md5($string).time());
		if ($length)
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
		
		// template utility
		$body = TemplateUtility::render(
			array(
				TemplateUtility::CONFIG_TEMPLATEFILEPATH => $templatePath,
			),
			$variables
		);
		
		$mail = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
		$mail->setFrom($sender)
			  ->setTo($recipients)
			  ->setSubject($subject)
			  ->setBody($emailBody);
		
		// Plain text example
		$mail->setBody($body, 'text/plain');
		
		// HTML Email
		$mail->setBody($body, 'text/html');
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
		
		/**
		 * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
		 */
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
	 * Add Frontend Flash Message
	 *
	 * @param string $translationLabel
	 * @return void
	 */
	public function addFrontendFlashMessage($translationLabel) {
		$this->flashMessageContainer->add(LocalizationUtility::translate($translationLabel, self::$extName));
	}
	
}