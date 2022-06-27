<?php
return [
    'SYS' => [
        'systemLog' => false,
        'belogErrorReporting' => 0,
    ],
    'LOG' => [
        'writerConfiguration' => [
            \TYPO3\CMS\Core\Log\LogLevel::WARNING => [
                \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                    'logFile' => dirname(dirname(dirname(dirname(__DIR__)))) . '/var/log/typo3-error.log'
                ]
            ]
        ]
    ],
];
