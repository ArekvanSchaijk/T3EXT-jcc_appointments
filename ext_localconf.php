<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Configures the front-end plugins
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ucreation.' . $_EXTKEY,
	'Pi1',
	array(
		'Appointment' => 'form, addProduct, removeProduct, chooseLocation, defaultModeCalendarSelectMonth, defaultModeCalendarSelectDay, showAllModeCalendarSelectDay, chooseDateAndTime, postClientData, confirmAppointment, nextStep, previousStep'
	),
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
	array(

	)
);

// extbase commandController
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Ucreation\JccAppointments\Command\ReminderCommandController';

// TYPO3 >= 7.5 Icon Registry
if (version_compare(TYPO3_branch, '7.5', '>=') && TYPO3_MODE == 'BE') {
	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Imaging\\IconRegistry');
	$iconRegistry->registerIcon('jcc_calendar', 'TYPO3\\CMS\\Core\\Imaging\\IconProvider\\BitmapIconProvider', array(
		'source' => 'EXT:jcc_appointments/Resources/Public/Icons/Calendar.png'
	));
}