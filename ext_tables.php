<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'JCC Appointments - Appointment Form'
);

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi2',
	'JCC Appointments - Cancel Appointment'
);

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'JCC Appointment Module');

t3lib_extMgm::allowTableOnStandardPages('tx_jccappointments_domain_model_appointment');
$TCA['tx_jccappointments_domain_model_appointment'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xml:tx_jccappointments_domain_model_appointment',
		'label' => 'app_id',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'app_id,sms,sms_send,sms_send_date,client_id,client_initials,client_insertions,client_last_name,client_sex,client_date_of_birth,client_street,client_street_number,client_postal_code,client_city,client_phone,client_mobile_phone,client_email,location_name,location_address,location_postal_code,location_phone',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Appointment.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_jccappointments_domain_model_appointment.gif'
	),
);

t3lib_extMgm::allowTableOnStandardPages('tx_jccappointments_domain_model_sms');
$TCA['tx_jccappointments_domain_model_sms'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xml:tx_jccappointments_domain_model_sms',
		'label' => 'recipient_name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'recipient_name,recipient_number,sender_name,message,app_id,status,send_date,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Sms.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_jccappointments_domain_model_sms.gif'
	),
);

?>