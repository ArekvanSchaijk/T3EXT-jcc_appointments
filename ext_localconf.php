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

if (TYPO3_MODE == 'BE') {
	// Icon Registry
	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Imaging\IconRegistry::class);
	$iconRegistry->registerIcon('jcc_calendar', \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class, array(
		'source' => 'EXT:jcc_appointments/Resources/Public/Icons/Calendar.png'
	));
}