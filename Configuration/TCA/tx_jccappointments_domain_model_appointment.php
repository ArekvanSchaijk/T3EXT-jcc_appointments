<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment',
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
		'iconfile' => 'EXT:jcc_appointments/Resources/Public/Icons/Calendar.gif',
		'typeicon_classes' => array(
			'default' => 'jcc_calendar'
		),
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, app_id, app_time, secret_hash, sms, sms_send, mail_send, closed, client_id, client_initials, client_insertions, client_last_name, client_sex, client_date_of_birth, client_street, client_street_number, client_postal_code, client_city, client_phone, client_mobile_phone, client_email, location_name, location_address, location_postal_code, location_phone',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, app_id, app_time, secret_hash, sms, sms_send, sms_send_date, mail_send, closed, client_id, client_initials, client_insertions, client_last_name, client_sex, client_date_of_birth, client_street, client_street_number, client_postal_code, client_city, client_phone, client_mobile_phone, client_email, location_name, location_address, location_postal_code, location_phone'),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => TRUE,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => TRUE,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_jccappointments_domain_model_appointment',
				'foreign_table_where' => 'AND tx_jccappointments_domain_model_appointment.pid=###CURRENT_PID### AND tx_jccappointments_domain_model_appointment.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'app_id' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.app_id',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'int',
			),
		),
		'app_time' => array(
			'exclude' => TRUE,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.app_time',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'secret_hash' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.secret_hash',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'sms' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.sms',
			'config' => array(
				'type' => 'check',
				'readOnly' => TRUE,
				'default' => 0
			),
		),
		'sms_send' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.sms_send',
			'config' => array(
				'type' => 'check',
				'readOnly' => TRUE,
				'default' => 0
			),
		),
		'sms_send_date' => array(
			'exclude' => TRUE,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.sms_send_date',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'mail_send' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.mail_send',
			'config' => array(
				'type' => 'check',
				'readOnly' => TRUE,
				'default' => 0
			),
		),
		'closed' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.closed',
			'config' => array(
				'type' => 'check',
				'readOnly' => TRUE,
				'default' => 0
			),
		),
		'client_id' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_id',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'client_initials' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_initials',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'client_insertions' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_insertions',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'client_last_name' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_last_name',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'client_sex' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_sex',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'client_date_of_birth' => array(
			'exclude' => TRUE,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_date_of_birth',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 13,
				'max' => 20,
				'eval' => 'date',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'client_street' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_street',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'client_street_number' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_street_number',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'client_postal_code' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_postal_code',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'client_city' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_city',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'client_phone' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_phone',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'client_mobile_phone' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_mobile_phone',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'client_email' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.client_email',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'location_name' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.location_name',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'location_address' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.location_address',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'location_postal_code' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.location_postal_code',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'location_phone' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_appointment.location_phone',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
	),
);