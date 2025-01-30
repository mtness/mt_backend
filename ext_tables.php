<?php
defined('TYPO3') || die('Access denied.');

$versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
if ($versionInformation->getMajorVersion() < 13) {
	// Custom CSS include
	$GLOBALS['TBE_STYLES']['skins']['mt_backend'] = [
		'name' => 'mt_backend',
		'stylesheetDirectories' => [
			'css' => 'EXT:mt_backend/Resources/Public/Css/'
		]
	];
}
