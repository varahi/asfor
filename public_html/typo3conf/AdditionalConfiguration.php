<?php

// We let the loader load context and environment specific configuration from .env
$configLoader = \Configloader\Utility\ConfigLoaderFactory::buildLoader(
    $context = \TYPO3\CMS\Core\Core\Environment::getContext()->isProduction() ? 'production' : 'development',
    $rootDir = dirname(dirname(__DIR__)),
    $fixedCacheIdentifier = getenv('CONFIGURATION_CACHE_IDENTIFIER')
);

$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive(
    $GLOBALS['TYPO3_CONF_VARS'],
    $configLoader->load()
);

// If .env crypto file exists, decrypt DB password
if(!empty(getenv('ENV_CRYPTO_FILE_PATH'))) {
    $keyFilePath = $rootDir . '/' . getenv('ENV_CRYPTO_FILE_PATH');

    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = \Configloader\Utility\Crypto::decryptCredential($GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'], $keyFilePath);
}

/**
 * Piment Rouge: additionally, we use the classic CAG conf.d approach
 *
 * To enable your local configuration just
 * add one or more files in conf.d.
 * They will be loaded automatically.
 */
$configurationFiles = \TYPO3\CMS\Core\Utility\GeneralUtility::getFilesInDir(
    \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('typo3conf/conf.d/'),
    'php',
    true
);
foreach ($configurationFiles as $file) {
    require_once($file);
}

// Disable deprecation log
$GLOBALS['TYPO3_CONF_VARS']['LOG']['TYPO3']['CMS']['deprecations']['writerConfiguration'][\TYPO3\CMS\Core\Log\LogLevel::NOTICE] = [];
