<?php
namespace Ucreation\JccAppointments\Controller;

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
 * Class TakeoutTextController
 *
 * @package Ucreation\JccAppointments
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class TakeoutTextController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	
	/**
	 * @var \Ucreation\JccAppointments\Domain\Repository\TakeoutTextRepository
	 * @inject
	 */
	protected $takeoutTextRepository = NULL;
	
	/**
	 * Show Action
	 *
	 * @return void
	 */
	public function showAction() {
		$takeoutTexts = array();
		if ($this->settings['selection']) {
			if (strpos($this->settings['selection'], ',') !== FALSE) {
				$uids = GeneralUtility::trimExplode(',', $this->settings['selection']);
				foreach ($uids as $uid) {
					if ($takeoutText = $this->takeoutTextRepository->findOneByUid($uid)) {
						$takeoutTexts[] = $takeoutText;	
					}
				}
			} else {
				if ($takeoutText = $this->takeoutTextRepository->findOneByUid($this->settings['selection'])) {
					$takeoutTexts[] = $takeoutText;	
				}
			}
		}
		$this->view->assign('takeoutTexts', $takeoutTexts);
	}

}