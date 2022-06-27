<?php
defined('TYPO3_MODE') or die();

// Additional fields for page
$GLOBALS['TCA']['pages']['columns'] += [

    'section_class' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:pimentconfig/Resources/Private/Language/locallang_db.xlf:page.section_class',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:pimentconfig/Resources/Private/Language/locallang_db.xlf:page.select.section_class_none', ''],
                ['LLL:EXT:pimentconfig/Resources/Private/Language/locallang_db.xlf:page.select.section_class_example', 'example_class'],
            ],
            'size' => 1,
            'minitems' => 0,
            'maxitems' => 1,
        ],
    ],

    'container_class' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:pimentconfig/Resources/Private/Language/locallang_db.xlf:page.container_class',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:pimentconfig/Resources/Private/Language/locallang_db.xlf:page.select.container_class_none', ''],
                ['LLL:EXT:pimentconfig/Resources/Private/Language/locallang_db.xlf:page.select.container_class_example', 'example_class'],
            ],
            'size' => 1,
            'minitems' => 0,
            'maxitems' => 1,
        ],
    ],

    'hide_breadcrumb' => [
        'label' => 'LLL:EXT:pimentconfig/Resources/Private/Language/locallang_db.xlf:page.hide_breadcrumb',
        'exclude' => 1,
        'config' => [
            'type' => 'check',
            'items' => [
                ['', '']
            ]
        ]
    ],

];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'section_class',
    '1',
    'after:title'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'container_class',
    '1',
    'after:section_class'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'hide_breadcrumb',
    '1',
    'after:container_class'
);
