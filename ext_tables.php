<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'JCC Appointment Module'
);

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'JCC Appointment Module');

?>