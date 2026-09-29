<?php

$EM_CONF[$_EXTKEY] = [
  'title' => 'MT BACKEND',
  'description' => 'This TYPO3 Extension provides a preview of page properties in the page module, and a some css touchup.',
  'category' => 'be',
  'version' => '14.0.1',
  'state' => 'stable',
  'author' => 'Markus Timtner',
  'author_email' => 'markus@timtner.tech',
  'author_company' => '',
  'constraints' => [
    'depends' => [
      'typo3' => '10.0.0-14.9.99',
	],
    'conflicts' => [],
    'suggests' => [],
  ],
];
