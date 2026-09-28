<?php
defined('TYPO3') || die('Access denied.');

// NOTE: ext_tables.php is deprecated since TYPO3 v14.3 (removal in v15). This block
// is only needed for TYPO3 < 13 (TBE_STYLES skins); from v13 on the stylesheet is
// registered via ext_localconf.php instead, so this file is a no-op there.
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
