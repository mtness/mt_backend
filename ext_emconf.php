<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "mt_backend".
 *
 * Auto generated 21-04-2016 12:34
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
  'title' => 'MT Backend Touchup',
  'description' => 'the mtness touchup for the TYPO3 Backend, css and other.',
  'category' => 'be',
  'author' => 'Markus Timtner',
  'author_email' => 'me@mtness.net',
  'author_company' => '',
  'version' => '11.1.0',
  'state' => 'stable',
  'uploadfolder' => false,
  'createDirs' => '',
  'constraints' => [
    'depends' => [
      'typo3' => '8.7.0-11.9.99',
	],
    'conflicts' => [],
    'suggests' => [],
  ],
];
