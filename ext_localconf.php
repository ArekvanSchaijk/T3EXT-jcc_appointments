<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ucreation.' . $_EXTKEY,
	'Pi1',
	array(
		'Appointment' => 'form, addProduct, removeProduct, chooseLocation, defaultModeCalendarSelectMonth, defaultModeCalendarSelectDay, showAllModeCalendarSelectDay, chooseDateAndTime, postClientData, confirmAppointment, nextStep, previousStep'
	),
	// non-cacheable actions
	array(
		'Appointment' => 'form, addProduct, removeProduct, chooseLocation, defaultModeCalendarSelectMonth, defaultModeCalendarSelectDay, showAllModeCalendarSelectDay, chooseDateAndTime, postClientData, confirmAppointment, nextStep, previousStep'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ucreation.' . $_EXTKEY,
	'Pi2',
	array(
		'Appointment' => 'cancel'
	),
	// non-cacheable actions
	array(
		'Appointment' => 'cancel'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ucreation.' . $_EXTKEY,
	'Pi3',
	array(
		'TakeoutText' => 'show'
	),
	// non-cacheable actions
	array(

	)
);

// extbase commandController
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Ucreation\JccAppointments\Command\ReminderCommandController';