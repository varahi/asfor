<?php
namespace T3Dev\Trainingcaces\Controller;

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

use T3Dev\Trainingcaces\Domain\Model\Place;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;
use T3Dev\Trainingcaces\Domain\Model\Exam;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use T3Dev\Trainingcaces\Utility\FileUtility;

/**
 * ExamController
 */
class ExamController extends AbstractController
{

    /**
     * Set TypeConverter option for date time
     *
     * @return void
     */
    public function initializeAction()
    {
        if (isset($this->arguments['exam'])) {
            $this->arguments['exam']->getPropertyMappingConfiguration()->forProperty('sessionDate')
                ->setTypeConverterOption(
                    'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                    DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                    'd/m/Y'
                );

            $this->arguments['exam']->getPropertyMappingConfiguration()->forProperty('practiceTestDate')
                ->setTypeConverterOption(
                    'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                    DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                    'd/m/Y'
                );

            $this->arguments['exam']->getPropertyMappingConfiguration()->forProperty('theoryTestDate')
                ->setTypeConverterOption(
                    'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                    DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                    'd/m/Y'
                );

            $this->arguments['exam']->getPropertyMappingConfiguration()->forProperty('candidate.dateOfBirth')
                ->setTypeConverterOption(
                    'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                    DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                    'd/m/Y'
                );

            $propertyMappingConfiguration = $this->arguments->getArgument('exam')->getPropertyMappingConfiguration();
            $propertyMappingConfiguration->allowProperties('candidate');
            $propertyMappingConfiguration->forProperty('candidate.*')->allowProperties('dateOfBirth');
            $propertyMappingConfiguration->allowCreationForSubProperty('candidate.*');
            $propertyMappingConfiguration->allowModificationForSubProperty('candidate.*');
            $propertyMappingConfiguration->allowProperties('category');
            //$propertyMappingConfiguration->allowProperties('sub_category');
            //$propertyMappingConfiguration->allowProperties('subCat');
        }
    }

    /**
     * Load JS Libraries and Code
     */
    private function loadSourcesListView()
    {
        $extensionKey = 'trainingcaces';

        /** @var \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $extRelPath = PathUtility::stripPathSitePrefix(ExtensionManagementUtility::extPath($extensionKey));
        // Include js files
        $pageRenderer->addJsFile($extRelPath . "Resources/Public/JavaScript/jquery.magnific-popup.min.js", 'text/javascript', false, false, '', true);
        $pageRenderer->addJsFile($extRelPath . "Resources/Public/JavaScript/jquery.dataTables.js", 'text/javascript', false, false, '', true);
        // Include css files
        $pageRenderer->addCssFile($extRelPath . "Resources/Public/Css/magnific-popup.css", 'stylesheet', 'all', '', true);
        $pageRenderer->addCssFile($extRelPath . "Resources/Public/Css/datatables.css", 'stylesheet', 'all', '', true);
    }

    /**
     * Load JS Libraries and Code
     */
    private function loadSourcesDetailView()
    {
        $extensionKey = 'trainingcaces';

        /** @var \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $extRelPath = PathUtility::stripPathSitePrefix(ExtensionManagementUtility::extPath($extensionKey));
        // Include js files
        $pageRenderer->addJsFile($extRelPath . "Resources/Public/JavaScript/jquery.magnific-popup.min.js", 'text/javascript', false, false, '', true);
        // Include css files
        $pageRenderer->addCssFile($extRelPath . "Resources/Public/Css/magnific-popup.css", 'stylesheet', 'all', '', true);
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $this->loadSourcesListView();

        $startDate = $_POST['tx_trainingcaces_trainingcaces']['startDate'];
        $endDate = $_POST['tx_trainingcaces_trainingcaces']['endDate'];

        $startDateFormat = \DateTime::createFromFormat('d/m/Y', $startDate);
        $endDateFormat = \DateTime::createFromFormat('d/m/Y', $endDate);

        //$startDateOj = $this->getDateObj($startDate)->format('Y-m-d');
        //$endDateOj = $this->getDateObj($endDate);

        if (!empty($startDate) && !empty($endDate)) {
            $exams = $this->examRepository->findByDateRange($startDateFormat->format('Y-m-d'), $endDateFormat->format('Y-m-d'));
            $this->view->assign('startDateIsset', 1);
            $this->view->assign('endDateIsset', 1);
        } elseif (!empty($startDate)) {
            $this->view->assign('startDateIsset', 1);
            $exams = $this->examRepository->findByStartDate($startDateFormat->format('Y-m-d'));
        } elseif (!empty($endDate)) {
            $this->view->assign('endDateIsset', 1);
            $exams = $this->examRepository->findByEndtDate($endDateFormat->format('Y-m-d'));
        } else {
            $exams = $this->examRepository->findAll();
        }

        $this->view->assign('startDate', $startDate);
        $this->view->assign('endDate', $endDate);
        $this->view->assign('exams', $exams);
    }

    /**
     * action export to Excel file
     *
     * @return void
     */
    public function exportAction()
    {
        $exams = $this->examRepository->findAll();

        /** @var  FileUtility $fileUtility */
        $fileUtility = GeneralUtility::makeInstance(FileUtility::class);
        $confTargetFolder = $this->settings['upload']['csvdir'];
        $fileUtility->checkUploadDirectory($confTargetFolder);

        $csvdirFileName = $this->settings['upload']['csvdirFileName'];
        $filePathName = $confTargetFolder .'/'. $csvdirFileName;

        $headline = 'Date de session;Entreprise cliente;Lieu du test;Candidat;Date de naissance;Formateur test théorique;Date test théorique;Résultat test théorique;Formateur test pratique;Date test pratique;Résultat test pratique;Type de CACES;Catégorie CACES';
        $fileUtility->saveExamsCSVFile($exams, $filePathName, $headline);
        $this->downloadFile($filePathName, 'Exam List.csv');
    }

    /**
     * action show
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\Exam $exam
     * @return void
     */
    public function showAction(\T3Dev\Trainingcaces\Domain\Model\Exam $exam)
    {
        $this->loadSourcesDetailView();
        $this->view->assign('exam', $exam);
    }

    /**
     * action edit
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\Exam $exam
     * @Extbase\IgnoreValidation $exam
     * @return void
     */
    public function editAction(\T3Dev\Trainingcaces\Domain\Model\Exam $exam)
    {
        $this->view->assign('exam', $exam);
    }

    /**
     * action editExamAjax
     *
     * @param Exam $exam
     * @Extbase\IgnoreValidation $exam
     * @return void
     */
    public function editAjaxAction(Exam $exam)
    {
        $currentLoggedInUser = $this->userRepository->findByUid(intval($GLOBALS['TSFE']->fe_user->user['uid']));
        $group = $this->accessService->getFrontendUserGroups();
        $userGroup = $this->settings['groups']['feEditor'];

        if (!is_null($currentLoggedInUser)) {
            if (in_array($userGroup, $group)) {
                $this->view->assign('exam', $exam);

                $candidatesList = $this->userRepository->findByUsergroup($this->settings['groups']['candidate']);
                $this->view->assign('candidatesList', $candidatesList);
                $this->setFullName($candidatesList);

                //$theoryTrainerGroup = $this->userGroupRepository->findByUid($this->settings['groups']['theoryTrainer']);
                //$theoryTrainers = $this->userRepository->findByUsergroupObj($theoryTrainerGroup);

                $theoryTrainers = $this->userRepository->findByUsergroupList($this->settings['groups']['theoryTrainer']);
                $this->setFullName($theoryTrainers);
                $this->view->assign('theoryTrainerList', $theoryTrainers);

                //$practiceTrainerGroup = $this->userGroupRepository->findByUid($this->settings['groups']['practiceTrainer']);
                //$practiceTrainers = $this->userRepository->findByUsergroupObj($practiceTrainerGroup);

                $practiceTrainers = $this->userRepository->findByUsergroupList($this->settings['groups']['practiceTrainer']);
                $this->setFullName($practiceTrainers);
                $this->view->assign('practiceTrainerList', $practiceTrainers);

                $enterpriceClientList = $this->enterpriseClientRepository->findAll();
                $this->view->assign('enterpriceClientList', $enterpriceClientList);

                $placeList = $this->placeRepository->findAll();
                $this->view->assign('placeList', $placeList);

                $categoriesList = $this->categoryRepository->findAll();
                $this->view->assign('categoriesList', $categoriesList);

                $subCategoriesList = $this->subcategoryRepository->findAll();
                $this->view->assign('subCategoriesList', $subCategoriesList);

                $typeList = $this->typeRepository->findAll();
                $this->view->assign('typeList', $typeList);
            } else {
                //$this->flashMessageService('tx_trainingcaces.http_message.incorrect_login', 'errorStatus', 'ERROR');
                $pageUid = $this->settings['exam']['loginPage'];
                $this->redirectToPage($pageUid);
            }
        } else {
            //$this->flashMessageService('tx_trainingcaces.please_login', 'errorStatus', 'ERROR');
            $loginPage = $this->settings['exam']['loginPage'];
            $this->redirectToPage($loginPage);
        }
    }
    

    /**
     * action update
     *
     * @param Exam $exam
     * @return void
     */
    public function updateAction(Exam $exam)
    {
        $config = $this->getConfiguration();
        $storagePid = (int)$config['storagePid'];

        //$practiceTrainer = $_POST['tx_trainingcaces_trainingcaces']['exam']['practiceTrainer'];
        //if($practiceTrainer == null || $practiceTrainer == '') {
        //    $exam->setPracticeTrainer((int)1);
        //}

        // Set place
        $placeName = $_POST['place'];
        $placeName = trim($placeName);
        $place = $this->placeRepository->findOneByName($placeName);
        if (($place instanceof \T3Dev\Trainingcaces\Domain\Model\Place)) {
            $exam->setPlace($place);
            $this->placeRepository->update($place);
        } else {
            $place = GeneralUtility::makeInstance(Place::class);
            $place->setName($placeName);
            $place->setPid($storagePid);
            $this->placeRepository->add($place);
            $this->persistenceManager->persistAll();
            $exam->setPlace($place);
        }

        // Set required data that can be empty
        $postVar = $_POST['tx_trainingcaces_trainingcaces'];
        $examUid = $exam->getUid();

        if ($postVar['theoryTrainer'] != 0) {
            $theoryTrainer = $this->userRepository->findOneByUid((int)$postVar['theoryTrainer']);
            $exam->setTheoryTrainer($theoryTrainer);
        } else {
            $this->examRepository->updateExam($examUid, 'theory_trainer', '0');
        }
        if ($postVar['practiceTrainer'] != 0) {
            $practiceTrainer = $this->userRepository->findOneByUid((int)$postVar['practiceTrainer']);
            $exam->setPracticeTrainer($practiceTrainer);
        } else {
            $this->examRepository->updateExam($examUid, 'practice_trainer', '0');
        }
        if ($postVar['theoryTestDate'] != 0) {
            $theoryTestDate = \DateTime::createFromFormat('d/m/Y', $postVar['theoryTestDate']);
            $exam->setTheoryTestDate($theoryTestDate);
        } else {
            $this->examRepository->updateExam($examUid, 'theory_test_date', null);
        }
        if ($postVar['practiceTestDate'] != 0) {
            $practiceTestDate = \DateTime::createFromFormat('d/m/Y', $postVar['practiceTestDate']);
            $exam->setPracticeTestDate($practiceTestDate);
        } else {
            $this->examRepository->updateExam($examUid, 'practice_test_date', null);
        }

        if ($postVar['subCategory'] == 0) {
            $this->examRepository->setSubCat(0);
        } else {
            $subCat = $this->subcategoryRepository->findOneByUid((int)$postVar['subCategory']);
            $exam->setSubCat($subCat);
        }

        $this->flashMessageService('tx_trainingcaces.exam_updated', 'okStatus', 'OK');
        $this->examRepository->update($exam);
        $pageUid = $this->settings['exam']['listPage'];
        $this->redirectToPage($pageUid);
    }

    /**
     * action delete
     *
     * @param Exam $exam
     * @return void
     */
    public function deleteAction(Exam $exam)
    {
        $this->flashMessageService('tx_trainingcaces.exam_deleted', 'okStatus', 'OK');
        $this->examRepository->remove($exam);
        $pageUid = $this->settings['exam']['listPage'];
        $this->redirectToPage($pageUid);
    }
}
