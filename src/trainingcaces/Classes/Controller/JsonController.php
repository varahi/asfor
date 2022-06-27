<?php

declare(strict_types = 1);

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use T3Dev\Trainingcaces\Utility\UserUtility;
use T3Dev\Trainingcaces\Utility\FileUtility;
use T3Dev\Trainingcaces\Utility\ImageUtility;
use T3Dev\Trainingcaces\Domain\Model\FrontendUser;
use T3Dev\Trainingcaces\Domain\Model\Exam;
use T3Dev\Trainingcaces\Domain\Model\Type;
use T3Dev\Trainingcaces\Domain\Model\Category;
use T3Dev\Trainingcaces\Domain\Model\Place;
use T3Dev\Trainingcaces\Domain\Model\EnterpriseClient;
use T3Dev\Trainingcaces\Service\AccessService;
use TYPO3\CMS\Core\Core\Environment;
use T3Dev\Trainingcaces\Domain\Model\Subcategory;

/**
 * JsonController
 */
class JsonController extends Api\AbstractApiController
{

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $jsonInput;

    /**
     * @var string
     */
    protected $accessService;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager;

    /**
     * Inject Persistence Manager
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager
     */
    public function injectPersistenceManager(
        \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager
    ) {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @var \T3Dev\Trainingcaces\Utility\ImageUtility
     */
    protected $imageUtility;

    /**
     * Inject Image Utility
     *
     * @param \T3Dev\Trainingcaces\Utility\ImageUtility $imageUtility
     */
    public function injectImageUtility(
        \T3Dev\Trainingcaces\Utility\ImageUtility $imageUtility
    ) {
        $this->imageUtility = $imageUtility;
    }

    /**
     * Initialize methods and form data
     *
     * @return
     */
    public function __construct()
    {
        // Defining a request method
        $this->method = $_SERVER['REQUEST_METHOD'];
        //$this->method = $this->request->getMethod();

        $this->jsonInput = file_get_contents('php://input');
        //$this->jsonInput = '{"login":"dima","password":"12345"}';

        // Set property acccess service
        $this->accessService = GeneralUtility::makeInstance(AccessService::class);
    }

    /**
     * action authentification for trainers
     * JSON output http://asfor.localhost/api/auth
     *
     * @return void
     */
    public function authAction()
    {
        if ($this->method === 'GET') {
            $result = $this->accessService->accessDenied();
            return \GuzzleHttp\json_encode($result);
        }

        try {
            $data = \GuzzleHttp\json_decode($this->jsonInput);
        } catch (\Exception $e) {
            $this->jsonValidate($this->jsonInput);
        }

        if ($this->method === 'POST') {
            $result = $this->accessService->checkPassword($data);
            return \GuzzleHttp\json_encode($result);
        }
    }


    /**
     * action authentification for trainers
     * JSON output http://asfor.localhost/api/testers
     *
     * @return string
     */
    public function testersAction()
    {
        if ($this->method === 'GET') {
            $result = $this->accessService->accessDenied();
            return \GuzzleHttp\json_encode($result);
        }

        try {
            $data = \GuzzleHttp\json_decode($this->jsonInput);
        } catch (\Exception $e) {
            $this->jsonValidate($this->jsonInput);
        }

        if ($this->method === 'POST') {
            $result = $this->accessService->checkPassword($data);
            if ($result['success'] == 1) {
                $theoryTrainerGroup = $this->userGroupRepository->findByUid($this->settings['groups']['theoryTrainer']);
                $practiceTrainerGroup = $this->userGroupRepository->findByUid($this->settings['groups']['practiceTrainer']);
                $trainers = $this->userRepository->findByUsergroups($theoryTrainerGroup, $practiceTrainerGroup);
                $result = [
                    'success' => '1',
                    'message' => LocalizationUtility::translate('tx_trainingcaces.http_message.ok', 'trainingcaces'),
                ];

                foreach ($trainers as $key => $user) {
                    if ($user->getFirstName() && $user->getLastName()) {
                        $fullName = $user->getFirstName() .' '. $user->getLastName();
                    } elseif ($user->getFirstName()) {
                        $fullName = $user->getFirstName();
                    } elseif ($user->getLastName()) {
                        $fullName = $user->getLastName();
                    } else {
                        $fullName = '';
                    }

                    foreach ($user->getUsergroup() as $key => $usergroup) {
                        $role[$key] = $usergroup->getUid();

                        if ($role[$key] == $this->settings['groups']['theoryTrainer']) {
                            $theoryRole = '1';
                        } else {
                            $theoryRole = '0';
                        }
                        if ($role[$key] == $this->settings['groups']['practiceTrainer']) {
                            $practiceRole = '1';
                        } else {
                            $practiceRole = '0';
                        }

                        // If count of usergroups greater than two, then trainer is in the both groups
                        if (count($user->getUsergroup()) >= 2) {
                            $theoryRole = '1';
                            $practiceRole = '1';
                        }
                    }

                    if ($user->getOpenPassword()) {
                        $password = $user->getOpenPassword();
                    } else {
                        $password = $data->password;
                    }

                    $result['testers'][] = [
                        'username' => $user->getUsername(),
                        'password' => $password,
                        'full_username' => $fullName,
                        'theory' => $theoryRole,
                        'practice' => $practiceRole
                    ];
                }

                return \GuzzleHttp\json_encode($result);
            } else {
                $result = $this->accessService->accessDenied();
                return \GuzzleHttp\json_encode($result);
            }
        }
    }

    /**
     * action authentification for trainers
     * JSON output http://asfor.localhost/api/students
     *
     * @return string
     */
    public function studentsAction()
    {
        if ($this->method === 'GET') {
            $result = $this->accessService->accessDenied();
            return \GuzzleHttp\json_encode($result);
        }

        try {
            $data = \GuzzleHttp\json_decode($this->jsonInput);
        } catch (\Exception $e) {
            $this->jsonValidate($this->jsonInput);
        }

        if ($this->method === 'POST') {
            $result = $this->accessService->checkPassword($data);

            if ($result['success'] == true) {
                $candidateGroup = $this->userGroupRepository->findByUid($this->settings['groups']['candidate']);
                $users = $this->userRepository->findByUsergroupObj($candidateGroup);

                $result = [
                    'success' => '1',
                    'message' => LocalizationUtility::translate('tx_trainingcaces.http_message.ok', 'trainingcaces'),
                ];

                foreach ($users as $key => $user) {
                    foreach ($user->getUsergroup() as $usergroup) {
                        $role = $usergroup->getTitle();
                    }

                    //$fullName = $user->getFirstName() . ' ' . $user->getLastName();

                    if ($user->getDateOfBirth() !== null) {
                        $birthday = $user->getDateOfBirth()->format('d/m/Y');
                    } else {
                        $birthday = '';
                    }

                    $exam = $this->examRepository->findOneByCandidate($user);
                    //$username = $this->userRepository->findByUsername($user->getUsername());
                    $baseurl = $this->request->getBaseUri();

                    if ($user->getPhoto()) {
                        $userPhoto = $baseurl . $user->getPhoto()->getOriginalResource()->getPublicUrl();

                        /** @var ImageUtility $imageItility */
                        $imagesize = $this->imageUtility::getImageSize($userPhoto);

                        if ($imagesize['0'] > $imagesize['1']) {
                            $degrees = -90;
                            $fileName = $user->getPhoto()->getOriginalResource()->getoriginalFile()->getName();
                            $fileIdentifier = $user->getPhoto()->getOriginalResource()->getoriginalFile()->getIdentifier();
                            $pathSite = Environment::getPublicPath();
                            $fullFilePath = $pathSite . '/fileadmin' . $fileIdentifier;
                            $userPhoto = $this->imageUtility::rotateImage($fullFilePath, $fileName, $degrees);
                        }
                    } else {
                        $userPhoto = '';
                    }

                    $result['students'][] = [
                        'uid' => $user->getUid(),
                        //'username' => $user->getUsername(),
                        //'password' => $username->getPassword(),
                        'first_name' => $user->getFirstName(),
                        'last_name' => $user->getLastName(),
                        //'full_name' => $fullName,
                        'birthday' => $birthday,
                        'image' => $userPhoto,
                        'company' => $user->getCompany(),
                    ];

                    // Check if object not empty
                    $examObj = $this->nonEmptyObj($user->getExam());

                    if ($examObj == true) {
                        //$i = 0;
                        foreach ($user->getExam() as $exam) {
                            if ($exam->getTheoryTestDate() !== null) {
                                $theoryTestDate = $exam->getTheoryTestDate()->format('d/m/Y');
                            } else {
                                $theoryTestDate = '';
                            }

                            if ($exam->getPracticeTestDate() !== null) {
                                $getPracticeTestDate = $exam->getPracticeTestDate()->format('d/m/Y');
                            } else {
                                $getPracticeTestDate = '';
                            }

                            if ($exam->getType()) {
                                $type = $exam->getType()->getName();
                            } else {
                                $type = '';
                            }

                            if ($exam->getCategory()) {
                                $category = $exam->getCategory()->getName();
                            } else {
                                $category = '';
                            }

                            if ($exam->getPlace()) {
                                $place = $exam->getPlace()->getName();
                            } else {
                                $place = '';
                            }

                            if ($exam->getTheoryTrainer()) {
                                if ($exam->getTheoryTrainer()->getFirstName() && $exam->getTheoryTrainer()->getLastName()) {
                                    $theoryTester = $exam->getTheoryTrainer()->getFirstName() .' '. $exam->getTheoryTrainer()->getLastName();
                                } elseif ($exam->getTheoryTrainer()->getFirstName()) {
                                    $theoryTester = $exam->getTheoryTrainer()->getFirstName();
                                } elseif ($exam->getTheoryTrainer()->getLastName()) {
                                    $theoryTester = $exam->getTheoryTrainer()->getLastName();
                                } elseif ($exam->getTheoryTrainer()->getUsername()) {
                                    $theoryTester = $exam->getTheoryTrainer()->getUsername();
                                } else {
                                    $theoryTester = '';
                                }
                            }
                            if ($theoryTester == null) {
                                $theoryTester = '';
                            }

                            if ($exam->getPracticeTrainer()) {
                                if ($exam->getPracticeTrainer()->getFirstName() . ' ' . $exam->getPracticeTrainer()->getLastName()) {
                                    $practiceTrainer = $exam->getPracticeTrainer()->getFirstName() . ' ' . $exam->getPracticeTrainer()->getLastName();
                                } elseif ($exam->getPracticeTrainer()->getFirstName()) {
                                    $practiceTrainer = $exam->getPracticeTrainer()->getFirstName();
                                } elseif ($exam->getPracticeTrainer()->getLastName()) {
                                    $practiceTrainer = $exam->getPracticeTrainer()->getLastName();
                                } elseif ($exam->getPracticeTrainer()->getUsername()) {
                                    $practiceTrainer = $exam->getPracticeTrainer()->getUsername();
                                } else {
                                    $practiceTrainer = '';
                                }
                            }
                            if ($practiceTrainer == null) {
                                $practiceTrainer = '';
                            }

                            /*
                            if ($exam->getTheoryResult() && $exam->getPracticeResult()) {
                                $totalSumPractice = $exam->getTheoryResult() + $exam->getPracticeResult();
                            } else {
                                $totalSumPractice = '0';
                            }
                            */

                            if ($exam->getTheoryResultFile()) {
                                $theoryPdf = $baseurl . $exam->getTheoryResultFile()->getOriginalResource()->getPublicUrl();
                            } else {
                                $theoryPdf = '';
                            }

                            if ($exam->getPracticeResultFile()) {
                                $practicePdf = $baseurl . $exam->getPracticeResultFile()->getOriginalResource()->getPublicUrl();
                            } else {
                                $practicePdf = '';
                            }

                            if ($exam->getTheoryIsSent()) {
                                $theoryIsSent = $exam->getTheoryIsSent();
                            } else {
                                $theoryIsSent = '0';
                            }

                            if ($exam->getPracticeIsSent()) {
                                $practiceIsSent = $exam->getPracticeIsSent();
                            } else {
                                $practiceIsSent = '0';
                            }

                            if ($exam->getTheoryResult() !== '') {
                                $sumTheory = $exam->getTheoryResult();
                            } else {
                                $sumTheory = '0';
                            }

                            if ($exam->getTheoryStatus() !== '') {
                                $theoryStatus = $exam->getTheoryStatus();
                            } else {
                                $theoryStatus = '0';
                            }

                            if ($exam->getPracticeStatus() !== '') {
                                $practiceStatus = $exam->getPracticeStatus();
                            } else {
                                $practiceStatus = '0';
                            }

                            if ($exam->getPracticeResult() !== '') {
                                $sumPractice = $exam->getPracticeResult();
                            } else {
                                $sumPractice = '0';
                            }

                            if ($exam->getIsChoice() !== '') {
                                $isChoice = $exam->getIsChoice();
                            } else {
                                $isChoice = '0';
                            }

                            if ($exam->getIsPractice() !== '') {
                                $isPractice = $exam->getIsPractice();
                            } else {
                                $isPractice = '0';
                            }

                            if ($exam->getIsOption() !== '') {
                                $isOption = $exam->getIsOption();
                            } else {
                                $isOption = '0';
                            }

                            if ($exam->getSubCat() !== '') {
                                $subCat = $exam->getSubCat();
                            } else {
                                $subCat = '';
                            }

                            /*
                            if ($exam->getNextExam() !== null) {
                                $nextExam = $exam->getNextExam()->format('d/m/Y');
                            } else {
                                $nextExam = '';
                            }
                            */

                            $result['students'][$key]['exams'][] = [
                                'exam_uid' => $exam->getUid(),
                                'family' => $type,
                                'category' => $category,
                                'place' => $place,
                                'theory_tester' => $theoryTester,
                                'theory_date' => $theoryTestDate,
                                'theory_answers' => $exam->getTheoryAnswers(),
                                'sum_theory' => $sumTheory,
                                'theory_status' => $theoryStatus,
                                'theory_is_sent' => $theoryIsSent,
                                'practice_tester' => $practiceTrainer,
                                'practice_date' => $getPracticeTestDate,
                                'practice_answers' => $exam->getPracticeAnswers(),
                                'practice_status' =>$practiceStatus,
                                'sum_practice' => $sumPractice,
                                'theory_pdf' => $theoryPdf,
                                'practice_pdf' => $practicePdf,
                                'practice_is_sent' => $practiceIsSent,
                                'practice_column' => $exam->getNote(),
                                'is_choice' => $isChoice,
                                'is_practice' => $isPractice,
                                'is_option' => $isOption,
                                'sub_cat' => $subCat,
                                //'next_exam' => $nextExam
                            ];
                        }
                    } else {
                        $result['students'][$key]['exams'] = [];
                    }
                }

                return \GuzzleHttp\json_encode($result);
            } else {
                $result = $this->accessService->accessDenied();
                return \GuzzleHttp\json_encode($result);
            }
        }
    }

    /**
     * action update
     * JSON output http://asfor.localhost/api/update
     *
     * @Extbase\IgnoreValidation $user
     * @return string
     */
    public function updateUserAction()
    {
        if ($this->method === 'GET') {
            $result = $this->accessService->accessDenied();
            return \GuzzleHttp\json_encode($result);
        }

        // Json full examaple
        /*
        $this->jsonInput = '
        {
            "login": "jeanmarie",
            "password": "jeanmarie05",
            "uid": "25",
            "username": "dmitry",
            "userpassword": "12345",
            "first_name": "Dmitry",
            "last_name": "Vasilev",
            "birthday": "11/07/1974",
            "company": "Company 1",
            "image": "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",
                "exams": [
                {
                    "exam_uid": 55,
                    "family": "R489",
                    "category": "Cat. A1 – 1B",
                    "theory_tester": "Paul Jean",
                    "company": "Client 2",
                    "place": "My new client",
                    "theory_date": "27/08/2020",
                    "theory_is_sent": "1",
                    "theory_answers": "{\"choice\":[0,1,1,1,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]}",
                    "practice_answers": "{\"choice\":[1,1,1,1,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]}",
                    "sum_theory": "55",
                    "theory_status": "2",
                    "practice_tester": "Pierre Hermitte",
                    "practice_date": "15/11/2020",
                    "sum_practice" : 77,
                    "practice_status" : 2,
                    "theory_pdf": "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bWxuczpyZGY9J2h0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMnIHhtbG5zOmlYPSdodHRwOi8vbnMuYWRvYmUuY29tL2lYLzEuMC8nPgo8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0ndXVpZDo0YTIzZDZiNi1lZmE2LTExZTgtMDAwMC05ZmIzZWU2NTk2YjMnIHhtbG5zOnBkZj0naHR0cDovL25zLmFkb2JlLmNvbS9wZGYvMS4zLycgcGRmOlByb2R1Y2VyPSdHUEwgR2hvc3RzY3JpcHQgOS4yMCcvPgo8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0ndXVpZDo0YTIzZDZiNi1lZmE2LTExZTgtMDAwMC05ZmIzZWU2NTk2YjMnIHhtbG5zOnhtcD0naHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyc+PHhtcDpNb2RpZnlEYXRlPjIwMTgtMTEtMjFUMDc6MDM6MzErMDI6MDA8L3htcDpNb2RpZnlEYXRlPgo8eG1wOkNyZWF0ZURhdGU+MjAxOC0xMS0yMVQwNzowMzozMSswMjowMDwveG1wOkNyZWF0ZURhdGU+Cjx4bXA6Q3JlYXRvclRvb2w+VW5rbm93bkFwcGxpY2F0aW9uPC94bXA6Q3JlYXRvclRvb2w+PC9yZGY6RGVzY3JpcHRpb24+CjxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSd1dWlkOjRhMjNkNmI2LWVmYTYtMTFlOC0wMDAwLTlmYjNlZTY1OTZiMycgeG1sbnM6eGFwTU09J2h0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8nIHhhcE1NOkRvY3VtZW50SUQ9J3V1aWQ6NGEyM2Q2YjYtZWZhNi0xMWU4LTAwMDAtOWZiM2VlNjU5NmIzJy8+CjxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSd1dWlkOjRhMjNkNmI2LWVmYTYtMTFlOC0wMDAwLTlmYjNlZTY1OTZiMycgeG1sbnM6ZGM9J2h0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvJyBkYzpmb3JtYXQ9J2FwcGxpY2F0aW9uL3BkZic+PGRjOnRpdGxlPjxyZGY6QWx0PjxyZGY6bGkgeG1s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",
                    "practice_pdf": "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bWxuczpyZGY9J2h0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMnIHhtbG5zOmlYPSdodHRwOi8vbnMuYWRvYmUuY29tL2lYLzEuMC8nPgo8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0ndXVpZDo0YTIzZDZiNi1lZmE2LTExZTgtMDAwMC05ZmIzZWU2NTk2YjMnIHhtbG5zOnBkZj0naHR0cDovL25zLmFkb2JlLmNvbS9wZGYvMS4zLycgcGRmOlByb2R1Y2VyPSdHUEwgR2hvc3RzY3JpcHQgOS4yMCcvPgo8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0ndXVpZDo0YTIzZDZiNi1lZmE2LTExZTgtMDAwMC05ZmIzZWU2NTk2YjMnIHhtbG5zOnhtcD0naHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyc+PHhtcDpNb2RpZnlEYXRlPjIwMTgtMTEtMjFUMDc6MDM6MzErMDI6MDA8L3htcDpNb2RpZnlEYXRlPgo8eG1wOkNyZWF0ZURhdGU+MjAxOC0xMS0yMVQwNzowMzozMSswMjowMDwveG1wOkNyZWF0ZURhdGU+Cjx4bXA6Q3JlYXRvclRvb2w+VW5rbm93bkFwcGxpY2F0aW9uPC94bXA6Q3JlYXRvclRvb2w+PC9yZGY6RGVzY3JpcHRpb24+CjxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSd1dWlkOjRhMjNkNmI2LWVmYTYtMTFlOC0wMDAwLTlmYjNlZTY1OTZiMycgeG1sbnM6eGFwTU09J2h0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8nIHhhcE1NOkRvY3VtZW50SUQ9J3V1aWQ6NGEyM2Q2YjYtZWZhNi0xMWU4LTAwMDAtOWZiM2VlNjU5NmIzJy8+CjxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSd1dWlkOjRhMjNkNmI2LWVmYTYtMTFlOC0wMDAwLTlmYjNlZTY1OTZiMycgeG1sbnM6ZGM9J2h0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvJyBkYzpmb3JtYXQ9J2FwcGxpY2F0aW9uL3BkZic+PGRjOnRpdGxlPjxyZGY6QWx0PjxyZGY6bGkgeG1s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",
                    "practice_is_sent": "1",
                    "practice_column": "nb/nh/ph/vh",
                    "is_choice": "0",
                    "is_practice": "0",
                    "is_option": "1",
                    "next_exam": "111",
                    "sub_cat": "null"
                },

                {
                    "exam_uid": 3900,
                    "family": "R482",
                    "category": "Cat. A",
                    "theory_tester": "Paul Jean",
                    "company": "Home",
                    "place": "My new client",
                    "theory_date": "03/09/2020",
                    "theory_is_sent": "1",
                    "theory_answers": "{\"choice\":[0,1,1,1,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]}",
                    "practice_answers": "{\"choice\":[1,1,1,1,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]}",
                    "sum_theory": "66",
                    "theory_status": "2",
                    "practice_tester": "Pierre Hermitte",
                    "practice_date": "03/09/2020",
                    "sum_practice" : 88,
                    "practice_status" : 2,
                    "theory_pdf": "JVBERi0xLjUKJYCBgoMKMSAwIG9iago8PC9GaWx0ZXIvRmxhdGVEZWNvZGUvRmlyc3QgMTQxL04gMjAvTGVuZ3RoIDg0OC9UeXBlL09ialN0bT4+CnN0cmVhbQp4AZVVbW/iOBD+K/NtW1W9+CUvzmlVCcjCcl26CLjrnaJ88BIvGylglBip/fc347QUdku3SFEyjmfGjx/PMxbAQAIXKYTAYw4RSJlCDJFkkEAcKlAQpxGkoJgCziBVAjhHC0M4mXECHDPICF+YQ6YMeISmpIT4VTiPT5zgGDMkmINjqBISBJoqxi/mSxMGHz8G890397g1wWCcDe3GLdAWNJg/ts6sx5vvlrIzmAXZPTCaWdjROJvobTAuzcZV7jGgoICi/Ssz7bKpts42hIci+7o1fro/vbsfja5Geuf0urq5IQCL2U+JPj240dxpZ7r59wO8z3mSi0QVcZrHnBdJ6D8qyqVKIYriQin/p6C94PjM3UTHm5n+ezv8MrnqNZWur/u2LieLDjHNDqvaCBDKhxBa40AkfjRH2P+AZGEwdrqulr3NqjZILa2EEOBaJohMbz+bavXD4cGHSdDrprw9rPWqhRjxK79Uv28f8usYs1MknrGU3rHws3d6bV6D+rLNlx0eE04Oz6hKjVtC3zYPaQ9F8GmztGW1We1pu/58QNzC/r2p0MGAeC9pE+N0qZ0G0dXMVK9MS4VOA595gLO1Xb0HZHoeSJTeEcjsv+yvwaID+YzvnDKMVJ6GYZGwPGKsSEROtZekuRAClJSF6v74MsR6PVdU4vdoZwn1BnKboeZR7p1Jsn8yBXhqO/cB5sFlWxAHfNMJ4LGUlaYCI4piXDpJRRHMLOkTa3aqG1+XXdjMtHbXLE37JALqQ/R/r2jqZ/5wG7tEReTBNBsGC/Pgipubn7UjxaF2JH+ndkR4pB15oB15Ujtx6CNRO0J5xwPtHNN7WjYDu6NVgtuqbHPOuwrcE9k+NbtbcM3OBF+nE3TtT4I726x1faLxbbe1WRN2FnxtStNgKV8818clsr2qWtc8XvRK+81c/tJ7wqPekx7wJyJ1ij/FxSF/XIZ7/sh+nT8e4kVGoXjgQnjPAwKPm/5pAmdYHF2/nHF/dXUmXoovhfq27MWZvUn8/nZaajynQQ9fvfEcvuu6Na8e1sSWGQ4usj8F44pzwVmCjZhfMfGBsQ+XwaAx2lV287YXCqPcLU1zMZp+gdEP27qOI0j/EOxyD4kRJPYWpP8Bsoh9UgplbmRzdHJlYW0KZW5kb2JqCjIyIDAgb2JqCjw8L0xlbmd0aCAxNjIvRmlsdGVyL0ZsYXRlRGVjb2RlPj4Kc3RyZWFtCnicXY8xDoMwDEX3nMI3CLBVQix0YWhV0V4gOA7KgBOFMPT2TQK0Ui3Z0rf95G/ZD9eBbQT5CA6fFMFY1oFWtwUkmGi2LOoGtMV4qFJxUV7I/qb86+0J0gKZXd/VQnJsLqVT7ww6TatXSEHxTKKtUnStSdEJYv03PqDJ/Lbr7qhNVU2FOacZz17O04BbCMSxGC6GshHL9P3JO58pSCk+UhVVCwplbmRzdHJlYW0KZW5kb2JqCjIzIDAgb2JqCjw8L0xlbmd0aCAzMDAvRmlsdGVyL0ZsYXRlRGVjb2RlPj4Kc3RyZWFtCngBrVHBSsQwEI2sP+BJvM2xFWwzado0HsUV2Ytuia4gnlZbESvs+v/gS3Zbg1TwIIHmzbzpvDeTDcmMSfqzv9d93hjqPonD2XYhW7GSZCw+2xdq/5jaIGWyckizAgaxOqWPaSbuouTYuEMnDh6Ha93Thcsb9onM2pJcSzv7jD/JSIuGrn9MxHkqs6K2XJhEXIub9Axzcq10Iq7AsDKVAdOIWx9ZW2vl62aIlC6LCtEqwg2YJ7eAso6UK85kXSqou+dEHKTuDQUmKpCBgpu5d8OmVNC8FPdQOhHH4ihSWHhcAzxEpXfetqwUm4I9Aws0d7Sk/bb7AbxPgFdq8wYIT/rbGvXEGtmarNY87nI3WJBFO/6fdj+ecNzTYRi+0ozhZ9/jemXllZdf4PGMdQplbmRzdHJlYW0KZW5kb2JqCjI0IDAgb2JqCjw8L0xlbmd0aCAxNDQ2L0ZpbHRlci9GbGF0ZURlY29kZT4+CnN0cmVhbQp4nO1XX2xTVRj/zr3t2q3MdTClUMk9l0ObLd0suolzVri2vbVYme02kttBwu3Wji6BUB3MaZA0RuK4gOHVRJNpYrKYGE/xwcITvhgS3YP4giaShRhdAiTGwANGNr9z2y0bUZ+N2Tm393zf7/tzvj+357ZAAKARyiCDd3TyOFX17ROIfABAYKx0+OhPZz75FGnknScOH3ljDOzhnsZbtFjI5W8Mn7oM0DKL/K4iAk0/uK8DuNqQ31E8enyqpt8yI4yOHBvN1e2/BWi4djQ3VXK2Nyyhfi+CtPRaoXRm+NofyBu4Z1hekH8HWLoNMzivwiycxM+Ujby9xBEpOr52nlq0oHi/DHnpN2le7lm8CWkyD/+p4Uw60xj1dfgIPoZ3YAFz4LBkI2fgO9dNvIP8vXxr8QTkHYdQYxY+hFnpR60vk36lf9/LqZf2Jl9M6PFY9AVtz+7nI8/1Pdv7zK6nw090dbYHAzvYdsXX1uptafY0NbpdDU6HLBHo1FnCpDxockeQJZNdgmc5BHKrAJNThBJrdTg1bTW6VlNDzbGHNLWapraiSbw0ApGuTqozyufijFbJcMZA+nycZSm/Y9P7bNoRtJlmZFQVLajuK8YpJybVeWKyaOlmHP1VPE0xFis0dXVCpcmDpAcp3s5KFdK+m9iE1K73VSRwN4ttuRzQc3mezhh63K+qWRuDmO2LN8S4y/ZFx0XMcJZWOq9Y56peGDFDG/IsnztocDmHRpasW9a7vDXEO1icd7z5sw9TLvBOFtd5iKGz1MDKBoQ7A15GrXuAwbM7t9ciuTrSEPDeA0GKFFfKhPJlGjA2jBDzU1URy9mqBiPI8HLGqPEURvwXQQuHslwyheTKsuTR/UJSXpasmJtMFa3Szfo1WfTx8gjt6sTq21cAL5RTLgfNkdGiWHMFi8XjtboNGVyLI6Hl6rnqlZ1h1M+ZmMS4KEPG4GFW4m0sWlNAgIoejA8atkndjLfFOJijdSse1uMiLqpbZrwWoPDFMsYl6F6ar/RQ/xfd0ANZEQd/LIZNCeqWkR/jiunP4/M5Rg2/yrUsli/LjEJWdIl5ecc8bqfaO9pWmNtD2svKInNXwE0NyS9nRbcQoAm8sWgEBV5sl82KjkYj1CB+WFbDXeoaglrjBxk5EEsKkSxMY0m/mlVr419C8tdjcga4e5UvLwIrMdX2+cfQatoioA6qF+KrAlzj1FkPsO7t7+OURC3qG6OFW7QzuSySA/jNRUxCNzYkuuijHNLUYAWWZfgMaWlD5CZqbfc3NchSmWHD7nb9KRlaw9XkvSuyOrU8qeVmqUFL6LC6CKi1lwM+fBp+zXo39tTQBJ5TlpVgNGGZVq66VB5h1MusSipllXRTBGlgwatLl8/6eeJclnvNIukT/tnevMUGjQiWoUsc2PhOdOXvl8nBR9x3+x/82nxVIGuGJBC5CJ+BG96HJuS9oMEAmiacJ8AJkjbR6GhTWhxUaXb4FJdDVV4tblbeOqkq40VVmTlFZk6SmSJpcAYVpyOoPCJtUmRJVcISKR1TlQ0eJI+RsIe0Qpvy+qSqbPZ1K+EpEt5KwltIeJKEfUTAhbyqEEDlPAkDwWMwenFzK5mmfHvGYlNcG5iqNNFpPOH2T1UkEuXy46pK+MYUpIaifBPBdTDKpZgBKR4ZSPHG9AGjQsh7WX+qSi6sBvBkma4SGOKO6aqEy8bY8AGjSrYI4Wn/JSAEeMo8fT7L09t4PjVo8PK2LH9KEBe2ZWFiIhQKTYhhr/gJ1YBQbYiKlrGiZXkBf4m4YKvmccqNDnARcKBoz9yeORKe896Ye3Jnd6vaGlBb1bIMD8oSLIK88KevLC3YfVHX5/pcn+tzfa7P/8HEt594L9Z+k4Dzl7EK//zyoZbIPfC7bbjyVeFLsX7Tf8tzt/9+uflqo/jv3YgvTXv8Bcy9sskKZW5kc3RyZWFtCmVuZG9iagoyNSAwIG9iago8PC9MZW5ndGggMjE4L0ZpbHRlci9GbGF0ZURlY29kZT4+CnN0cmVhbQp4nF2QwY7CIBCG7zwFb1BpC7iJmYt78bBmo74A0qnhICVYD779tjM6hyXhS/jgJ5m/2R++DznNuvmtUzzjrMeUh4qP6Vkj6iveUlam1UOK8/tEjPdQVLP/CeXyKqiXBzjy+Rju2JzaLRnDmTgN+CghYg35hmq3WRbsxmWBwjz8uzYbTl1HeW4MCFsEUh6EXc/qC4SdI9X3IHSGlQWha1ltQegsqwBC50lZijA9B60Doe9Y0S9Mb2m+zyDrqGtvn5p0fNaKeaZyqby1tJRR+i9TWVN62eoPnDx4GQplbmRzdHJlYW0KZW5kb2JqCjI2IDAgb2JqCjw8L0xlbmd0aCAxMzQ0L1N1YnR5cGUvWE1ML1R5cGUvTWV0YWRhdGE+PgpzdHJlYW0KPD94cGFja2V0IGJlZ2luPSfvu78nIGlkPSdXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQnPz4KPD9hZG9iZS14YXAtZmlsdGVycyBlc2M9IkNSTEYiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSdhZG9iZTpuczptZXRhLycgeDp4bXB0az0nWE1QIHRvb2xraXQgMi45LjEtMTMsIGZyYW1ld29yayAxLjYnPgo8cmRmOlJERiB4bWxuczpyZGY9J2h0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMnIHhtbG5zOmlYPSdodHRwOi8vbnMuYWRvYmUuY29tL2lYLzEuMC8nPgo8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0ndXVpZDo0YTIzZDZiNi1lZmE2LTExZTgtMDAwMC05ZmIzZWU2NTk2YjMnIHhtbG5zOnBkZj0naHR0cDovL25zLmFkb2JlLmNvbS9wZGYvMS4zLycgcGRmOlByb2R1Y2VyPSdHUEwgR2hvc3RzY3JpcHQgOS4yMCcvPgo8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0ndXVpZDo0YTIzZDZiNi1lZmE2LTExZTgtMDAwMC05ZmIzZWU2NTk2YjMnIHhtbG5zOnhtcD0naHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyc+PHhtcDpNb2RpZnlEYXRlPjIwMTgtMTEtMjFUMDc6MDM6MzErMDI6MDA8L3htcDpNb2RpZnlEYXRlPgo8eG1wOkNyZWF0ZURhdGU+MjAxOC0xMS0yMVQwNzowMzozMSswMjowMDwveG1wOkNyZWF0ZURhdGU+Cjx4bXA6Q3JlYXRvclRvb2w+VW5rbm93bkFwcGxpY2F0aW9uPC94bXA6Q3JlYXRvclRvb2w+PC9yZGY6RGVzY3JpcHRpb24+CjxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSd1dWlkOjRhMjNkNmI2LWVmYTYtMTFlOC0wMDAwLTlmYjNlZTY1OTZiMycgeG1sbnM6eGFwTU09J2h0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8nIHhhcE1NOkRvY3VtZW50SUQ9J3V1aWQ6NGEyM2Q2YjYtZWZhNi0xMWU4LTAwMDAtOWZiM2VlNjU5NmIzJy8+CjxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSd1dWlkOjRhMjNkNmI2LWVmYTYtMTFlOC0wMDAwLTlmYjNlZTY1OTZiMycgeG1sbnM6ZGM9J2h0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvJyBkYzpmb3JtYXQ9J2FwcGxpY2F0aW9uL3BkZic+PGRjOnRpdGxlPjxyZGY6QWx0PjxyZGY6bGkgeG1s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",
                    "practice_pdf": "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bWxuczpyZGY9J2h0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMnIHhtbG5zOmlYPSdodHRwOi8vbnMuYWRvYmUuY29tL2lYLzEuMC8nPgo8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0ndXVpZDo0YTIzZDZiNi1lZmE2LTExZTgtMDAwMC05ZmIzZWU2NTk2YjMnIHhtbG5zOnBkZj0naHR0cDovL25zLmFkb2JlLmNvbS9wZGYvMS4zLycgcGRmOlByb2R1Y2VyPSdHUEwgR2hvc3RzY3JpcHQgOS4yMCcvPgo8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0ndXVpZDo0YTIzZDZiNi1lZmE2LTExZTgtMDAwMC05ZmIzZWU2NTk2YjMnIHhtbG5zOnhtcD0naHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyc+PHhtcDpNb2RpZnlEYXRlPjIwMTgtMTEtMjFUMDc6MDM6MzErMDI6MDA8L3htcDpNb2RpZnlEYXRlPgo8eG1wOkNyZWF0ZURhdGU+MjAxOC0xMS0yMVQwNzowMzozMSswMjowMDwveG1wOkNyZWF0ZURhdGU+Cjx4bXA6Q3JlYXRvclRvb2w+VW5rbm93bkFwcGxpY2F0aW9uPC94bXA6Q3JlYXRvclRvb2w+PC9yZGY6RGVzY3JpcHRpb24+CjxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSd1dWlkOjRhMjNkNmI2LWVmYTYtMTFlOC0wMDAwLTlmYjNlZTY1OTZiMycgeG1sbnM6eGFwTU09J2h0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8nIHhhcE1NOkRvY3VtZW50SUQ9J3V1aWQ6NGEyM2Q2YjYtZWZhNi0xMWU4LTAwMDAtOWZiM2VlNjU5NmIzJy8+CjxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSd1dWlkOjRhMjNkNmI2LWVmYTYtMTFlOC0wMDAwLTlmYjNlZTY1OTZiMycgeG1sbnM6ZGM9J2h0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvJyBkYzpmb3JtYXQ9J2FwcGxpY2F0aW9uL3BkZic+PGRjOnRpdGxlPjxyZGY6QWx0PjxyZGY6bGkgeG1s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",
                    "practice_is_sent": "1",
                    "practice_column": "nb/nh/ph/vh",
                    "is_choice": "1",
                    "is_practice": "0",
                    "is_option": "1",
                    "next_exam": "123",
                    "sub_cat": "Porte-Engin"
                }

            ]
        }
        ';
        */

        try {
            $data = \GuzzleHttp\json_decode($this->jsonInput);
        } catch (\Exception $e) {
            $this->jsonValidate($this->jsonInput);
        }

        if ($this->method === 'POST') {
            $result = $this->accessService->checkPassword($data);

            // Set pid for usergroup
            $config = $this->getConfiguration();
            $pid = (int)$config['userPid'];
            $usergroup = $this->settings['groups']['candidate'];

            $hashMethod = UserUtility::getHashMethod();

            if ($result['success'] == true) {
                if ($data->uid == 0) {
                    // Create a new user
                    /** @var FrontendUser $user */
                    $user = GeneralUtility::makeInstance(FrontendUser::class);
                    $this->setUserProperties($user, $data);

                    // Set username and password and convert userpass
                    //$currentDate = date("Ymd");
                    $username = $data->first_name .'_'. $data->last_name .'_'. time();
                    $username = strtolower($username);

                    // Set default password for user
                    $user->setUsername($username);
                    $user->setPassword($this->settings['user']['defaultPassword']);
                    UserUtility::convertPassword($user, $hashMethod);

                    // Additional settings for new user
                    $user->setPid($pid);
                    $user->setUsergroup($usergroup);

                    // Create, update and restore exams
                    $this->updateExam($user, $data);

                    // Upload image
                    if ($data->image) {
                        $this->uploadImage($data, $user);
                    }
                    $this->userRepository->add($user);
                } else {
                    // Update an existing user
                    $user = $this->userRepository->findByUid($data->uid);
                    if ($user) {
                        $this->setUserProperties($user, $data);

                        // Create, update and restore exams
                        $this->updateExam($user, $data);

                        // Upload image
                        if ($data->image) {
                            $this->uploadImage($data, $user);
                        }
                        $this->userRepository->update($user);
                    } else {

                        // Restore user
                        $this->userRepository->restoreUser($data->uid, $pid);
                        $this->persistenceManager->persistAll();
                        $result = [
                            'success' => '1',
                            //'message' => LocalizationUtility::translate('tx_trainingcaces.http_message.user_not_exist', 'trainingcaces')
                            'message' => LocalizationUtility::translate('tx_trainingcaces.http_message.ok', 'trainingcaces')
                        ];
                        return \GuzzleHttp\json_encode($result);
                    }
                }

                $this->persistenceManager->persistAll();

                return \GuzzleHttp\json_encode($result);
            } else {
                $result = $this->accessService->accessDenied();
                return \GuzzleHttp\json_encode($result);
            }
        }
    }

    private function restoreExam($data, $user)
    {

        // Set pid for usergroup
        $config = $this->getConfiguration();
        $pid = (int)$config['userPid'];

        foreach ($data->exams as $item) {
            $uid = $item->exam_uid;
            $this->examRepository->setRawValue($uid, $pid);
            $this->persistenceManager->persistAll();
        }
        $this->updateExam($user, $data);

        $result = [
            'success' => '1',
            'message' => LocalizationUtility::translate('tx_trainingcaces.http_message.ok', 'trainingcaces')
        ];

        return $result;
    }

    private function uploadImage($data, $user)
    {

        // Upload image
        if ($data->image !== '') {
            if ($user->getPhoto()) {
                // Remove image if alraedy exist
                $this->userRepository->deleteUserPhoto($user);
            }
            if ($data->image) {

                /** @var FileUtility $fileUtility */
                $fileUtility = GeneralUtility::makeInstance(FileUtility::class);
                $fileUtility->uploadFile($data, $user);
            }
        }
    }

    private function setUserProperties($user, $data)
    {

        // Set name
        if ($data->first_name) {
            $user->setFirstName($data->first_name);
        }
        if ($data->last_name) {
            $user->setLastName($data->last_name);
        }
        if ($data->first_name && $data->last_name) {
            $user->setName($data->first_name . ' ' . $data->last_name);
        }

        // Set password if it transfer
        if ($data->userpassword) {
            $hashMethod = UserUtility::getHashMethod();
            $user->setPassword($data->userpassword);
            UserUtility::convertPassword($user, $hashMethod);
        }

        // Set birthday
        $dateObj = $this->getDateObj($data->birthday);
        if ($dateObj instanceof \DateTime) {
            $user->setDateOfBirth($dateObj);
        }

        if ($data->company) {
            $user->setCompany($data->company);
        }
    }

    private function updateExam($user, $data)
    {
        // Set pid for exam
        $config = $this->getConfiguration();
        $pid = (int)$config['storagePid'];

        foreach ($data->exams as $key => $item) {
            if ($item->exam_uid == 0) {
                // Create new exam
                /** @var Exam $exam */
                $exam = GeneralUtility::makeInstance(Exam::class);
                $exam->setPid($pid);
                $exam->setCandidate($user);
                $this->setExamProperties($exam, $item);
                $this->examRepository->add($exam);
            } else {
                // Check if exam don't exist and has uid not null, Update an existing exam
                //$exam = $this->examRepository->findLast($user);
                $exam = $this->examRepository->findByUid($item->exam_uid);
                if (!is_null($exam)) {
                    $this->setExamProperties($exam, $item);
                    $this->examRepository->update($exam);
                } else {
                    //return false;
                    //$this->restoreExam($data, $user);
                }
            }
            //return true;
        }
    }

    private function setExamProperties($exam, $item)
    {

        // Set pid for usergroup
        $config = $this->getConfiguration();
        $storagePid = (int)$config['storagePid'];
        $userPid = (int)$config['userPid'];
        $hashMethod = UserUtility::getHashMethod();

        // Set type
        if ($item->family) {
            $typeObject = $this->typeRepository->findOneByName($item->family);
            if (is_object($typeObject)) {
                $exam->setType($typeObject);
                $this->typeRepository->update($typeObject);
            } else {
                $typeObject = GeneralUtility::makeInstance(Type::class);
                $typeObject->setName($item->family);
                $typeObject->setPid($storagePid);
                $this->typeRepository->add($typeObject);
            }
        }

        // Set category
        if ($item->category) {
            $categoryObject = $this->categoryRepository->findOneByName($item->category);
            if (is_object($categoryObject)) {
                $exam->setCategory($categoryObject);
                $this->categoryRepository->update($categoryObject);
            } else {
                $categoryObject = GeneralUtility::makeInstance(Category::class);
                $categoryObject->setName($item->category);
                $categoryObject->setPid($storagePid);
                $this->categoryRepository->add($categoryObject);
            }
        }

        // Set place
        if ($item->place) {
            $placeObject = $this->placeRepository->findOneByName($item->place);
            if (is_object($placeObject)) {
                $exam->setPlace($placeObject);
                $this->placeRepository->update($placeObject);
            } else {
                $placeObject = GeneralUtility::makeInstance(Place::class);
                $placeObject->setName($item->place);
                $placeObject->setPid($storagePid);
                $this->placeRepository->add($placeObject);
            }
        }

        // Set company
        if ($item->company) {
            $enterpriceClientObject = $this->enterpriseClientRepository->findOneByName($item->company);
            if (is_object($enterpriceClientObject)) {
                $exam->setEnterpriceClient($enterpriceClientObject);
                $this->enterpriseClientRepository->update($enterpriceClientObject);
            } else {
                $enterpriceClientObject = GeneralUtility::makeInstance(EnterpriseClient::class);
                $enterpriceClientObject->setName($item->company);
                $enterpriceClientObject->setPid($storagePid);
                $this->enterpriseClientRepository->add($enterpriceClientObject);
            }
        }

        // Theory values
        if ($item->theory_date) {
            $theoryDateObj = $this->getDateObj($item->theory_date);
            $exam->setTheoryTestDate($theoryDateObj);
        }

        // Set theory trainer
        if ($item->theory_tester) {
            if ($item->theory_tester == trim($item->theory_tester) && strpos($item->theory_tester, ' ') !== false) {
                // Tetster has first name and last name
                $theoryTrainerArr = explode(" ", $item->theory_tester);
                $theoryTrainerObject = $this->userRepository->findByFirstNameAndLastName($theoryTrainerArr[0], $theoryTrainerArr[1]);
                if (is_object($theoryTrainerObject)) {
                    $exam->setTheoryTrainer($theoryTrainerObject);
                    $this->userRepository->update($theoryTrainerObject);
                } else {
                    // Create new user trainer
                    $theoryTrainerObject = GeneralUtility::makeInstance(FrontendUser::class);
                    $username = strtolower($theoryTrainerArr[0] .'_'. $theoryTrainerArr[1]);
                    $theoryTrainerObject->setUsername($username);
                    $theoryTrainerObject->setFirstName($theoryTrainerArr[0]);
                    $theoryTrainerObject->setLastName($theoryTrainerArr[1]);
                    $this->setDefaultsForFrontEndUserInstance($theoryTrainerObject);
                    $this->userRepository->add($theoryTrainerObject);
                }
            } else {
                // Tester has username
                $theoryTrainerObject = $this->userRepository->findByUsername($item->theory_tester);
                if (is_object($theoryTrainerObject)) {
                    $exam->setTheoryTrainer($theoryTrainerObject);
                    $this->userRepository->update($theoryTrainerObject);
                } else {
                    $theoryTrainerObject = GeneralUtility::makeInstance(FrontendUser::class);
                    $theoryTrainerObject->setUsername($item->theory_tester);
                    $this->setDefaultsForFrontEndUserInstance($theoryTrainerObject);
                    $this->userRepository->add($theoryTrainerObject);
                }
            }
        }

        if ($item->theory_answers) {
            $exam->setTheoryAnswers($item->theory_answers);
        }

        if ($item->practice_answers) {
            $exam->setPracticeAnswers($item->practice_answers);
        }

        if ($item->sum_theory == '0') {
            $exam->setTheoryResult('0');
        }

        if ($item->sum_theory) {
            $exam->setTheoryResult($item->sum_theory);
        }

        if ($item->theory_status) {
            $exam->setTheoryStatus($item->theory_status);
        }

        // Set practice trainer
        if ($item->practice_tester) {
            if ($item->practice_tester == trim($item->practice_tester) && strpos($item->practice_tester, ' ') !== false) {
                // Tetster has first name and last name

                $practiceTrainerArr = explode(" ", $item->practice_tester);
                $practiceTrainerObject = $this->userRepository->findByFirstNameAndLastName($practiceTrainerArr[0], $practiceTrainerArr[1]);
                if (is_object($practiceTrainerObject)) {
                    $exam->setPracticeTrainer($practiceTrainerObject);
                    $this->userRepository->update($practiceTrainerObject);
                } else {
                    // Create new user trainer
                    $practiceTrainerObject = GeneralUtility::makeInstance(FrontendUser::class);
                    $username = strtolower($practiceTrainerArr[0] .'_'. $practiceTrainerArr[1]);
                    $practiceTrainerObject->setUsername($username);
                    $practiceTrainerObject->setFirstName($practiceTrainerArr[0]);
                    $practiceTrainerObject->setLastName($practiceTrainerArr[1]);
                    $this->setDefaultsForFrontEndUserInstance($practiceTrainerObject);
                    $this->userRepository->add($practiceTrainerObject);
                }
            } else {
                // Tester has username
                $practiceTrainerObject = $this->userRepository->findByUsername($item->practice_tester);
                if (is_object($practiceTrainerObject)) {
                    $exam->setPracticeTrainer($practiceTrainerObject);
                    $this->userRepository->update($practiceTrainerObject);
                } else {
                    $practiceTrainerObject = GeneralUtility::makeInstance(FrontendUser::class);
                    $practiceTrainerObject->setUsername($item->practice_tester);
                    $this->setDefaultsForFrontEndUserInstance($practiceTrainerObject);
                    $this->userRepository->add($practiceTrainerObject);
                }
            }
        }

        if ($item->sum_practice == 0) {
            $exam->setPracticeResult('0');
        }

        if ($item->sum_practice) {
            $exam->setPracticeResult($item->sum_practice);
        }

        if ($item->practice_status) {
            $exam->setPracticeStatus($item->practice_status);
        }

        if ($item->practice_date) {
            $practiceDateObj = $this->getDateObj($item->practice_date);
            $exam->setPracticeTestDate($practiceDateObj);
        }

        if (!empty($item->theory_pdf)) {
            if ($exam->getTheoryResultFile()) {
                // Remove pdf file if already exist
                $this->examRepository->deleteTheoryResultFile($exam);
            }
            if ($item->theory_pdf) {
                /** @var FileUtility $fileUtility */
                $fileUtility = GeneralUtility::makeInstance(FileUtility::class);
                $fileUtility->uploadPdfFile($item, $exam, 'setTheoryResultFile', '1', '0');
            }
        }

        if (!empty($item->practice_pdf)) {
            if ($exam->getPracticeResultFile()) {
                // Remove pdf file if already exist
                $this->examRepository->deletePracticeResultFile($exam);
            }
            if ($item->practice_pdf) {
                /** @var FileUtility $fileUtility */
                $fileUtility = GeneralUtility::makeInstance(FileUtility::class);
                $fileUtility->uploadPdfFile($item, $exam, 'setPracticeResultFile', '0', '1');
            }
        }

        if ($item->theory_is_sent) {
            $exam->setTheoryIsSent($item->theory_is_sent);
        }

        if ($item->practice_is_sent) {
            $exam->setPracticeIsSent($item->practice_is_sent);
        }

        if ($item->practice_column) {
            $exam->setNote($item->practice_column);
        }

        if ($item->is_choice == '0') {
            $exam->setIsChoice((int)0);
        }

        if ($item->is_choice) {
            $exam->setIsChoice((int)$item->is_choice);
        }

        if ($item->is_practice == '0') {
            $exam->setIsPractice((int)0);
        }

        if ($item->is_practice) {
            $exam->setIsPractice((int)$item->is_practice);
        }

        if ($item->is_option == '0') {
            $exam->setIsOption((int)0);
        }

        if ($item->is_option) {
            $exam->setIsOption((int)$item->is_option);
        }


        if (is_null($item->sub_cat) || $item->sub_cat == 'null' || $item->sub_cat == '' || $item->sub_cat == '{}') {
            // Do nothing
        } else {
            // Set subctegory
            if ($item->sub_cat) {
                $subcategoryObject = $this->subcategoryRepository->findOneByName($item->sub_cat);
                if ($subcategoryObject instanceof Subcategory) {
                    // Update existing exam subcategory
                    $exam->setSubCat($subcategoryObject);
                    $this->subcategoryRepository->update($subcategoryObject);
                } else {
                    // Create new subcategory
                    $subcategoryObject = GeneralUtility::makeInstance(Subcategory::class);
                    $subcategoryObject->setName($item->sub_cat);
                    $subcategoryObject->setPid($storagePid);
                    $this->subcategoryRepository->add($subcategoryObject);
                    $exam->setSubCat($subcategoryObject);
                }
            }
        }

        if ($item->next_exam) {
            $exam->setNextExam((int)$item->next_exam);
        }
    }

    private function setDefaultsForFrontEndUserInstance($object)
    {

        // Set pid for usergroup
        $config = $this->getConfiguration();
        $userPid = (int)$config['trainerPid'];
        $hashMethod = UserUtility::getHashMethod();

        $object->setPassword($this->settings['trainer']['defaultPassword']);
        $object->setEmail($this->settings['trainer']['defaultEmail']);
        $object->setPid($userPid);
        $object->setUsergroup($this->settings['groups']['practiceTrainer']);
        UserUtility::convertPassword($object, $hashMethod);
    }
}
