<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Trainingcaces',
            'Trainingcaces',
            'Training Caces'
        );

        /*
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'T3Dev.Trainingcaces',
            'TrainingcacesApi',
            'Training Caces REST Api'
        );
        */

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('trainingcaces', 'Configuration/TypoScript', 'Training Caces');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_trainingcaces_domain_model_exam', 'EXT:trainingcaces/Resources/Private/Language/locallang_csh_tx_trainingcaces_domain_model_exam.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_trainingcaces_domain_model_exam');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_trainingcaces_domain_model_enterpriseclient', 'EXT:trainingcaces/Resources/Private/Language/locallang_csh_tx_trainingcaces_domain_model_enterpriseclient.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_trainingcaces_domain_model_enterpriseclient');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_trainingcaces_domain_model_place', 'EXT:trainingcaces/Resources/Private/Language/locallang_csh_tx_trainingcaces_domain_model_place.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_trainingcaces_domain_model_place');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_trainingcaces_domain_model_type', 'EXT:trainingcaces/Resources/Private/Language/locallang_csh_tx_trainingcaces_domain_model_type.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_trainingcaces_domain_model_type');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_trainingcaces_domain_model_category', 'EXT:trainingcaces/Resources/Private/Language/locallang_csh_tx_trainingcaces_domain_model_category.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_trainingcaces_domain_model_category');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_trainingcaces_domain_model_subcategory', 'EXT:trainingcaces/Resources/Private/Language/locallang_csh_tx_trainingcaces_domain_model_subcategory.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_trainingcaces_domain_model_subcategory');
    }
);
