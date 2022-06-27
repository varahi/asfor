<?php
namespace T3Dev\Trainingcaces\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * The repository for Exams
 */
class ExamRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
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

    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'sessionDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
        'theoryTestDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
        'practiceTestDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
        'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    );

    /**
     * @var string
     *
     */
    public function findLast($user)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('candidate', $user)
            )
        );

        $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));

        return $query->execute()->getFirst();
    }

    /**
     * Find entities by a given DateTime object
     *
     * @param \DateTime $date The DateTime to filter by
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     *
     */
    public function findByDateRange($startDate, $endDate)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('practiceTestDate', $startDate),
                $query->lessThanOrEqual('practiceTestDate', $endDate)
            )
        );
        $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        return $query->execute();
    }

    /**
     * Find entities by a given DateTime object
     *
     * @param \DateTime $date The DateTime to filter by
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     *
     */
    public function findByStartDate($date)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('practiceTestDate', $date)
            )
        );
        $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        return $query->execute();
    }

    /**
     * Find entities by a given DateTime object
     *
     * @param \DateTime $date The DateTime to filter by
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     *
     */
    public function findByEndtDate($date)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->lessThanOrEqual('practiceTestDate', $date)
            )
        );
        $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        return $query->execute();
    }

    public function deletePracticeResultFile($exam)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
        $queryBuilder
            ->update('sys_file_reference')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($exam->getPracticeResultFile()->getUid()))
            )
            ->set('deleted', '1')
            ->execute();
    }

    public function deleteTheoryResultFile($exam)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
        $queryBuilder
            ->update('sys_file_reference')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($exam->getTheoryResultFile()->getUid()))
            )
            ->set('deleted', '1')
            ->execute();
    }

    public function updateExam($exam, $field, $value)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_trainingcaces_domain_model_exam');
        $queryBuilder
            ->update('tx_trainingcaces_domain_model_exam')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($exam))
            )
            ->set($field, $value)
            ->execute();
    }

    public function setRawObject($uid, $pid)
    {
        $table = 'tx_trainingcaces_domain_model_exam';
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable($table);
        $queryBuilder
            ->insert($table)
            ->values([
                'crdate' => time(),
                'pid' => $pid,
                'uid' => $uid,
                'deleted' => '0',
                'hidden' => '0'
            ])
            ->execute();
    }

    public function setRawValue($uid, $pid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_trainingcaces_domain_model_exam');
        $queryBuilder
            ->update('tx_trainingcaces_domain_model_exam')
            ->set('deleted', '0')
            ->set('hidden', '0')
            ->set('pid', $pid)
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid))
            )
            ->execute();
    }

    public function setSubCat($value)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_trainingcaces_domain_model_exam');
        $queryBuilder
            ->update('tx_trainingcaces_domain_model_exam')
            ->set('sub_cat', $value)
            ->execute();
    }
}
