<?php

namespace T3Dev\Trainingcaces\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use T3Dev\Trainingcaces\Domain\Model\FrontendUserGroup;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/***
 *
 * This file is part of the "Training Caces" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Dmitry Vasilev <dmitry@t3dev.ru>
 *
 ***/
/**
 * A repository for feusers
 */
class FrontendUserRepository extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
{

    /**
     * Initializes the repository.
     *
     * @return void
     * @see \TYPO3\CMS\Extbase\Persistence\Repository::initializeObject()
     */
    public function initializeObject()
    {
        /** @var $querySettings Typo3QuerySettings */
        $querySettings = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }


    protected function listAllUsers()
    {
        $table = 'fe_users';
        /** @var  \TYPO3\CMS\Core\Database\Query\QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $queryBuilder
            ->select('*')
            ->from('fe_users')
            ->execute()
            ->fetchAll();

        $sql = $queryBuilder->getSql();
        $users = $this->createQuery()->statement($sql)->execute()->toArray();

        //while ($row = $statement->fetch()) {
        //}
        //\TYPO3\CMS\Core\Utility\DebugUtility::debug($sql);

        return $users;
    }

    /**
     * @var string
     *
     */
    public function findByUsername($login)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('username', $login)
            )
        );

        return $query->execute()->getFirst();
    }

    /**
     * @param FrontendUserGroup $theoryTrainerGroup
     * @param FrontendUserGroup $practiceTrainerGroup
     *
     */
    public function findByUsergroups(
        FrontendUserGroup $theoryTrainerGroup,
        FrontendUserGroup $practiceTrainerGroup
    ) {
        $query = $this->createQuery();
        $constraints = [];

        $constraints[] = $query->logicalOr(
            $query->logicalAnd(
                $query->equals('usergroup', $theoryTrainerGroup)
            ),
            $query->logicalAnd(
                $query->equals('usergroup', $practiceTrainerGroup)
            )
        );
        $query->matching($query->logicalAnd($constraints));

        return $query
            ->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING))
            ->execute();
    }


    /**
     * @param FrontendUserGroup $usergroup
     *
     */
    public function findByUsergroupObj(
        FrontendUserGroup $usergroup
    ) {
        $query = $this->createQuery();
        $constraints = [];

        $constraints[] = $query->logicalOr(
            $query->logicalAnd(
                $query->equals('usergroup', $usergroup)
            )
        );
        $query->matching($query->logicalAnd($constraints));

        return $query
            ->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING))
            ->execute();
    }

    /**
     * @var string
     *
     */
    public function findByFirstNameAndLastName($firstName, $lastName)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('firstName', $firstName),
                $query->equals('lastName', $lastName)
            )
        );

        return $query->execute()->getFirst();
    }

    public function deleteUserPhoto($user)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
        $queryBuilder
            ->update('sys_file_reference')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($user->getPhoto()->getUid()))
            )
            ->set('deleted', '1')
            ->execute();
    }

    public function restoreUser($uid, $pid)
    {
        $table = 'fe_users';

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $queryBuilder
            ->update($table)
            ->set('deleted', '0')
            ->set('disable', '0')
            ->set('pid', $pid)
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid))
            )
            ->execute();
    }


    /**
     * Find users by commaseparated usergroup list
     *
     * @param string $userGroupList commaseparated list of usergroup uids
     * @return QueryResultInterface|array
     */
    public function findByUsergroupList($userGroupList)
    {
        $query = $this->createQuery();

        // where
        $and = [
            $query->greaterThan('uid', 0)
        ];
        if (!empty($userGroupList)) {
            $selectedUsergroups = GeneralUtility::trimExplode(',', $userGroupList, true);
            $logicalOr = [];
            foreach ($selectedUsergroups as $group) {
                $logicalOr[] = $query->contains('usergroup', $group);
            }
            $and[] = $query->logicalOr($logicalOr);
        }

        $query->matching($query->logicalAnd($and));
        $users = $query->execute();

        return $users;
    }
}
