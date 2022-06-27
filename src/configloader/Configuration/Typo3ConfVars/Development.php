<?php
return [
    'SYS' => [
        // Debug
        'displayErrors' => 1,
        'devIPmask' => '*',
        'enableDeprecationLog' => 'file',
        'exceptionalErrors' => E_WARNING | E_USER_ERROR | E_RECOVERABLE_ERROR | E_DEPRECATED | E_USER_DEPRECATED,
        'systemLogLevel' => 0,
        'caching' => [
            'cacheConfigurations' => [
                // Uncommenting the two lines below will slow down request times dramatically
//                'cache_core' => array(
//                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
//                ),
//                'fluid_template' => array(
//                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
//                ),
                'cache_hash' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
                'cache_pages' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
                'cache_pagesection' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
                'cache_phpcode' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
                'cache_runtime' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend::class,
                ],
                'cache_rootline' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
                'cache_imagesizes' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
                'l10n' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
                'extbase_object' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
                'extbase_reflection' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
                'extbase_typo3dbbackend_tablecolumns' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
                'extbase_typo3dbbackend_queries' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
                'extbase_datamapfactory_datamap' => [
                    'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
                ],
            ]
        ]
    ],
    'BE' => [
        'debug' => true,
        // Convenience
        'sessionTimeout' => 60 * 60 * 24 * 365 // One year!
    ],
    'FE' => [
        'debug' => true,
    ],
    'MAIL' => [
        'transport' => 'mbox',
        'transport_mbox_file' => dirname(dirname(dirname(dirname(__DIR__)))) . '/var/log/sent-mails.log',
    ],
    'LOG' => [
        'writerConfiguration' => [
            \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
                \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                    'logFile' => dirname(dirname(dirname(dirname(__DIR__)))) . '/var/log/typo3-debug.log'
                ]
            ]
        ]
    ],
];
