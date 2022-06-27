<?php
declare(strict_types=1);
namespace T3Dev\Trainingcaces\Domain\Model;

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

use T3Dev\Trainingcaces\Interfaces\FrontendUserInterface;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * An extended frontend user with more attributes
 */

// class FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser implements FrontendUserInterface

class FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
{
    const TABLE_NAME = 'fe_users';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Dev\Trainingcaces\Domain\Model\FrontendUserGroup>
     */
    protected $usergroup;

    /**
     * @var int
     */
    protected $crdate;

    /**
     * usergroup
     *
     * @var string
     */
    //protected $usergroup = '';

    /**
     * If the account is disabled or not
     *
     * @var bool
     */
    protected $disable = false;

    /**
     * Date on which the account was activated
     *
     * @var \DateTime|NULL
     */
    protected $activatedOn;

    /**
     *  virtual not stored in database
     *
     * @var string
     */
    protected $captcha = '';

    /**
     *  virtual not stored in database
     *
     * @var string
     */
    protected $passwordRepeat = '';

    /**
     *  virtual not stored in database
     *
     * @var string
     */
    protected $emailRepeat = '';

    /**
     * Pseudonym
     *
     * @var string
     */
    protected $pseudonym = '';

    /**
     * Gender 1 or 2 for mr or mrs
     *
     * @var int
     */
    protected $gender = 1;

    /**
     * dateOfBirth
     *
     * @var \DateTime
     */
    protected $dateOfBirth = null;

    /**
     * Day of date of birth
     *
     * @var int
     */
    protected $dateOfBirthDay = 0;

    /**
     * Month of date of birth
     *
     * @var int
     */
    protected $dateOfBirthMonth = 0;

    /**
     * Year of date of birth
     *
     * @var int
     */
    protected $dateOfBirthYear = 0;

    /**
     * Language
     *
     * @var string
     */
    protected $language = '';

    /**
     * Code of state/province
     *
     * @var string
     */
    protected $zone = '';

    /**
     * Timezone
     *
     * @var float
     */
    protected $timezone = 0;

    /**
     * Daylight saving time
     *
     * @var bool
     */
    protected $daylight = false;

    /**
     * Country with static info table code
     *
     * @var string
     */
    protected $staticInfoCountry = '';

    /**
     * Number of mobilephone
     *
     * @var string
     */
    protected $mobilephone = '';

    /**
     * General terms and conditions accepted flag
     *
     * @var bool
     */
    protected $gtc = false;

    /**
     * Privacy agreement accepted flag
     *
     * @var bool
     */
    protected $privacy = false;

    /**
     * Status
     *
     * @var int
     */
    protected $status = 0;

    /**
     * whether the user register by invitation
     *
     * @var bool
     */
    protected $byInvitation = false;

    /**
     * comment of user
     *
     * @var string
     */
    protected $comments = '';

    /**
     * firstName
     *
     * @var string
     */
    protected $firstName = '';

    /**
     * lastName
     *
     * @var string
     */
    protected $lastName = '';

    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * fullName
     *
     * @var string
     */
    protected $fullName = '';

    /**
     * image
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    //protected $image = null;

    /**
     * image
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @Extbase\ORM\Cascade ("remove")
     */
    protected $image = null;

    /**
     * photo
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @Extbase\ORM\Cascade ("remove")
     */
    protected $photo = 0;

    /**
     * exam
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Dev\Trainingcaces\Domain\Model\Exam>
     * @Extbase\ORM\Cascade ("remove")
     */
    protected $exam = null;

    /**
     * openPassword
     *
     * @var string
     */
    protected $openPassword = '';


    /**
     * __construct
     */
    public function __construct()
    {

        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->exam = new ObjectStorage();
        //$this->photo = new ObjectStorage();
    }

    /**
     * Adds a Exam
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\Exam $exam
     * @return void
     */
    public function addExam(\T3Dev\Trainingcaces\Domain\Model\Exam $exam)
    {
        $this->exam->attach($exam);
    }

    /**
     * Removes a Exam
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\Exam $examToRemove The Exam to be removed
     * @return void
     */
    public function removeExam(\T3Dev\Trainingcaces\Domain\Model\Exam $examToRemove)
    {
        $this->exam->detach($examToRemove);
    }

    /**
     * Returns the exam
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Dev\Trainingcaces\Domain\Model\Exam> $exam
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * Sets the exam
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Dev\Trainingcaces\Domain\Model\Exam> $exam
     * @return void
     */
    public function setExam(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Initializes the date of birth if related values are set by request to argument mapping
     */
    public function prepareDateOfBirth()
    {
        if ($this->dateOfBirthDay > 0 && $this->dateOfBirthMonth > 0 && $this->dateOfBirthYear > 0) {
            if ($this->dateOfBirth === null) {
                $this->dateOfBirth = new \DateTime();
            }
            $this->dateOfBirth->setDate($this->dateOfBirthYear, $this->dateOfBirthMonth, $this->dateOfBirthDay);
        }
    }

    public function setDisable(bool $disable)
    {
        $this->disable = ($disable ? true : false);
    }

    public function getDisable(): bool
    {
        return (bool) $this->disable;
    }

    /**
     * Getter for activatedOn
     *
     * @return \DateTime|NULL
     */
    public function getActivatedOn()
    {
        return $this->activatedOn;
    }

    public function setActivatedOn(\DateTime $activatedOn = null)
    {
        $this->activatedOn = $activatedOn;
    }

    public function setCaptcha(string $captcha)
    {
        $this->captcha = trim($captcha);
    }

    public function getCaptcha(): string
    {
        return $this->captcha;
    }

    public function setPasswordRepeat(string $passwordRepeat)
    {
        $this->passwordRepeat = trim($passwordRepeat);
    }

    public function getPasswordRepeat(): string
    {
        return $this->passwordRepeat;
    }

    public function setEmailRepeat(string $emailRepeat)
    {
        $this->emailRepeat = trim($emailRepeat);
    }

    public function getEmailRepeat(): string
    {
        return $this->emailRepeat;
    }

    public function removeImage()
    {
        $this->image = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    public function setPseudonym(string $pseudonym)
    {
        $this->pseudonym = $pseudonym;
    }

    public function getPseudonym(): string
    {
        return $this->pseudonym;
    }

    public function setGender(int $gender)
    {
        $this->gender = $gender;
    }

    public function getGender(): int
    {
        return $this->gender;
    }


    public function setDateOfBirth(\DateTime $dateOfBirth = null)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * Getter for dateOfBirth
     *
     * @return \DateTime|NULL
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }


    public function setDateOfBirthDay(int $day)
    {
        $this->dateOfBirthDay = $day;
        $this->prepareDateOfBirth();
    }

    public function getDateOfBirthDay(): int
    {
        $result = 1;

        if ($this->dateOfBirth instanceof \DateTime) {
            $result = $this->dateOfBirth->format('j');
        }

        return $result;
    }

    public function setDateOfBirthMonth(int $month)
    {
        $this->dateOfBirthMonth = $month;
        $this->prepareDateOfBirth();
    }

    public function getDateOfBirthMonth(): int
    {
        $result = 1;

        if ($this->dateOfBirth instanceof \DateTime) {
            $result = $this->dateOfBirth->format('n');
        }

        return $result;
    }

    public function setDateOfBirthYear(int $year)
    {
        $this->dateOfBirthYear = $year;
        $this->prepareDateOfBirth();
    }

    public function getDateOfBirthYear(): int
    {
        $result = 1970;

        if ($this->dateOfBirth instanceof \DateTime) {
            $result = $this->dateOfBirth->format('Y');
        }

        return $result;
    }

    public function setMobilephone(string $mobilephone)
    {
        $this->mobilephone = $mobilephone;
    }

    public function getMobilephone(): string
    {
        return $this->mobilephone;
    }

    public function setZone(string $zone)
    {
        $this->zone = $zone;
    }

    public function getZone(): string
    {
        return $this->zone;
    }

    public function setTimezone(int $timezone)
    {
        $this->timezone = ($timezone > 14 || $timezone < -12 ?
            $timezone / 10 :
            $timezone);
    }

    public function getTimezone(): int
    {
        return floor($this->timezone) != $this->timezone ?
            $this->timezone * 10 :
            $this->timezone;
    }

    public function setDaylight(bool $daylight)
    {
        $this->daylight = ($daylight ?
            true :
            false);
    }

    public function getDaylight(): bool
    {
        return $this->daylight ?
            true :
            false;
    }

    public function setStaticInfoCountry(string $staticInfoCountry)
    {
        $this->staticInfoCountry = $staticInfoCountry;
    }

    public function getStaticInfoCountry(): string
    {
        return $this->staticInfoCountry;
    }

    public function setGtc(bool $gtc)
    {
        $this->gtc = ($gtc ?
            true :
            false);
    }

    public function getGtc(): bool
    {
        return $this->gtc ?
            true :
            false;
    }

    public function setPrivacy(bool $privacy)
    {
        $this->privacy = ($privacy ?
            true :
            false);
    }

    public function getPrivacy(): bool
    {
        return $this->privacy ?
            true :
            false;
    }

    public function setByInvitation(bool $byInvitation)
    {
        $this->byInvitation = $byInvitation;
    }

    public function getByInvitation(): bool
    {
        return $this->byInvitation;
    }

    public function setComments(string $comments)
    {
        $this->comments = $comments;
    }

    public function getComments(): string
    {
        return $this->comments;
    }

    public function setLanguage(string $language)
    {
        $this->language = $language;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Returns the firstName
     *
     * @return string $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets the firstName
     *
     * @param string $firstName
     * @return void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Returns the lastName
     *
     * @return string $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets the lastName
     *
     * @param string $lastName
     * @return void
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the photo
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Sets the photo
     *
     * @param string $photo
     * @return void
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * Remove image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     * @return void
     */
    public function removePhoto(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->photo->detach($image);
    }


    /**
     * Returns the usergroup
     *
     * @return string $usergroup
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }

    /**
     * Sets the usergroup
     *
     * @param string $usergroup
     * @return void
     */
    public function setUsergroup($usergroup)
    {
        $this->usergroup = $usergroup;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return string
     */
    public function getOpenPassword(): string
    {
        return $this->openPassword;
    }

    /**
     * @param string $openPassword
     */
    public function setOpenPassword(string $openPassword): void
    {
        $this->openPassword = $openPassword;
    }

    /**
     * Returns the crdate
     *
     * @return int
     */
    public function getCrdate()
    {
        return $this->crdate;
    }
}
