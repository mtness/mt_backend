<?php
defined('TYPO3') || die('Access denied.');

if (TYPO3_MODE === 'BE') {

	// Hook into the page module
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/db_layout.php']['drawHeaderHook']['mt_backend'] =
		\MarkusTimtner\MtBackend\Hooks\PageHook::class . '->render';
}
