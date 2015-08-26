<?php
namespace Ucreation\JccAppointments\Handler;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Arek van Schaijk <info@ucreation.nl>, Ucreation
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
 * HookHandler
 */
class HookHandler {

	/**
	 * @var string
	 */
	static protected $extKey = 'JccAppointments';
	
	/**
	 * @var SoapClient
	 */ 
	protected $api;
	
	/**
	 * @var array
	 */
	protected $settings = NULL;
	
	/**
	 * Set Settings
	 *
	 * @return void
	 */
	protected function setSettings() {
		if (is_null($this->settings)) {
			// make an instance of the object manager
			$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
			// gets the configuration manager interface
			$configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
			// get jcc_appointments configuration
			$configuration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK, 'JccAppointments');
			// sets the settings
			$this->settings = $configuration['settings'];	
		}
	}
	
	/**
	 * Get Api
	 *
	 * @return void
	 */
	protected function api() {
		
		// initialize SoapClient if not loaded yet
		if (!$this->api) {
			
			try {
			
				$this->api = new \SoapClient($this->settings['soap']['wsdl']);

			} catch(SoapFault $e) {
				
				return FALSE;
			}
		}
		
		return $this->api;
	}
	
	/**
	 * Get Prodcuts Array
	 *
	 * @return array
	 */
	protected function getProductsArray() {
		$productsArray = array();
		$this->setSettings();
		if (isset($this->settings['soap']['wsdl']) && $this->api()) {
			$products = $this->api()->getGovAvailableProducts();
			if ($products->products && count($products->products) > 0) {
				foreach ($products->products as $product) {
					$productsArray[(int)$product->productId] = (string)$product->productDesc;
				}
			}
		}
		return $productsArray;
	}
	
	/**
	 * Add Tca Product Selection
	 *
	 * @return array
	 */
	public function addTcaProductSelection(array $config) {
		$products = $this->getProductsArray();
		if ($products) {
			foreach ($products as $productId => $productName) {
				$config['items'][] = array($productName, $productId);
			}
		}
		return $config;
	}
	
	/**
	 * TCA Product Name
	 *
	 * @param array $parameters
	 * @return array
	 */
	public function tcaProductName(&$parameters) {

		global $TYPO3_CONF_VARS;
		
		$productId = $parameters['row']['product_id'];
		
		if (ctype_digit($productId) && $productId > 0) {
			if (!$TYPO3_CONF_VARS['EXTCONF']['jcc_appointments']['productStorage'] 
					|| !is_array($TYPO3_CONF_VARS['EXTCONF']['jcc_appointments']['productStorage'])
			) {
				$TYPO3_CONF_VARS['EXTCONF']['jcc_appointments']['productStorage'] = $this->getProductsArray();
			}
	
			if (isset($TYPO3_CONF_VARS['EXTCONF']['jcc_appointments']['productStorage'][$productId])) {
				$parameters['title'] = $TYPO3_CONF_VARS['EXTCONF']['jcc_appointments']['productStorage'][$productId];		
			} else {
				$parameters['title'] = 'Textout for product id: '.$productId;
			}
		} else {
			$parameters['title'] = '-- no product selected --';	
		}
	}
	
}