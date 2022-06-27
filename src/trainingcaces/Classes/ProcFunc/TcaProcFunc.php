<?php

namespace T3Dev\Trainingcaces\ProcFunc;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TcaProcFunc
{

    /**
     * Initializes a TypoScript Frontend necessary for using TypoScript and TypoLink functions
     *
     * @param int $id
     * @param int $typeNum
     */
    protected function initTSFE($id = 1, $typeNum = 0)
    {

        /** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController */
        //$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class, $GLOBALS['TYPO3_CONF_VARS']);
        $tsfe = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', $GLOBALS['TYPO3_CONF_VARS'], $id, $typeNum);
        $tsfe->getConfigArray();
        $tsfe->initTemplate();
    }


    /**
     * @param array $config
     * @return array
     */
    public function theoryTrainersItems($config)
    {
        //$this->initTSFE();
        //$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_trainingcaces.']['settings.'];
        // ToDo: maybe get setting usergroups from typoscript

        $table = 'fe_users';
        /** @var  \TYPO3\CMS\Core\Database\Query\QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $statement = $queryBuilder
            ->select('*')
            ->from($table)
            ->where($queryBuilder->expr()->eq('usergroup', '2'))
            ->orWhere($queryBuilder->expr()->eq('usergroup', $queryBuilder->createNamedParameter('3,2')))
            ->orWhere($queryBuilder->expr()->eq('usergroup', $queryBuilder->createNamedParameter('2,3')))
            ->execute();

        while ($rows = $statement->fetchAll()) {
            foreach ($rows as $row) {
                $itemList[] = [$row['username'] .' - ' . $row['first_name'] .' '. $row['last_name'], $row['uid']];
            }

            $config['items'] = $itemList;
            return $config;
        }
    }

    /**
     * @param array $config
     * @return array
     */
    public function practiceTrainersItems($config)
    {
        $table = 'fe_users';
        /** @var  \TYPO3\CMS\Core\Database\Query\QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $statement = $queryBuilder
            ->select('*')
            ->from($table)
            ->where($queryBuilder->expr()->eq('usergroup', '3'))
            ->orWhere($queryBuilder->expr()->eq('usergroup', $queryBuilder->createNamedParameter('3,2')))
            ->orWhere($queryBuilder->expr()->eq('usergroup', $queryBuilder->createNamedParameter('2,3')))
            ->execute();

        while ($rows = $statement->fetchAll()) {
            foreach ($rows as $row) {
                $itemList[] = [$row['username'] .' - ' . $row['first_name'] .' '. $row['last_name'], $row['uid']];
            }
            $config['items'] = $itemList;
            return $config;
        }
    }
}
