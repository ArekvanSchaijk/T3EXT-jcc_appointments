<?php
namespace Ucreation\JccAppointments\Domain\Repository;

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

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Class AppointmentRepository
 *
 * @package Ucreation\JccAppointments
 * @author Arek van Schaijk <info@ucreation.nl>
 */
class AppointmentRepository extends Repository {
	
	/**
	 * Find Unsend Sms
	 *
	 * @param int $interval
	 * @param mixed $limit
	 * @return array
	 */
	public function findUnsendSms($interval, $limit = FALSE) {
		
		$query = $this->createQuery();
		
		// prepare interval
		$interval = time() + $interval;
		
        $query->matching(
			$query->logicalAnd(
				$query->equals('sms', 1),
				$query->equals('sms_send', 0),
				$query->lessThanOrEqual('app_time', $interval),
				$query->greaterThanOrEqual('app_time', time())
			)
		);
		
        $query->setOrderings(array(
			'uid' => QueryInterface::ORDER_DESCENDING
		));
		
		if ($limit) {
        	$query->setLimit((integer)$limit);
		}
			
        return $query->execute();
	}
}