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
		'searchFields' => 'app_id,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Appointment.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_jccappointments_domain_model_appointment.gif'
	),
);

?>