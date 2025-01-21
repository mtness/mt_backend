<?php
defined('TYPO3') || die('Access denied.');


$versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
if ($versionInformation->getMajorVersion() > 12) {

	// Hook into the page module
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/db_layout.php']['drawHeaderHook']['mt_backend'] =
		\MarkusTimtner\MtBackend\Hooks\PageHook::class . '->render';
} else {

	$GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['mt_backend'] = 'EXT:mt_backend/Resources/Public/Css/MyBeTouchup.css';

}