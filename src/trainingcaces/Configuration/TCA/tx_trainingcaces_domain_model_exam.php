<?php

$languageFile = 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam',
        'label' => 'validate_date',
        'label_alt' => 'type,category,sub_cat,candidate',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'theory_result,practice_result,selection',
        'iconfile' => 'EXT:trainingcaces/Resources/Public/Icons/tx_trainingcaces_domain_model_exam.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, number, is_choice, is_practice, is_option, session_date, validate_date, theory_test_date, note,
        theory_result, theory_result_file, practice_test_date, practice_result, practice_result_file, enterprice_client, 
        place, candidate, theory_trainer, practice_trainer, type, category, theory_answers, practice_answers, theory_status, practice_status,
        theory_is_sent, practice_is_sent, next_exam',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, number, is_choice, is_practice, is_option, session_date, validate_date, candidate, note, next_exam,
        --div--;LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces.theory, theory_trainer, theory_test_date, theory_result, theory_result_file, theory_status, theory_is_sent, theory_answers,
        --div--;LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces.practice, practice_trainer, practice_test_date, practice_result, practice_result_file, practice_status, practice_is_sent, practice_answers,
        --div--;LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces.category, enterprice_client, place, type, category, sub_cat, 
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_trainingcaces_domain_model_exam',
                'foreign_table_where' => 'AND {#tx_trainingcaces_domain_model_exam}.{#pid}=###CURRENT_PID### AND {#tx_trainingcaces_domain_model_exam}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'number' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.number',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'is_choice' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.is_choice',
            'config' => [
                'type' => 'input',
                'size' => 4,
                //'eval' => 'trim'
                'eval' => 'int'
            ]
        ],
        'is_practice' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.is_practice',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]
        ],
        'is_option' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.is_option',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]
        ],
        'session_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.session_date',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date',
                'default' => null,
                //'default'  => date('Y-m-d H:i:s'),
                //'default'  => date('Y-m-d'),
            ],
        ],
        'validate_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.validate_date',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date',
                'default' => null,
            ],
        ],
        'theory_test_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.theory_test_date',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date',
                'default' => null,
            ],
        ],
        'theory_result' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.theory_result',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'theory_result_file' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.theory_result_file',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'theory_result_file',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
                    ],
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ]
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'theory_result_file',
                        'tablenames' => 'tx_trainingcaces_domain_model_exam',
                        'table_local' => 'sys_file',
                    ],
                    'maxitems' => 1
                ]
            ),
            
        ],
        'practice_test_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.practice_test_date',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date',
                'default' => null,
            ],
        ],
        'practice_result' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.practice_result',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'practice_result_file' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.practice_result_file',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'practice_result_file',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
                    ],
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ]
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'practice_result_file',
                        'tablenames' => 'tx_trainingcaces_domain_model_exam',
                        'table_local' => 'sys_file',
                    ],
                    'maxitems' => 1
                ]
            ),
            
        ],
        'selection' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.selection',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'theory_answers' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.theory_answers',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'practice_answers' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.practice_answers',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'theory_status' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.theory_status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [$languageFile . 'tx_trainingcaces_domain_model_exam.theory_status.0', 0],
                    [$languageFile . 'tx_trainingcaces_domain_model_exam.theory_status.1', 1],
                    [$languageFile . 'tx_trainingcaces_domain_model_exam.theory_status.2', 2],
                    [$languageFile . 'tx_trainingcaces_domain_model_exam.theory_status.3', 3],
                ],
            ]
        ],
        'practice_status' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.practice_status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [$languageFile . 'tx_trainingcaces_domain_model_exam.practice_status.0', 0],
                    [$languageFile . 'tx_trainingcaces_domain_model_exam.practice_status.1', 1],
                    [$languageFile . 'tx_trainingcaces_domain_model_exam.practice_status.2', 2],
                    [$languageFile . 'tx_trainingcaces_domain_model_exam.practice_status.3', 3],
                ],

            ]
        ],
        'enterprice_client' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.enterprice_client',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_trainingcaces_domain_model_enterpriseclient',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
        'place' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.place',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_trainingcaces_domain_model_place',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
        'candidate' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.candidate',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
        'theory_trainer' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.theory_trainer',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                //'foreign_table' => 'fe_users',
                'itemsProcFunc' => 'T3Dev\\Trainingcaces\\ProcFunc\\TcaProcFunc->theoryTrainersItems',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],

        ],
        'practice_trainer' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.practice_trainer',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                //'foreign_table' => 'fe_users',
                'itemsProcFunc' => 'T3Dev\\Trainingcaces\\ProcFunc\\TcaProcFunc->practiceTrainersItems',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],

        ],
        'type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_trainingcaces_domain_model_type',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
        'category' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_trainingcaces_domain_model_category',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
        'path_segment' => [
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.path_segment',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['titel']
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
                'default' => ''
            ]

        ],
        'theory_is_sent' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.theory_is_sent',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'practice_is_sent' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.practice_is_sent',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'note' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.note',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'company' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.company',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'sub_cat' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.sub_cat',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_trainingcaces_domain_model_subcategory',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'next_exam' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.next_exam',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],

        /*
        'next_exam' => [
            'exclude' => true,
            'label' => 'LLL:EXT:trainingcaces/Resources/Private/Language/locallang_db.xlf:tx_trainingcaces_domain_model_exam.next_exam',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date',
                'default' => null,
            ],
        ],
        */
    
    ],
];
