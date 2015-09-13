<?php

$EM_CONF[$_EXTKEY] = array (
	'title' => 'JCC Appointment Module',
	'description' => 'This extension provides a Appointment module based on the JCC Software API',
	'category' => 'plugin',
	'version' => '2.5.0',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearcacheonload' => false,
	'author' => 'Arek van Schaijk',
	'author_email' => 'info@ucreation.nl',
	'author_company' => 'Ucreation',
	'constraints' => 
	array (
		'depends' => 
		array (
			'typo3' => '6.2.0-7.4.99',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
);