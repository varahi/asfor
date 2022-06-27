<?php
if (! defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(
    function () {
        /*
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Pimentconfig',
            'Pimentconfig',
            'Piment Config Site template'
        );
        */

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('pimentconfig', 'Configuration/TypoScript', 'Piment Config Site templaten');

        /**
         * Page TS Config
         */
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pimentconfig/Configuration/TsConfig/page.tsconfig">');

        /**
         * Add some basic User TS Config
         */
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pimentconfig/Configuration/TsConfig/user.tsconfig">');

        /**
         * Add rte_ckeditor custom config
         */
        $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['custom'] = 'EXT:pimentconfig/Configuration/RTE/Custom.yaml';
    }
);
