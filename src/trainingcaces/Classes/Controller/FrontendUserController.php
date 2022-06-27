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


use T3Dev\Trainingcaces\Domain\Model\FrontendUser;
use T3Dev\Trainingcaces\Domain\Repository\FrontendUserGroupRepository;
use T3Dev\Trainingcaces\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use T3Dev\Trainingcaces\Utility\ImageUtility;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;

/**
 * An frontend user controller
 */
class FrontendUserController extends AbstractController
{
    /**
     * @var string
     */
    protected $controller = '';

    /**
     * @var array
     */
    protected $ignoredActions = [];

    /**
     * @var Context
     */
    protected $context;

    /**
     * User repository
     *
     * @var FrontendUserRepository
     */
    protected $userRepository;

    /**
     * Usergroup repository
     *
     * @var FrontendUserGroupRepository
     */
    protected $userGroupRepository;

    /**
     * Set TypeConverter option for date time
     *
     * @return void
     */
    public function initializeUpdateExamsArrayAction()
    {
        if (isset($this->arguments['exam'])) {
            $this->arguments->getArgument('candidate')->getPropertyMappingConfiguration()->allowProperties('exam');
            $this->arguments->getArgument('candidate')->getPropertyMappingConfiguration()->forProperty('exam.*')->allowProperties('number');
            $this->arguments->getArgument('candidate')->getPropertyMappingConfiguration()->allowCreationForSubProperty('exam.*');
            $this->arguments->getArgument('candidate')->getPropertyMappingConfiguration()->allowModificationForSubProperty('exam.*');
        }
    }

    /**
     * Set TypeConverter option for date time
     *
     * @return void
     */
    public function initializeUpdateAction()
    {
        if (isset($this->arguments['candidate'])) {
            $this->arguments['candidate']->getPropertyMappingConfiguration()->forProperty('dateOfBirth')
                ->setTypeConverterOption(
                    'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                    DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                    'd/m/Y'
                );
        }
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
        $pageRenderer->addJsFile($extRelPath . "Resources/Public/JavaScript/jquery.clearsearch.js", 'text/javascript', false, false, '', true);
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
        $users = $this->userRepository->findAll();
        $this->view->assign('users', $users);
    }


    /**
     * action show
     *
     * @param FrontendUser $user
     * @return void
     */
    public function editAction(FrontendUser $user)
    {
        $currentLoggedInUser = $this->userRepository->findByUid(intval($GLOBALS['TSFE']->fe_user->user['uid']));
        $group = $this->accessService->getFrontendUserGroups();
        $userGroup = $this->settings['groups']['feEditor'];

        if (!is_null($currentLoggedInUser)) {
            if (in_array($userGroup, $group)) {
                $this->view->assign('candidate', $user);
            } else {
                $this->flashMessageService('tx_trainingcaces.http_message.incorrect_login', 'errorStatus', 'ERROR');
                $pageUid = $this->settings['exam']['listPage'];
                $this->redirectToPage($pageUid);
            }
        } else {
            $this->flashMessageService('tx_trainingcaces.please_login', 'errorStatus', 'ERROR');
            $loginPage = $this->settings['exam']['loginPage'];
            $this->redirectToPage($loginPage);
        }
    }

    /**
     * action show
     *
     * @param FrontendUser $user
     * @param string $degrees
     * @return void
     */
    public function rotateUserImageAction(FrontendUser $user, $degrees)
    {

        // Rotate image if it need
        if ($user->getPhoto()) {
            $baseurl = $this->request->getBaseUri();
            $userPhoto = $baseurl . $user->getPhoto()->getOriginalResource()->getPublicUrl();

            /** @var ImageUtility $imageItility */
            $imagesize = $this->imageUtility::getImageSize($userPhoto);
            $fileName = $user->getPhoto()->getOriginalResource()->getoriginalFile()->getName();
            $fileIdentifier = $user->getPhoto()->getOriginalResource()->getoriginalFile()->getIdentifier();
            $pathSite = Environment::getPublicPath();
            $fullFilePath = $pathSite . '/fileadmin' . $fileIdentifier;
            $userPhoto = $this->imageUtility::rotateImage($fullFilePath, $fileName, $degrees);
        }

        $this->redirect('show', 'FrontendUser', null, ['user' => $user]);
        return $userPhoto;
    }

    /**
     * action show
     *
     * @param FrontendUser $user
     * @return void
     */
    public function showAction(FrontendUser $user)
    {
        $currentLoggedInUser = $this->userRepository->findByUid(intval($GLOBALS['TSFE']->fe_user->user['uid']));
        $group = $this->accessService->getFrontendUserGroups();
        $userGroup = $this->settings['groups']['feEditor'];

        if (!is_null($currentLoggedInUser)) {
            if (in_array($userGroup, $group)) {
                $theoryTrainers = $this->userRepository->findByUsergroupList($this->settings['groups']['theoryTrainer']);
                $practiceTrainers = $this->userRepository->findByUsergroupList($this->settings['groups']['practiceTrainer']);
                $exams = $this->examRepository->findByCandidate($user);
                $this->loadSourcesDetailView();

                // Rotate image if it need
                if ($user->getPhoto()) {
                    $baseurl = $this->request->getBaseUri();
                    $userPhoto = $baseurl . $user->getPhoto()->getOriginalResource()->getPublicUrl();

                    /** @var ImageUtility $imageItility */
                    /*
                    $imagesize = $this->imageUtility::getImageSize($userPhoto);
                    if ($imagesize['0'] > $imagesize['1']) {
                        $degrees = -90;
                        $fileName = $user->getPhoto()->getOriginalResource()->getoriginalFile()->getName();
                        $fileIdentifier = $user->getPhoto()->getOriginalResource()->getoriginalFile()->getIdentifier();
                        $pathSite = Environment::getPublicPath();
                        $fullFilePath = $pathSite . '/fileadmin' . $fileIdentifier;
                        $userPhoto = $this->imageUtility::rotateImage($fullFilePath, $fileName, $degrees);
                    }
                    */
                }

                $this->view->assign('userPhoto', $userPhoto);
                $this->view->assign('candidate', $user);
                $this->view->assign('theoryTrainers', $theoryTrainers);
                $this->view->assign('practiceTrainers', $practiceTrainers);
                //$this->view->assign('exams', $exams->toArray());
                $this->view->assign('exams', $exams);

                foreach ($exams as $key => $exam) {
                    if ($exam->getTheoryTestDate() !== null) {
                        $examDate = $exam->getTheoryTestDate()->format('dmy');
                    }

                    // Set validate date
                    $this->setValidateDate($exam);
                    $examCategory = $exam->getCategory()->getShortName();
                    //$examCategory = substr($examCategory, '0', '3');
                    $examCandidate = $exam->getCandidate()->getLastName();
                    if ($exam->getNumber() == null) {
                        // Changed 10.06.2021 T3Dev
                        /*
                        if ($exam->getIsOption() == 0 && $exam->getIsPractice() == 0) {
                            $examNumber = $examDate . $examCategory .'ENGINS'. $examCandidate;
                        } elseif ($exam->getIsOption() == 1 && $exam->getIsPractice() == 0) {
                            $examNumber = $examDate . $examCategory .'TELE'. $examCandidate;
                        } else {
                            $examNumber = $examDate . $examCategory . $examCandidate;
                        }
                        */
                        $examNumber = $examDate . $examCategory . $examCandidate;
                        $examNumber = strtoupper($examNumber);
                        $exam->setNumber($examNumber);
                        $this->examRepository->update($exam);
                        $this->persistenceManager->persistAll();
                    }

                    $this->view->assign('examNumber', $examNumber);
                }

                //$this->view->assign('trainers', $trainers);
            } else {
                $this->flashMessageService('tx_trainingcaces.http_message.incorrect_login', 'errorStatus', 'ERROR');
                $pageUid = $this->settings['exam']['listPage'];
                $this->redirectToPage($pageUid);
            }
        } else {
            //$this->flashMessageService('tx_trainingcaces.please_login', 'errorStatus', 'ERROR');
            $loginPage = $this->settings['exam']['loginPage'];
            $this->redirectToPage($loginPage);
        }
    }

    /**
     * action update exams array
     *
     * @param FrontendUser $candidate
     * @param string $type
     * @return void
     */
    public function downloadPdfAction(FrontendUser $candidate, $type)
    {
        //\TYPO3\CMS\Core\Utility\DebugUtility::debug($candidate);
        $currentLoggedInUser = $this->userRepository->findByUid(intval($GLOBALS['TSFE']->fe_user->user['uid']));
        $group = $this->accessService->getFrontendUserGroups();
        $userGroup = $this->settings['groups']['feEditor'];

        if (!is_null($currentLoggedInUser)) {
            if (in_array($userGroup, $group)) {
                if ($type == $this->settings['type']['R482']) {
                    $templateName = 'R482';
                    $pdfFileName = $this->settings['pdf']['R482'];
                    $categories = $this->categoryRepository->findByType($this->settings['type']['R482']);
                }

                if ($type == $this->settings['type']['R486']) {
                    $templateName = 'R486';
                    $pdfFileName = $this->settings['pdf']['R486'];
                    $categories = $this->categoryRepository->findByType($this->settings['type']['R486']);
                }

                if ($type == $this->settings['type']['R489']) {
                    $templateName = 'R489';
                    $pdfFileName = $this->settings['pdf']['R489'];
                    $categories = $this->categoryRepository->findByType($this->settings['type']['R489']);
                }

                if ($type == $this->settings['type']['R490']) {
                    $templateName = 'R490';
                    $pdfFileName = $this->settings['pdf']['R490'];
                    $categories = $this->categoryRepository->findByType($this->settings['type']['R490']);
                }

                foreach ($candidate->getExam() as $exam) {
                    if ($exam->getTheoryTestDate() !== null) {
                        $examDate = $exam->getTheoryTestDate()->format('dmy');
                    }
                    $examCategory =  $exam->getCategory()->getSection();
                    $examCandidate =  $exam->getCandidate()->getLastName();
                    $examNumber = $examDate . $examCategory . $examCandidate;
                    $this->view->assign('examNumber', $examNumber);
                }

                $this->view->assign('categories', $categories);
                // Get user photo full path
                if ($candidate->getPhoto()) {
                    $userPhoto = $candidate->getPhoto()->getOriginalResource()->getOriginalFile()->getIdentifier();
                    $pathSite = Environment::getPublicPath();
                    $userPhotoFile = $pathSite . '/fileadmin/' . trim($userPhoto, '/');
                } else {
                    $userPhotoFile = null;
                }

                $this->getTemplateHtml(
                    'Pdf',
                    $templateName,
                    [
                        'candidate' => $candidate,
                        'categories' => $categories,
                        'userPhotoFile' => $userPhotoFile,
                        'examNumber' => $examNumber,
                        'type' => $type
                    ]
                );

                $confTargetFolder = $this->settings['upload']['pdfdir'];
                $filePathName = $confTargetFolder .'/'. $pdfFileName;
                $this->downloadFile($filePathName, $pdfFileName);
            }
        } else {
            $this->flashMessageService('tx_trainingcaces.please_login', 'errorStatus', 'ERROR');
            $loginPage = $this->settings['exam']['loginPage'];
            $this->redirectToPage($loginPage);
        }
    }

    /**
     * action update exams array
     *
     * @param FrontendUser $candidate
     * @return void
     */
    public function updateExamsArrayAction(FrontendUser $candidate)
    {
        if ($candidate->getExam()) {
            foreach ($_POST['exam'] as $key => $value) {
                $exam = $this->examRepository->findOneByUid($value['uid']);
                $theoryTrainer = $this->userRepository->findOneByUid($value['theoryTrainer']);
                $practiceTrainer = $this->userRepository->findOneByUid($value['practiceTrainer']);
                $sessionDate = $this->examGetDateObj($value['sessionDate']);
                $practiceTestDate = $this->examGetDateObj($value['practiceTestDate']);

                if ($value['number']) {
                    $exam->setNumber($value['number']);
                }
                if ($value['theoryTrainer']) {
                    $exam->setTheoryTrainer($theoryTrainer);
                }
                if ($value['practiceTrainer']) {
                    $exam->setPracticeTrainer($practiceTrainer);
                }
                if ($value['practiceTestDate']) {
                    $exam->setPracticeTestDate($practiceTestDate);
                }
                if ($value['sessionDate']) {
                    $exam->setSessionDate($sessionDate);
                }

                //if ($value['validateDate']) {
                //    $exam->setValidateDate($validateDate);
                //}

                // Set validate date
                $postValidateDate = $_POST['exam'][$key]['validateDate'];
                if ($postValidateDate == '') {
                    if ($exam->getPracticeTestDate()) {
                        $validateDate = clone $exam->getPracticeTestDate();
                        $type = $exam->getType()->getUid();
                        if ($type == $this->settings['type']['R482']) {
                            $validateDate->modify('+10 year - 1day'); // + 10 Years
                        } else {
                            $validateDate->modify('+5 year - 1day'); // +5 Years
                        }
                        $exam->setValidateDate($validateDate);
                        $this->examRepository->update($exam);
                        $this->persistenceManager->persistAll();
                    }
                } else {
                    $validateDate = $this->examGetDateObj($value['validateDate']);
                    $exam->setValidateDate($validateDate);
                    $this->examRepository->update($exam);
                    $this->persistenceManager->persistAll();
                }

                $exam->setNumber($value['number']);
                $this->userRepository->update($candidate);
            }

            $this->persistenceManager->persistAll();
            $this->flashMessageService('tx_trainingcaces.exam_list_updated', 'okStatus', 'OK');
            $pageUid = $this->settings['exam']['listPage'];
            $this->redirect('show', 'FrontendUser', null, ['user' => $candidate], $pageUid);
        }
    }

    /**
     * action update exams array
     *
     * @param FrontendUser $candidate
     * @return void
     */
    public function updateAction(FrontendUser $candidate)
    {
        $this->userRepository->update($candidate);
        $this->persistenceManager->persistAll();
        $this->flashMessageService('tx_trainingcaces.user_updated', 'okStatus', 'OK');
        $pageUid = $this->settings['exam']['listPage'];
        $this->redirect('list', 'Exam', null, null, $pageUid);
    }

    private function setValidateDate($exam)
    {
        // Preset validate date
        $type = $exam->getType()->getUid();
        if ($exam->getPracticeTestDate()) {
            $validateDate = clone $exam->getPracticeTestDate();
            if ($type == $this->settings['type']['R482']) {
                $validateDate->modify('+10 year - 1day'); // + 10 Years
            } else {
                $validateDate->modify('+5 year - 1day'); // +5 Years
            }
            // Set validate date if it null
            if ($exam->getValidateDate() == null) {
                $exam->setValidateDate($validateDate);
                $this->examRepository->update($exam);
                $this->persistenceManager->persistAll();
            }
        }
    }
}
