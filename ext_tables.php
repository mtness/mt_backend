<?php
defined('TYPO3_MODE') || die('Access denied.');

// Custom CSS include
$GLOBALS['TBE_STYLES']['skins']['mt_backend'] = [
	'name' => 'mt_backend',
	'stylesheetDirectories' => [
		'css' => 'EXT:mt_backend/Resources/Public/Css/'
	]
];
