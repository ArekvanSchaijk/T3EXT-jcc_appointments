<?php
namespace Ucreation\JccAppointments\Utility;

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

use Ucreation\JccAppointments\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class TemplateUtility
 *
 * @package Ucreation\JccAppointments
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class TemplateUtility {
	
	/**
	 * @const string
	 */
	const	CONFIG_TEMPLATEFILEPATH 	= 'templateFilePath',
			CONFIG_PARTIALROOTPATH		= 'partialRootPath',
			CONFIG_LAYOUTROOTPATH		= 'layoutRootPath';
	
	/**
	 * Render
	 *
	 * @param array $config
	 * @param array $variables
	 * @return string
	 * @api
	 */		
	static public function render(array $config = NULL, array $variables = NULL) {
		
		// we do need atleast an template file path location otherwise we can't generate the stand alone view
		if (!isset($config[self::CONFIG_TEMPLATEFILEPATH])) {
			throw new Exception('The template file path is not set in the config');	
		}
		
		$view = GeneralUtility::makeInstance('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
		$view->setFormat('html');
		// sets the template file and filename
		$view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($config[self::CONFIG_TEMPLATEFILEPATH]));
		// sets the partial root path
		if (isset($config[self::CONFIG_PARTIALROOTPATH])) {
			$view->setPartialRootPath($config[self::CONFIG_PARTIALROOTPATH]);
		}
		// sets the layout root path
		if (isset($config[self::CONFIG_LAYOUTROOTPATH])) {
			$view->setLayoutRootPath($config[self::CONFIG_LAYOUTROOTPATH]);
		}
		// sets the variables
		if ($variables) {
			$view->assignMultiple($variables);
		}
		
		return $view->render();
	}
	
}