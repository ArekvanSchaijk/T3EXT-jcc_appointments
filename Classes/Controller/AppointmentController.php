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
class Tx_JccAppointments_Controller_AppointmentController extends Tx_JccAppointments_Controller_BaseController {

	/**
	 * Form Action
	 *
	 * @return void
	 */
	public function formAction() {
		
		// prepare current step
		$this->prepareCurrentStep();

		// view allocations
		$this->view->assign('step', $this->getCurrentStep());
		$this->view->assign('data', $this->getData());
		$this->view->assign('progress', $this->getProgress());
	}
	
	/**
	 * Step 1 Action
	 *
	 * @return void
	 */
	public function step1Action() {
		
		// get all available products when no products are selected yet
		if(!$this->isProductsInSession()) {
			
			// API get available products
			$availableProducts = $this->api()->getGovAvailableProducts();
			
			// validate if we have products returned
			if($availableProducts->products && count($availableProducts->products) > 0):
				$availableProducts = $this->renderProductArray($availableProducts->products);
			else:
				$availableProducts = NULL;
			endif;
			
		// get available products by product
		} else {
			
			$firstAddedProduct = $this->getFirstProductFromSession();
			$availableProducts = $this->api()->getGovAvailableProductsByProduct(array('ProductNr' => $firstAddedProduct['uid']));
			
			// validate if we have products returned
			if($availableProducts->products && count($availableProducts->products) > 0):
				$availableProducts = $this->renderProductArray($availableProducts->products);
			else:
				$availableProducts = NULL;
			endif;
			
			$this->data('selectedProducts', $this->getSessionProducts());
		}

		$this->data('products', $availableProducts);
	}
	
	/**
	 * action add product
	 *
	 * @return void
	 */
	public function addProductAction() {
		
		// get arguments
		$arguments = $this->request->getArguments();

		// validate product ID 
		if(!$arguments['product'] || empty($arguments['product']) || !ctype_digit($arguments['product'])) {
			
			$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('validation.product_doesnt_exist', $this->extensionName));
			
		} else {
			
			// API get product details
			$product = $this->api()->getGovProductDetails(array('productID' => $arguments['product']));
			
			// check if the product exist
			if(!$product->out) {
				
				$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('validation.product_doesnt_exist', $this->extensionName));
				
			} else {
				
				// add product to session
				$this->addProductToSession($arguments['product'], $product->out);
			}
		}
		
		// redirect back to the form action
		$this->redirect(NULL);
	}
	
	/**
	 * action remove product
	 *
	 * @return void
	 */
	public function removeProductAction() {
		
		// get arguments
		$arguments = $this->request->getArguments();
		
		// remove product key from session
		if(ctype_digit($arguments['productKey']))
			$this->removeProductKeyFromSession($arguments['productKey']);

		// redirect back to the form action
		$this->redirect(NULL);
	}
	
	/**
	 * Step 2 Action
	 *
	 * @return void
	 */
	public function step2Action() {
		
		// API get location for products
		$locations = $this->api()->getGovLocationsForProduct(array('productID' => $this->getProductIdList()));
		$locations = $this->renderLocationArray($locations->location);
		
		$this->data('locations', $locations);
	}
	
	/**
	 * Choose Location Action
	 *
	 * @return void
	 */
	public function chooseLocationAction() {
		
		// get arguments
		$arguments = $this->request->getArguments();
		
		// validate given location
		if($arguments['location'] && !empty($arguments['location']) && ctype_digit($arguments['location'])) {
			
			// API get location details
			$location = $this->api()->getGovLocationDetails(array('locationID' => $arguments['location']));
			$location = $location->locaties;
			
			// check if the choosen location exist
			if($location->locationDesc) {
				
				$this->addLocationToSession($arguments['location']);
			}
		}
		
		// redirect to the next step action
		$this->redirect('nextStep');
	}
	
	/**
	 * Step 3 Action
	 *
	 * @return void
	 */
	public function step3Action() {
	
		// prepare calendar mode
		$this->prepareCalendarMode();
		
		$this->data('calendarMode', ucfirst($this->settings['calendar']['mode']));
	}
	
	/**
	 * Default Mode Calendar Action
	 *
	 * @return void
	 */
	public function defaultModeCalendarAction() {
		
		// get arguments
		$arguments = $this->request->getArguments();
		
		// build month array
		$months = $this->buildMonthArray();
		
		// if there is no month selected yet we have and the configuration "open first month" is set we have to redirect 
		if($this->settings['calendar']['default_openfirstmonth'] && (!$arguments['year'] || !$arguments['month'])) {

			$this->redirect('defaultModeCalendarSelectMonth', NULL, NULL, array('month' => date('Ym')));
		}
		
		if(
			// validate given year
			$this->validateYear($arguments['year'])
			// validate given month
			&& $this->validateMonth($arguments['month'])
		) {
			
			// if the month is allowed
			if(!$this->isMonthAllowed($arguments['year'].$arguments['month'], $months)) {
				
				$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('validation.calendar_invalid_month', $this->extensionName));
				
			} else {

				$this->data('showAvailableDays', true);
				$this->data('activeYear', $arguments['year']);
				$this->data('activeMonth', $arguments['month']);
				$this->data('activeMonthKey', $arguments['year'].$arguments['month']);

				// API get gov available days
				$availableDays = $this->api()->getGovAvailableDays(array(
					'locationID'	=> $this->getLocation(),
					'productID'		=> $this->getProductIdList(),
					'startDate'		=> $arguments['year'].'-'.$arguments['month'].'-01',
					'endDate'		=> $arguments['year'].'-'.$arguments['month'].'-'.cal_days_in_month(CAL_GREGORIAN, $arguments['month'], $arguments['year']),
					'appDuration'	=> $this->getProductsDuration(),
				));
				
				// if there are available days
				if($availableDays->dates) {
					
					$availableDays = $this->renderAvailableDaysArray($availableDays->dates);
					
					// render available days array
					$this->data('days', $availableDays);
					
					// if date is set we should serve the available times of that date
					if($arguments['date'] && $this->validateDate($arguments['date']) && $this->isDateAllowed($arguments['date'], $availableDays)) {
						
						$this->data('times', $this->getAvailableTimesByDate($arguments['date']));
						$this->data('showAvailableTimes', true);
						$this->data('activeDay', $this->getActiveDayByDate($arguments['date'], $availableDays));
					}
				}
			}
		}
		
		$this->data('months', $months);
		$this->data('year', date('Y'));
	}
	
	/**
	 * Default Mode Calendar Slecet Month Action
	 *
	 * @return void
	 */
	public function defaultModeCalendarSelectMonthAction() {
		
		$returnedArguments = NULL;
		
		// get arguments
		$arguments = $this->request->getArguments();
		
		// validate given year month
		if(!$this->validateYearMonth($arguments['month'])) {
			
			$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('validation.calendar_invalid_month', $this->extensionName));
		
		} else {
			
			// build month array
			$months = $this->buildMonthArray();
			
			// the selected month isnt accepted
			if(!$this->isMonthAllowed($arguments['month'], $months)) {
				
				$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('validation.calendar_invalid_month', $this->extensionName));
				
			} else {
			
				$postedMonth = str_split($arguments['month'], 4);
				
				$returnedArguments['year'] = $postedMonth[0];
				$returnedArguments['month'] = $postedMonth[1];
			}
		}
		
		$this->redirect(NULL, NULL, NULL, $returnedArguments);
	}
	
	/**
	 * Default Mode Calendar Select Day Action
	 *
	 * @return void
	 */
	protected function defaultModeCalendarSelectDayAction() {
		
		// get arguments
		$arguments = $this->request->getArguments();
		
		if(
			// validate given date
			$this->validateDate($arguments['date'])
			// validate given year
			&& $this->validateYear($arguments['year'])
			// validate given month
			&& $this->validateMonth($arguments['month'])
		) {
			
			$this->redirect(NULL, NULL, NULL, array('year' => $arguments['year'], 'month' => $arguments['month'], 'date' => $arguments['date']));
		
		} else {
			
			$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('validation.calendar_invalid_day', $this->extensionName));				
		}
		
		// reselect the current month/year when the arguments are given
		if(
			// validate given year
			$this->validateYear($arguments['year'])
			// validate given month
			&& $this->validateMonth($arguments['month'])
		) {
		
			$this->redirect('defaultModeCalendarSelectMonth', NULL, NULL, array('month' => $arguments['year'].$arguments['month']));
			
		} else {
		
			// redirect back to the form action
			$this->redirect(NULL);	
		}
	}
	
	/**
	 * Choose Date And Time Action
	 *
	 * @return void
	 */
	protected function chooseDateAndTimeAction() {

		// get arguments
		$arguments = $this->request->getArguments();
		
		// validate given date
		if(!$this->validateDate($arguments['date'])) {
			
			$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('validation.calendar_invalid_day', $this->extensionName));
			$this->redirect(NULL);
		
		// validate given time
		} else if(!$this->validateTime($arguments['time'])) {
			
			$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('validation.calendar_invalid_time', $this->extensionName));
			$this->redirect(NULL);
		
		} else {
			
			$dayAndTimeAllowed = false;
			
			// get available times by date
			$times = $this->getAvailableTimesByDate($arguments['date']);
			
			// walk trough the times array and 
			foreach($times as $time) {
				
				// match time with the given time
				if($time == $arguments['time']) {
					
					$dayAndTimeAllowed = true;
					break;
				}
			}
			
			// if day and time are not allwoed
			if(!$dayAndTimeAllowed) {
			
				$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('validation.calendar_day_and_time_not_available', $this->extensionName));
				$this->redirect(NULL);
				
			} else {
				
				$this->addDayToSession($arguments['date']);
				$this->addDayTimeToSession($arguments['time']);
				
				// redirect to the next step action
				$this->redirect('nextStep');
			}
		}
	}
	
	/**
	 * Step 4 Action
	 *
	 * @return void
	 */
	public function step4Action() {
		
		$this->data('splitAddress', $this->settings['clientdata']['splitAddress']);
		$this->data('enableCountry', $this->settings['clientdata']['enableCountry']);
		$this->data('smsNotifications', $this->settings['sms']['enabled']);
		$this->data('requirements', $this->settings['clientdata']['requirements']);
		
		// if is client data in session
		if($this->isClientDataInSession()) {
			
			// get client data
			$this->data('clientData', $this->getClientData());
		}
		
		$this->data('splitBirthday', $this->settings['clientdata']['splitBirthday']);
		
		// if the birthday is splitted
		if($this->settings['clientdata']['splitBirthday'] && $this->settings['clientdata']['renderBirthdayOptions']) {
			
			$endYear = date('Y');
			
			// if a end date is configured
			if($this->settings['clientdata']['birthdayEndYear'])
				$endYear = $this->settings['clientdata']['birthdayEndYear'];
				
			// list of months with leading zero
			$birthdayMonths = range(1, 12);
			foreach($birthdayMonths as $key => $value) {
				$birthdayMonths[$key] = str_pad($value, 2, 0, STR_PAD_LEFT);
			}
			
			$this->data('birthdayDays', range(1, 31));
			$this->data('birthdayMonths', $birthdayMonths);
			$this->data('birthdayYears', range($endYear, $this->settings['clientdata']['birthdayStartYear']));
		}
	}
	
	/**
	 * Post Client Data
	 *
	 * @return void
	 */
	public function postClientDataAction() {
		
		// get arguments
		$arguments = $this->request->getArguments();
		
		$clientData = array(
			'id'			=> $arguments['clientId'],
			'sex'			=> $arguments['clientSex'],
			'initials'		=> strtoupper($arguments['clientInitials']),
			'middleName'	=> $arguments['clientMiddleName'],
			'lastName'		=> $arguments['clientLastName'],
			'postalCode'	=> strtoupper(str_replace(' ', '', $arguments['clientPostalCode'])),
			'city'			=> $arguments['clientCity'],
			'tel'			=> $arguments['clientTel'],
			'mail'			=> $arguments['clientMail'],
		);
		
		// full name
		$clientData['fullName'] = '';
		if($arguments['clientInitials'] && !empty($arguments['clientInitials']))
			$clientData['fullName'] .= $arguments['clientInitials'];
			
		if($arguments['clientMiddleName'] && !empty($arguments['clientMiddleName']))
			$clientData['fullName'] .= ' '.$arguments['clientMiddleName'];
			
		if($arguments['clientLastName'] && !empty($arguments['clientLastName']))
			$clientData['fullName'] .= ' '.$arguments['clientLastName'];
		$clientData['fullName'] = trim($clientData['fullName']);
		
		// if split address
		if($this->settings['clientdata']['splitAddress']):
			$clientData['street'] = $arguments['clientStreet'];
			$clientData['number'] = $arguments['clientStreetNumber'];
			$clientData['address'] = trim($arguments['clientStreet'].' '.$arguments['clientStreetNumber']);
		else:
			$clientData['address'] = $arguments['clientAddress'];
		endif;
		
		// if split birthday
		if($this->settings['clientdata']['splitBirthday']) {
			$clientData['dayOfBirth'] = str_pad($arguments['clientDayOfBirth'], 2, 0, STR_PAD_LEFT);
			$clientData['monthOfBirth'] = str_pad($arguments['clientMonthOfBirth'], 2, 0, STR_PAD_LEFT);
			$clientData['yearOfBirth'] = $arguments['clientYearOfBirth'];
			$clientData['dateOfBirth'] = '';
			
			if($arguments['clientDayOfBirth'] && !empty($arguments['clientDayOfBirth'])):
				$clientData['dateOfBirth'] .= str_pad($arguments['clientDayOfBirth'], 2, 0, STR_PAD_LEFT);
			endif;
			if($arguments['clientMonthOfBirth'] && !empty($arguments['clientMonthOfBirth'])):
				$clientData['dateOfBirth'] .= '-'.str_pad($arguments['clientMonthOfBirth'], 2, 0, STR_PAD_LEFT);
			endif;
			if($arguments['clientYearOfBirth'] && !empty($arguments['clientYearOfBirth'])):
				$clientData['dateOfBirth'] .= '-'.$arguments['clientYearOfBirth'];
			endif;
		} else {
			$clientData['dateOfBirth'] = $arguments['clientDateOfBirth'];
		}
		
		// if country is enabled
		if($this->settings['clientdata']['enableCountry'])
			$clientData['country'] = $arguments['clientCountry'];
		
		// add client data to session
		$this->addClientDataToSession($clientData);
		
		// redirect to the next step action
		$this->redirect('nextStep');
	}
	
	/**
	 * Step 5 Action
	 *
	 * @return void
	 */
	public function step5Action() {
		
		$this->data('clientData', $this->getClientData());
		$this->data('products', $this->getProducts());
		$this->data('splitAddress', $this->settings['clientdata']['splitAddress']);
		$this->data('enableCountry', $this->settings['clientdata']['enableCountry']);
		
		// API get location details
		$location = $this->api()->getGovLocationDetails(array('locationID' => $this->getLocation()));
		$this->data('location', $this->renderLocationDetailsArray($location->locaties));
		
		$this->data('date', $this->getDayArray());
		$this->data('time', $this->getDayTime());
	}
	
	/**
	 * Confirm Appointment Action
	 *
	 * @return void
	 */
	public function confirmAppointmentAction() {
		
		// this action can only be called when the current step is 5
		if($this->getCurrentStep() != 5)
			throw new Exception('Access denied for this action');
			
		// get client data
		$clientData = $this->getClientData();
		
		$startAppointmentTime = strtotime($this->getDay().' '.$this->getDayTime());
		$endAppointmentTime = $this->getProductsDuration() * 60 + $startAppointmentTime;
		
		$bookData = array(
			'productID'			=> $this->getProductIdList(),
			'clientID'			=> $clientData['id'],
			'clientSex'			=> $clientData['sex'],
			'clientInitials'	=> $clientData['initials'],
			'clientDateOfBirth'	=> date('Y-m-d', strtotime($clientData['dateOfBirth'])),
			'clientPostalCode'	=> $clientData['postalCode'],
			'clientCity'		=> $clientData['city'],
			'clientTel'			=> $clientData['tel'],
			'clientMail'		=> $clientData['mail'],
			'locationID'		=> $this->getLocation(),
			'appStartTime'		=> date('c', $startAppointmentTime),
			'appEndTime'		=> date('c', $endAppointmentTime),
			'address'			=> $clientData['address'],
		);
		
		// client last name
		$bookData['clientLastname'] = '';
		if($clientData['middleName']) {
			$bookData['clientLastname'] .= $clientData['middleName'].' ';
		}
		$bookData['clientLastName'] .= $clientData['lastName'];
		
		// country
		if($this->settings['clientdata']['enableCountry'])
			$bookData['clientCountry'] = $clientData['country'];
		
		// API book appointment
		$appointment = $this->api()->bookGovAppointment(array('appDetail' => $bookData));
		
		// updatestatus : 0 = booking succesfull
		if($appointment->updateStatus == 0) {
			
			// remove user session
			$this->removeUserSession();
			
			// add appointment id in session
			$this->addAppointmentIdInSession($appointment->appID);
			
			// redirect to the succes pid
			if(!$this->settings['general']['successPid']) {
				
				throw new Exception('Misconfiguration: There is no successPid given');
				
			} else {
				
				$this->pageRedirect($this->settings['general']['successPid']);
			}
			
		} else {
			
			echo 'oopss<pre>';
			print_r($appointment);
			die();
		}
	}
	
	/**
	 * action validate step1
	 *
	 * @return void
	 */
	public function validateStep1Action() {
		
		// if there are no products in session
		if(!$this->isProductsInSession())
			$this->setValidationError('validation.no_product_selected');
	}
	
	/**
	 * action validate step2
	 *
	 * @return void
	 */
	public function validateStep2Action() {
		
		// if there are no products in session
		if(!$this->isLocationInSession())
			$this->setValidationError('validation.no_location_selected');
	}
	
	/**
	 * action validate step3
	 *
	 * @return void
	 */
	public function validateStep3Action() {
		
		// if there are no products in session
		if(!$this->isDayInSession() || !$this->isDayTimeInSession())
			$this->setValidationError('validation.no_day_or_time_selected');
	}
	
	/**
	 * action validate step4
	 *
	 * @return void
	 */
	public function validateStep4Action() {
		
		// if client data does not exists
		if(!$this->isClientDataInSession()) {

			$this->setValidationError('validation.clientdata.no_clientdata_given');
			
		} else {
			
			// get client data
			$clientData = $this->getClientData();
			
			// id
			if($this->isValidation('clientID', $clientData['id'])):
				if(!$clientData['id'] || empty($clientData['id'])):
					$this->setValidationError('validation.clientdata.no_id');
				else:
					if(strlen($clientData['id']) != 9 || !ctype_digit($clientData['id']))
						$this->setValidationError('validation.clientdata.invalid_id');
				endif;
			endif;
			
			// sex
			if($this->isValidation('clientSex', $clientData['sex'])):
				if(!$clientData['sex'] || empty($clientData['sex'])):
					$this->setValidationError('validation.clientdata.no_sex_checked');
				else:
					if($clientData['sex'] != 'M' && $clientData['sex'] != 'F')
						$this->setValidationError('validation.clientdata.invalid_sex');
				endif;
			endif;
			
			// initials
			if($this->isValidation('clientInitials', $clientData['initials'])):
				if(!$clientData['initials'] || empty($clientData['initials']))
					$this->setValidationError('validation.clientdata.no_initials');
			endif;
			
			// last name
			if($this->isValidation('clientLastName', $clientData['lastName'])):
				if(!$clientData['lastName'] || empty($clientData['lastName']))
					$this->setValidationError('validation.clientdata.no_lastName');
			endif;
			
			if($this->isValidation('clientDateOfBirth', $clientData['dateOfBirth'])):
			
				// splitted birthday
				if($this->settings['clientdata']['splitBirthday']) {
					
					if(empty($clientData['dayOfBirth']) || empty($clientData['monthOfBirth']) || empty($clientData['yearOfBirth'])):
						$this->setValidationError('validation.clientdata.no_birthday');	
					else:
						$birthdayTimestamp = strtotime($clientData['dateOfBirth']);
						if(
							// day of birth
							!ctype_digit($clientData['dayOfBirth']) || strlen($clientData['dayOfBirth']) > 2
							// month of birth
							|| !ctype_digit($clientData['monthOfBirth']) || strlen($clientData['monthOfBirth']) > 2
							// year of birth
							|| !ctype_digit($clientData['yearOfBirth']) || strlen($clientData['yearOfBirth']) > 4
							// year of birth start
							|| $clientData['yearOfBirth'] < $this->settings['clientdata']['birthdayStartYear']
							// year of birth end
							|| ($this->settings['clientdata']['birthdayEndYear'] && $clientData['yearOfBirth'] > $this->settings['clientdata']['birthdayEndYear'])
							// invalid timestamp
							|| !$birthdayTimestamp
							// validate strtotime mapping
							|| date('Y', $birthdayTimestamp) != $clientData['yearOfBirth']
							|| date('m', $birthdayTimestamp) != $clientData['monthOfBirth']
							|| date('d', $birthdayTimestamp) != $clientData['dayOfBirth']
						) {
							$this->setValidationError('validation.clientdata.invalid_birthday');
						}
					endif;
					
				// non splitted birthday
				} else {
					
					// is empty birthdate
					if(empty($clientData['dateOfBirth'])):
						$this->setValidationError('validation.clientdata.no_birthday');	
					else:
						// validate given birthdate string
						if(!strtotime($clientData['dateOfBirth']))
							$this->setValidationError('validation.clientdata.invalid_birthday');
					endif;
				}
			endif;

			if($this->isValidation('clientAddress', $clientData['address'])):

				// splitted address
				if($this->settings['clientdata']['splitAddress']) {
				
					// street (without number)
					if(!$clientData['street'] || empty($clientData['street']))
						$this->setValidationError('validation.clientdata.no_street');
					
					// number
					if(!$clientData['number'] || empty($clientData['number'])):
						$this->setValidationError('validation.clientdata.no_number');
					else:
						if(!preg_match('#[0-9]#', $clientData['number']))
							$this->setValidationError('validation.clientdata.invalid_number');
					endif;
					
				// non splitted address
				} else {
					
					// address
					if(!$clientData['address'] || empty($clientData['address'])):
						$this->setValidationError('validation.clientdata.no_address');
					else:
						if(strlen($clientData['address']) <= 4 || !preg_match('#[0-9]#', $clientData['address']))
							$this->setValidationError('validation.clientdata.invalid_address');
					endif;
				}
			endif;
			
			// postal code
			if($this->isValidation('clientPostalCode', $clientData['postalCode'])):
				if(!$clientData['postalCode'] && empty($clientData['postalCode'])):
					$this->setValidationError('validation.clientdata.no_postalcode');
				else:
					$postalcode = str_split($clientData['postalCode'], 4);
					if(
						strlen($clientData['postalCode']) != 6
						|| strlen($postalcode[0]) != 4
						|| strlen($postalcode[1]) != 2
						|| !ctype_digit($postalcode[0])
						|| !ctype_alpha($postalcode[1])
					) {
						$this->setValidationError('validation.clientdata.invalid_postalcode');
					}
				endif;
			endif;
			
			// city
			if($this->isValidation('clientCity', $clientData['city'])):
				if(!$clientData['city'] || empty($clientData['city']))
					$this->setValidationError('validation.clientdata.no_city');
			endif;
			
			// country
			if($this->isValidation('clientCountry', $clientData['country'])):
				if($this->settings['clientdata']['enableCountry'] && (!$clientData['country'] || empty($clientData['country'])))
					$this->setValidationError('validation.clientdata.no_country');
			endif;
			
			// phone
			if($this->isValidation('clientTel', $clientData['tel'])):
				if(!$clientData['tel'] || empty($clientData['tel']))
					$this->setValidationError('validation.clientdata.no_tel');
			endif;

			// email
			if($this->isValidation('clientMail', $clientData['mail'])):
				if(!$clientData['mail'] || empty($clientData['mail'])):
					$this->setValidationError('validation.clientdata.no_mail');
				else:
					if(!filter_var($clientData['mail'], FILTER_VALIDATE_EMAIL))
						$this->setValidationError('validation.clientdata.invalid_mail');
				endif;
			endif;
		}
	}
	
	/**
	 * Backward Modification Step1 Action
	 *
	 * @return void
	 */
	public function backwardModificationStep2Action() {
		
		unset($this->session['location']);
		
		// save session data
		$this->saveSessionData();
	}
	
	/**
	 * Backward Modification Step3 Action
	 *
	 * @return void
	 */
	public function backwardModificationStep3Action() {
		
		unset($this->session['day'], $this->session['time']);
		
		// save session data
		$this->saveSessionData();
	}
	
	/**
	 * action next step
	 *
	 * @return void
	 */
	public function nextStepAction() {
		
		// prepare next step
		$this->prepareNextStep();
		
		// is step validated
		if($this->isStepValidated())
			$this->forwardStep();
		
		// redirect back to the form action
		$this->redirect(NULL);
	}
	
	/**
	 * action previous step
	 *
	 * @return void
	 */
	public function previousStepAction() {
	
		// prepare previous step
		$this->preparePreviousStep();
		
		// backward step
		$this->backwardStep();
		
		// redirect back to the form action
		$this->redirect(NULL);
	}
}
?>