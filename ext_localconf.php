<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi1',
	array(
		'Appointment' => 'form, addProduct, removeProduct, chooseLocation, defaultModeCalendarSelectMonth, defaultModeCalendarSelectDay, chooseDateAndTime, postClientData, confirmAppointment, nextStep, previousStep'
	),
	// non-cacheable actions
	array(
		'Appointment' => 'form, addProduct, removeProduct, chooseLocation, defaultModeCalendarSelectMonth, defaultModeCalendarSelectDay, chooseDateAndTime, postClientData, confirmAppointment, nextStep, previousStep'
	)
);

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi2',
	array(
		'Appointment' => 'cancel'
	),
	// non-cacheable actions
	array(
		'Appointment' => 'cancel'
	)
);

// extbase commandController
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Tx_JccAppointments_Command_ReminderCommandController';

?>