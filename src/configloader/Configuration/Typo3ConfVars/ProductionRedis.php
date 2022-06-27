<?php


// only use the redis setup if

$dbStart = 12; // staring at 12 because edumeres.net is using the first 3-11
$redisConf = [];

// define list of caches to be set to REDIS config
$aRedisCache = [
    'cache_hash' => $dbStart . ';86400',
    'cache_pages' => ($dbStart + 1) . ';86400',
    'cache_pagesection' => ($dbStart + 2) . ';86400',
    'cache_rootline' => ($dbStart + 3) . ';86400',
    'cache_imagesizes' => ($dbStart + 4) . ';86400',
    'extbase_object' => ($dbStart + 5) . ';86400',
    'extbase_reflection' => ($dbStart + 6) . ';86400',
    'extbase_datamapfactory_datamap' => ($dbStart + 7) . ';86400',
    'azurestorage' => ($dbStart + 8) . ';86400',
];


// traverse the list caches and set up the redis connection based on .env setup
foreach ($aRedisCache as $table => $config) {

    // make sure all "tables" use the REDIS Backend
    $redisConf['SYS']['caching']['cacheConfigurations'][$table]['backend'] = 'TYPO3\\CMS\\Core\\Cache\\Backend\\RedisBackend';

    // get some more info on the redis config of each cache
    list($db, $lifetime) = explode(';', $config);
    $lifetime = $lifetime ? (int)$lifetime : 86400;
    $db = $db ? (int)$db : 0;

    // and add it to each "table's" setup
    $redisConf['SYS']['caching']['cacheConfigurations'][$table]['options']['hostname'] = 'redis';
    $redisConf['SYS']['caching']['cacheConfigurations'][$table]['options']['port'] = '6379';
    $redisConf['SYS']['caching']['cacheConfigurations'][$table]['options']['database'] = $db;

    if (!isset($redisConf['SYS']['caching']['cacheConfigurations'][$table]['options']['defaultLifetime'])) {
        $redisConf['SYS']['caching']['cacheConfigurations'][$table]['options']['defaultLifetime'] = $lifetime;
    }
}



// All production values should be in LocalConfiguration.php
return $redisConf;
