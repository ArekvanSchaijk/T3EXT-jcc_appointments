<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_jccappointments_domain_model_appointment'] = array(
	'ctrl' => $TCA['tx_jccappointments_domain_model_appointment']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, app_id, app_time, secret_hash, sms, sms_send, mail_send, closed',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, app_id, app_time, secret_hash, sms, sms_send, mail_send, closed,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
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
			'exclude' => 1,
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
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
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
			'exclude' => 1,
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
		'app_id' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xml:tx_jccappointments_domain_model_appointment.app_id',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'int',
			),
		),
		'app_time' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xml:tx_jccappointments_domain_model_appointment.app_time',
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
			'exclude' => 0,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xml:tx_jccappointments_domain_model_appointment.secret_hash',
			'config' => array(
				'type' => 'input',
				'readOnly' => TRUE,
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'sms' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xml:tx_jccappointments_domain_model_appointment.sms',
			'config' => array(
				'type' => 'check',
				'readOnly' => TRUE,
				'default' => 0
			),
		),
		'sms_send' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xml:tx_jccappointments_domain_model_appointment.sms_send',
			'config' => array(
				'type' => 'check',
				'readOnly' => TRUE,
				'default' => 0
			),
		),
		'mail_send' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xml:tx_jccappointments_domain_model_appointment.mail_send',
			'config' => array(
				'type' => 'check',
				'readOnly' => TRUE,
				'default' => 0
			),
		),
		'closed' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:jcc_appointments/Resources/Private/Language/locallang_db.xml:tx_jccappointments_domain_model_appointment.closed',
			'config' => array(
				'type' => 'check',
				'readOnly' => TRUE,
				'default' => 0
			),
		),
	),
);

?>