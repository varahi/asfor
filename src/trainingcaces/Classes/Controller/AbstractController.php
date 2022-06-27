<?php
namespace T3Dev\Trainingcaces\Controller;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use T3Dev\Trainingcaces\Service\AccessService;

/**
 * AbstractController
 */
abstract class AbstractController extends ActionController
{

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
     * examRepository
     *
     * @var \T3Dev\Trainingcaces\Domain\Repository\ExamRepository
     */
    protected $examRepository = null;

    /**
     * @param \T3Dev\Trainingcaces\Domain\Repository\ExamRepository $examRepository
     */
    public function injectExamRepository(\T3Dev\Trainingcaces\Domain\Repository\ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    /**
     * userRepository
     *
     * @var \T3Dev\Trainingcaces\Domain\Repository\FrontendUserRepository
     */
    protected $userRepository = null;

    /**
     * @param \T3Dev\Trainingcaces\Domain\Repository\ExamRepository $userRepository
     */
    public function injectFrontendUserRepository(\T3Dev\Trainingcaces\Domain\Repository\FrontendUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * userRepository
     *
     * @var \T3Dev\Trainingcaces\Domain\Repository\FrontendUserGroupRepository
     */
    protected $userGroupRepository = null;

    /**
     * @param \T3Dev\Trainingcaces\Domain\Repository\ExamRepository $userGroupRepository
     */
    public function injectFrontendUserGroupRepository(\T3Dev\Trainingcaces\Domain\Repository\FrontendUserGroupRepository $userGroupRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
    }

    /**
     * enterpriseClientRepository
     *
     * @var \T3Dev\Trainingcaces\Domain\Repository\EnterpriseClientRepository
     */
    protected $enterpriseClientRepository = null;

    /**
     * @param \T3Dev\Trainingcaces\Domain\Repository\EnterpriseClientRepository $enterpriseClientRepository
     */
    public function injectEnterpriseClientRepository(\T3Dev\Trainingcaces\Domain\Repository\EnterpriseClientRepository $enterpriseClientRepository)
    {
        $this->enterpriseClientRepository = $enterpriseClientRepository;
    }

    /**
     * placeRepository
     *
     * @var \T3Dev\Trainingcaces\Domain\Repository\PlaceRepository
     */
    protected $placeRepository = null;

    /**
     * @param \T3Dev\Trainingcaces\Domain\Repository\PlaceRepository $placeRepository
     */
    public function injectPlaceRepository(\T3Dev\Trainingcaces\Domain\Repository\PlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    /**
     * categoryRepository
     *
     * @var \T3Dev\Trainingcaces\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository = null;

    /**
     * @param \T3Dev\Trainingcaces\Domain\Repository\CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(\T3Dev\Trainingcaces\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * subcategoryRepository
     *
     * @var \T3Dev\Trainingcaces\Domain\Repository\SubcategoryRepository
     */
    protected $subcategoryRepository = null;

    /**
     * @param \T3Dev\Trainingcaces\Domain\Repository\SubcategoryRepository $subcategoryRepository
     */
    public function injectSubcategoryRepository(\T3Dev\Trainingcaces\Domain\Repository\SubcategoryRepository $subcategoryRepository)
    {
        $this->subcategoryRepository = $subcategoryRepository;
    }

    /**
     * typeRepository
     *
     * @var \T3Dev\Trainingcaces\Domain\Repository\TypeRepository
     */
    protected $typeRepository = null;

    /**
     * @param \T3Dev\Trainingcaces\Domain\Repository\TypeRepository $typeRepository
     */
    public function injectTypeRepository(\T3Dev\Trainingcaces\Domain\Repository\TypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    /**
     * accessControll
     *
     * @var AccessService
     *
     */
    protected $accessService = null;

    /**
     * @param AccessService $accessService
     */
    public function injectAccessControllService(AccessService $accessService)
    {
        $this->accessService = $accessService;
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
     * Build config array (get from $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['fritschproducts'])
     * if empty then set default values for configuration
     *
     * @return array Configuration
     */
    protected function getConfiguration()
    {
        if (empty($this->configuration)) {
            $configuration = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['trainingcaces'];
            if (is_string($configuration)) {
                $configuration = unserialize($configuration);
            }
            // set default values if no global conf found
            if (empty($configuration)) {
                $configuration = array(
                    'userPid' => 0,
                    'trainerPid' => 0,
                    'storagePid' => 0
                );
            }
            $this->configuration = $configuration;
        }
        return $this->configuration;
    }

    /**
     * @param \string $messageKey
     * @param \string $statusKey
     * @param \string $level
     */
    public function flashMessageService($messageKey, $statusKey, $level)
    {
        switch ($level) {
            case "NOTICE":
                $level = AbstractMessage::NOTICE;
                break;
            case "INFO":
                $level = AbstractMessage::INFO;
                break;
            case "OK":
                $level = AbstractMessage::OK;
                break;
            case "WARNING":
                $level = AbstractMessage::WARNING;
                break;
            case "ERROR":
                $level = AbstractMessage::ERROR;
                break;
        }

        $this->addFlashMessage(
            LocalizationUtility::translate($messageKey, 'trainingcaces'),
            LocalizationUtility::translate($statusKey, 'trainingcaces'),
            $level,
            true
        );
    }

    /**
     * method count product items
     *
     * @return int
     */
    public function countItems($items)
    {
        if ($items) {
            $numberOfResults = count($items);
            return $numberOfResults;
        }
    }

    // Retrieving data from the request body
    protected function getPostArray($method, $formData)
    {
        $postArray = [
            'method' => $method,
            'formData' => $formData
        ];
        return json_encode($postArray);
    }

    protected function setFullName($users)
    {
        foreach ($users as $item) {
            $firstName = $item->getFirstName();
            $lastName = $item->getLastName();
            $uid[] = $item->getUid();
            $fullName = $firstName . ' ' . $lastName;
            $item->setName($fullName);
        }
    }

    /**
     * redirect to page
     *
     * @return void
     */
    protected function redirectToPage($pageUid)
    {
        $uriBuilder = $this->uriBuilder;
        $uri = $uriBuilder->setTargetPageUid($pageUid)->setTargetPageType(0)->build();
        $this->redirectToURI($uri, $delay=0, $statusCode=200);
    }

    protected function examGetDateObj($data)
    {

        // Get correct timestamp
        // ToDo: method strtotime return incorrect result, fix it
        $timestamp = strtotime($data);
        if ($timestamp === false) {
            $timestamp = strtotime(str_replace('/', '-', $data));
        }

        $dateFormat = LocalizationUtility::translate('tx_trainingcaces.dateFormat', 'trainingcaces');
        $dateStr = date($dateFormat, $timestamp);
        $dateObj = new \DateTime($dateStr);

        return $dateObj;
    }

    protected function getDateObj($data)
    {

        // Get correct timestamp
        $timestamp = strtotime($data);

        //if ($timestamp === false) {
        //    $timestamp = strtotime(str_replace('/', '-', $data));
        //}

        $dateFormat = LocalizationUtility::translate('tx_trainingcaces.dateFormat', 'trainingcaces');
        $dateStr = date($dateFormat, $timestamp);
        $dateObj = new \DateTime($dateStr);

        if ($timestamp === false) {
            $timestampNew = strtotime(str_replace('/', '-', $data));
            $dateStr = date($dateFormat, $timestampNew);
            $dateObj = new \DateTime($dateStr);
        }

        return $dateObj;
    }

    /**
     * @param string $fileName
     * @return void
     */
    protected function downloadFile($fileName, $fileTitle)
    {
        $pathSite = Environment::getPublicPath();
        $file = $pathSite . '/' . trim($fileName, '/');

        if (is_file($file)) {
            $fileLen = filesize($file);
            $ext = strtolower(substr(strrchr($fileName, '.'), 1));

            switch ($ext) {
                case 'txt':
                    $cType = 'text/plain';
                    break;
                case 'pdf':
                    $cType = 'application/pdf';
                    break;
                case 'zip':
                    $cType = 'application/zip';
                    break;
                case 'doc':
                    $cType = 'application/msword';
                    break;
                case 'xls':
                    $cType = 'application/vnd.ms-excel';
                    break;
                case 'csv':
                    $cType = 'application/vnd.ms-excel';
                    break;
                case 'ppt':
                    $cType = 'application/vnd.ms-powerpoint';
                    break;
                case 'gif':
                    $cType = 'image/gif';
                    break;
                case 'png':
                    $cType = 'image/png';
                    break;
                case 'jpeg':
                case 'jpg':
                    $cType = 'image/jpg';
                    break;
                case 'mp3':
                    $cType = 'audio/mpeg';
                    break;
                case 'wav':
                    $cType = 'audio/x-wav';
                    break;
                case 'mpeg':
                case 'mpg':
                case 'mpe':
                    $cType = 'video/mpeg';
                    break;
                case 'mov':
                    $cType = 'video/quicktime';
                    break;
                case 'avi':
                    $cType = 'video/x-msvideo';
                    break;

                //forbidden filetypes
                case 'inc':
                case 'conf':
                case 'sql':
                case 'cgi':
                case 'htaccess':
                case 'php':
                case 'php3':
                case 'php4':
                case 'php5':
                    exit;

                case 'exe':
                default:
                    $cType = 'application/octet-stream';
                    break;
            }

            $downloadFileName = $fileTitle;

            $headers = array(
                'Pragma'                    => 'public',
                'Expires'                   => 0,
                'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
                'Content-Description'       => 'File Transfer',
                'Content-Type'              => $cType,
                'Content-Disposition'       => 'attachment; filename="'. $downloadFileName .'"',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Length'            => $fileLen
            );

            foreach ($headers as $header => $data) {
                $this->response->setHeader($header, $data);
            }

            $this->response->sendHeaders();
            @readfile($file);
        }
        exit;
    }

    /**
     * @param $controllerName
     * @param $templateName
     * @param array $variables
     * @return string
     */
    public function getTemplateHtml($controllerName, $templateName, array $variables = array())
    {
        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $tempView */
        $tempView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $templateRootPaths = $extbaseFrameworkConfiguration['view']['templateRootPaths'];
        foreach (array_reverse($templateRootPaths) as $templateRootPath) {
            $templatePathAndFilename = GeneralUtility::getFileAbsFileName($templateRootPath . $controllerName . '/' . $templateName . '.html');
            if (file_exists($templatePathAndFilename)) {
                break;
            }
        }
        $tempView->setTemplatePathAndFilename($templatePathAndFilename);
        // Set layout and partial root paths
        $tempView->setLayoutRootPaths($extbaseFrameworkConfiguration['view']['layoutRootPaths']);
        $tempView->setPartialRootPaths($extbaseFrameworkConfiguration['view']['partialRootPaths']);
        $tempView->assignMultiple($variables);
        $tempHtml = $tempView->render();
        return $tempHtml;
    }
}
