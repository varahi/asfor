<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Pimentconfig',
            'Pimentconfig',
            [
                //Example to request some action
                //\Piment\PimentConfig\Controller\SomeController::class => 'list, show',
            ],
            // non-cacheable actions
            [
            ]
        );
    }
);
