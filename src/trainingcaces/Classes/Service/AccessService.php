<?php
namespace T3Dev\Trainingcaces\Service;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use T3Dev\Trainingcaces\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use T3Dev\Trainingcaces\Utility\ObjectUtility;
use TYPO3\CMS\Core\SingletonInterface;

class AccessService implements SingletonInterface
{


    /**
     * Initialize methods and form data
     *
     * @return
     */
    public function __construct()
    {
        $this->userRepository = ObjectUtility::getObjectManager()->get(FrontendUserRepository::class);
    }


    /**
     * action check password
     *
     * @return void
     */
    public function checkPassword($data)
    {
        // Old method get data from post array
        //$login = $post['login'];
        //$password = $post['password'];

        // Get data from json
        $login = $data->login;
        $password = $data->password;
        $username = $this->userRepository->findByUsername($login);

        if ($username !== null) {
            $passwordHash = $username->getPassword();
            //$passwordHashing = $GLOBALS['TYPO3_CONF_VARS']['FE']['passwordHashing']['className'];

            $mode = 'FE';
            $success = GeneralUtility::makeInstance(PasswordHashFactory::class)
                ->get($passwordHash, $mode) # or getDefaultHashInstance($mode)
                ->checkPassword($password, $passwordHash);

            if ($success == true) {
                $result = [
                    'success' => '1',
                    'message' => LocalizationUtility::translate('tx_trainingcaces.http_message.ok', 'trainingcaces')
                ];
            } else {
                $result = $this->accessDenied();
            }
        } else {
            $result = [
                'success' => '0',
                'error' => LocalizationUtility::translate('tx_trainingcaces.http_error.incorrect_login', 'trainingcaces'),
                'message' => LocalizationUtility::translate('tx_trainingcaces.http_message.user_not_found', 'trainingcaces'),
            ];
            header("HTTP/1.1 401 Unauthorized");
        }

        return $result;
    }

    /**
     * action access denied redirect
     *
     * @return void
     */
    public function accessDenied()
    {
        $result = [
            'success' => '0',
            'error' => LocalizationUtility::translate('tx_trainingcaces.http_error.incorrect_login', 'trainingcaces'),
            'message' => LocalizationUtility::translate('tx_trainingcaces.http_message.incorrect_login', 'trainingcaces'),
        ];
        header("HTTP/1.1 401 Unauthorized");

        return $result;
    }

    /**
     * Do we have a logged in feuser
     * @return boolean
     */
    public function hasLoggedInFrontendUser()
    {
        $context = GeneralUtility::makeInstance(Context::class);
        $userIsLoggedIn = $context->getPropertyFromAspect('frontend.user', 'isLoggedIn');

        return $userIsLoggedIn;
    }

    /**
     * @return array
     */
    public function getFrontendUserGroups()
    {
        if ($this->hasLoggedInFrontendUser()) {
            return $GLOBALS['TSFE']->fe_user->groupData['uid'];
        }
        return array();
    }

    /**
     * Get the uid of the current feuser
     * @return mixed
     */
    public function getFrontendUserUid()
    {
        if ($this->hasLoggedInFrontendUser() && !empty($GLOBALS['TSFE']->fe_user->user['uid'])) {
            return intval($GLOBALS['TSFE']->fe_user->user['uid']);
        }
        return null;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $user
     * @return boolean
     */
    public function isAccessAllowed(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $user)
    {
        return $this->getFrontendUserUid() === $user->getUid() ? true : false;
    }
}
