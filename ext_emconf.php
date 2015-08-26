<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "jcc_appointments".
 *
 * Auto generated 17-12-2014 16:00
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'JCC Appointment Module',
	'description' => 'This extension provides a Appointment module based on the JCC Software API',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '2.2.0',
	'dependencies' => 'extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Arek van Schaijk',
	'author_email' => 'info@ucreation.nl',
	'author_company' => 'Ucreation',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.1.0-6.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:34:{s:12:"ext_icon.gif";s:4:"e922";s:17:"ext_localconf.php";s:4:"e26c";s:14:"ext_tables.php";s:4:"334b";s:14:"ext_tables.sql";s:4:"8160";s:45:"Classes/Command/ReminderCommandController.php";s:4:"2aa6";s:44:"Classes/Controller/AppointmentController.php";s:4:"9176";s:37:"Classes/Controller/BaseController.php";s:4:"98a2";s:36:"Classes/Domain/Model/Appointment.php";s:4:"7e5c";s:28:"Classes/Domain/Model/Sms.php";s:4:"90b4";s:51:"Classes/Domain/Repository/AppointmentRepository.php";s:4:"baa9";s:43:"Classes/Domain/Repository/SmsRepository.php";s:4:"1e04";s:33:"Configuration/TCA/Appointment.php";s:4:"c600";s:25:"Configuration/TCA/Sms.php";s:4:"b2bc";s:38:"Configuration/TypoScript/constants.txt";s:4:"f1d8";s:34:"Configuration/TypoScript/setup.txt";s:4:"e804";s:41:"Resources/Private/Email/Confirmation.html";s:4:"dc9f";s:40:"Resources/Private/Language/locallang.xml";s:4:"d843";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"206d";s:55:"Resources/Private/PHP/Messagebird/class.Messagebird.php";s:4:"bead";s:52:"Resources/Private/Partials/Calendar/DefaultMode.html";s:4:"4460";s:54:"Resources/Private/Partials/Calendar/SelectionMode.html";s:4:"b9e0";s:45:"Resources/Private/Partials/Form/Progress.html";s:4:"7b74";s:42:"Resources/Private/Partials/Form/Step1.html";s:4:"2937";s:42:"Resources/Private/Partials/Form/Step2.html";s:4:"6a4c";s:42:"Resources/Private/Partials/Form/Step3.html";s:4:"02a6";s:42:"Resources/Private/Partials/Form/Step4.html";s:4:"aad7";s:42:"Resources/Private/Partials/Form/Step5.html";s:4:"e505";s:51:"Resources/Private/Templates/Appointment/Cancel.html";s:4:"39bb";s:49:"Resources/Private/Templates/Appointment/Form.html";s:4:"95db";s:45:"Resources/Private/Templates/Sms/Reminder.html";s:4:"0e18";s:35:"Resources/Public/Css/Stylesheet.css";s:4:"5fd1";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:70:"Resources/Public/Icons/tx_jccappointments_domain_model_appointment.gif";s:4:"f73b";s:62:"Resources/Public/Icons/tx_jccappointments_domain_model_sms.gif";s:4:"f73b";}',
	'suggests' => array(
	),
);