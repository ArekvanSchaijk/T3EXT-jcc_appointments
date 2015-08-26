<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'JCC - Appointment Form'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi2',
	'JCC - Cancel Appointment'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi3',
	'JCC - Display Takeout Text(s)'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'JCC Appointment Module');

$pluginSignature = str_replace('_','',$_EXTKEY) . '_' . pi3;
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_' .pi3. '.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jccappointments_domain_model_appointment');
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
		'searchFields' => 'app_id,sms,sms_send,sms_send_date,client_id,client_initials,client_insertions,client_last_name,client_sex,client_date_of_birth,client_street,client_street_number,client_postal_code,client_city,client_phone,client_mobile_phone,client_email,location_name,location_address,location_postal_code,location_phone',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Appointment.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_jccappointments_domain_model_appointment.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jccappointments_domain_model_takeouttext');
$TCA['tx_jccappointments_domain_model_takeouttext'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xml:tx_jccappointments_domain_model_takeouttext',
		'label' => 'product_id',
		'label_userFunc' => 'Ucreation\\JccAppointments\\Handler\\HookHandler->tcaProductName',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',
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
		'searchFields' => 'text',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/TakeoutText.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_jccappointments_domain_model_takeouttext.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jccappointments_domain_model_sms');
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
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Sms.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_jccappointments_domain_model_sms.gif'
	),
);