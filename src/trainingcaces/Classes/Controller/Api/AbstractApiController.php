<?php
namespace T3Dev\Trainingcaces\Controller\Api;

use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * AbstractController
 */
abstract class AbstractApiController extends ActionController
{
    const SEPARATOR_TEMP_NAME = '-';

    /**
     * @var \TYPO3\CMS\Extbase\Mvc\View\JsonView
     */
    protected $view;

    /**
     * @var string
     */
    protected $resourceArgumentName;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Repository
     */
    protected $resourceRepository;

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
     * @var ResourceFactory
     */
    protected $resourceFactory;

    /**
     * Build config array (get from $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['inkluviva_icd10'])
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


    protected function jsonValidate($string)
    {
        // decode the JSON data
        $result = json_decode($string);

        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }

        if ($error !== '') {
            // throw the Exception or exit // or whatever :)
            exit($error);
        }

        // everything is OK
        return $result;
    }

    protected function getDateObj($data)
    {

        // Get correct timestamp

        /*
        $timestamp = strtotime($data);
        if ($timestamp === false) {
            $timestamp = strtotime(str_replace('/', '-', $data));
        }
        */

        $timestamp = strtotime(str_replace('/', '-', $data));
        $dateFormat = LocalizationUtility::translate('tx_trainingcaces.dateFormat', 'trainingcaces');
        $dateStr = date($dateFormat, $timestamp);
        $dateObj = new \DateTime($dateStr);
        return $dateObj;
    }

    protected function nonEmptyObj($obj)
    {
        foreach ($obj as $prop) {
            return true;
        }

        return false;
    }
}
