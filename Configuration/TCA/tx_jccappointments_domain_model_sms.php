<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_sms',
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
		'iconfile' => 'EXT:jcc_appointments/Resources/Public/Icons/Calendar.gif',
		'typeicon_classes' => array(
			'default' => 'jcc_calendar'
		),
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, recipient_name, recipient_number, sender_name, message, app_id, status, send_date',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, recipient_name, recipient_number, sender_name, message, app_id, status, send_date,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
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
				'foreign_table' => 'tx_jccappointments_domain_model_sms',
				'foreign_table_where' => 'AND tx_jccappointments_domain_model_sms.pid=###CURRENT_PID### AND tx_jccappointments_domain_model_sms.sys_language_uid IN (-1,0)',
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
		'hidden' => array(
			'exclude' => TRUE,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => TRUE,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config' => array(
				'type' => 'input',
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
		'endtime' => array(
			'exclude' => TRUE,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => array(
				'type' => 'input',
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
		'recipient_name' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_sms.recipient_name',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'recipient_number' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_sms.recipient_number',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'sender_name' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_sms.sender_name',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'message' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_sms.message',
			'config' => array(
				'type' => 'text',
				'readOnly' => TRUE,
				'cols' => 50,
				'rows' => 10,
				'eval' => 'trim'
			),
		),
		'app_id' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_sms.app_id',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 4,
				'eval' => 'int'
			),
		),
		'status' => array(
			'exclude' => FALSE,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_sms.status',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'send_date' => array(
			'exclude' => TRUE,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xlf:tx_jccappointments_domain_model_sms.send_date',
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
	),
);