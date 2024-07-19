<?php

$EM_CONF[$_EXTKEY] = [
  'title' => 'MT BACKEND',
  'description' => 'This TYPO3 Extension provides a preview of page properties in the page module, and a some css touchup.',
  'category' => 'be',
  'version' => '12.0.2',
  'state' => 'stable',
  'author' => 'Markus Timtner',
  'author_email' => 'markus@timtner.tech',
  'author_company' => '',
  'constraints' => [
    'depends' => [
      'typo3' => '10.0.0-12.5.99',
	],
    'conflicts' => [],
    'suggests' => [],
  ],
];
