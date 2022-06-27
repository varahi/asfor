<?php
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
/**
 * Exam
 */
class Exam extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * number
     *
     * @var string
     */
    protected $number = '';

    /**
     * sessionDate
     *
     * @var \DateTime
     */
    protected $sessionDate = null;

    /**
     * sessionDate
     *
     * @var \DateTime
     */
    protected $validateDate = null;

    /**
     * theoryTestDate
     *
     * @var \DateTime
     */
    protected $theoryTestDate = null;

    /**
     * theoryResult
     *
     * @var string
     */
    protected $theoryResult = '';

    /**
     * theoryResultFile
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $theoryResultFile = null;

    /**
     * practiceTestDate
     *
     * @var \DateTime
     */
    protected $practiceTestDate = null;

    /**
     * practiceResult
     *
     * @var string
     */
    protected $practiceResult = '';

    /**
     * practiceResultFile
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $practiceResultFile = null;

    /**
     * selection
     *
     * @var string
     */
    protected $selection = '';

    /**
     * enterpriceClient
     *
     * @var \T3Dev\Trainingcaces\Domain\Model\EnterpriseClient
     */
    protected $enterpriceClient = null;

    /**
     * company
     *
     * @var string
     */
    protected $company = '';

    /**
     * place
     *
     * @var \T3Dev\Trainingcaces\Domain\Model\Place
     */
    protected $place = null;

    /**
     * candidate
     *
     * @var \T3Dev\Trainingcaces\Domain\Model\FrontendUser
     */
    protected $candidate = null;

    /**
     * theoryTrainer
     *
     * @var \T3Dev\Trainingcaces\Domain\Model\FrontendUser
     */
    protected $theoryTrainer = null;

    /**
     * practiceTrainer
     *
     * @var \T3Dev\Trainingcaces\Domain\Model\FrontendUser
     */
    protected $practiceTrainer = null;

    /**
     * type
     *
     * @var \T3Dev\Trainingcaces\Domain\Model\Type
     */
    protected $type = null;

    /**
     * category
     *
     * @var \T3Dev\Trainingcaces\Domain\Model\Category
     */
    protected $category = null;

    /**
     * theoryAnswers
     *
     * @var string
     */
    protected $theoryAnswers = '';

    /**
     * practiceAnswers
     *
     * @var string
     */
    protected $practiceAnswers = '';

    /**
     * theoryStatus
     *
     * @var string
     */
    protected $theoryStatus = '';

    /**
     * practiceStatus
     *
     * @var string
     */
    protected $practiceStatus = '';

    /**
     * theoryIsSent
     *
     * @var string
     */
    protected $theoryIsSent = '';

    /**
     * practiceIsSent
     *
     * @var string
     */
    protected $practiceIsSent = '';

    /**
     * note
     *
     * @var string
     */
    protected $note = '';

    /**
     * isPractice
     *
     * @var bool
     */
    protected $isPractice = false;

    /**
     * isChoice
     *
     * @var int
     */
    protected $isChoice = 0;

    /**
     * isOption
     *
     * @var bool
     */
    protected $isOption = false;

    /**
     * subCat
     *
     * @var \T3Dev\Trainingcaces\Domain\Model\Subcategory
     */
    protected $subCat = null;

    /**
     * nextExam
     *
     * @var int
     */
    protected $nextExam = 0;

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return void
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Returns the sessionDate
     *
     * @return \DateTime $sessionDate
     */
    public function getSessionDate()
    {
        return $this->sessionDate;
    }

    /**
     * Sets the sessionDate
     *
     * @param \DateTime $sessionDate
     * @return void
     */
    public function setSessionDate(\DateTime $sessionDate)
    {
        $this->sessionDate = $sessionDate;
    }

    /**
     * Returns the validateDate
     *
     * @return \DateTime $validateDate
     */
    public function getValidateDate()
    {
        return $this->validateDate;
    }

    /**
     * Sets the validateDate
     *
     * @param \DateTime $validateDate
     * @return void
     */
    public function setValidateDate(\DateTime $validateDate)
    {
        $this->validateDate = $validateDate;
    }

    /**
     * Returns the theoryTestDate
     *
     * @return \DateTime $theoryTestDate
     */
    public function getTheoryTestDate()
    {
        return $this->theoryTestDate;
    }

    /**
     * Sets the theoryTestDate
     *
     * @param \DateTime $theoryTestDate
     * @return void
     */
    public function setTheoryTestDate(\DateTime $theoryTestDate)
    {
        $this->theoryTestDate = $theoryTestDate;
    }

    /**
     * Returns the theoryResult
     *
     * @return string $theoryResult
     */
    public function getTheoryResult()
    {
        return $this->theoryResult;
    }

    /**
     * Sets the theoryResult
     *
     * @param string $theoryResult
     * @return void
     */
    public function setTheoryResult($theoryResult)
    {
        $this->theoryResult = $theoryResult;
    }

    /**
     * Returns the practiceTestDate
     *
     * @return \DateTime $practiceTestDate
     */
    public function getPracticeTestDate()
    {
        return $this->practiceTestDate;
    }

    /**
     * Sets the practiceTestDate
     *
     * @param \DateTime $practiceTestDate
     * @return void
     */
    public function setPracticeTestDate(\DateTime $practiceTestDate)
    {
        $this->practiceTestDate = $practiceTestDate;
    }

    /**
     * Returns the practiceResult
     *
     * @return string $practiceResult
     */
    public function getPracticeResult()
    {
        return $this->practiceResult;
    }

    /**
     * Sets the practiceResult
     *
     * @param string $practiceResult
     * @return void
     */
    public function setPracticeResult($practiceResult)
    {
        $this->practiceResult = $practiceResult;
    }

    /**
     * Returns the selection
     *
     * @return string $selection
     */
    public function getSelection()
    {
        return $this->selection;
    }

    /**
     * Sets the selection
     *
     * @param string $selection
     * @return void
     */
    public function setSelection($selection)
    {
        $this->selection = $selection;
    }

    /**
     * Returns the place
     *
     * @return \T3Dev\Trainingcaces\Domain\Model\Place $place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets the place
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\Place $place
     * @return void
     */
    public function setPlace(\T3Dev\Trainingcaces\Domain\Model\Place $place)
    {
        $this->place = $place;
    }

    /**
     * Returns the enterpriceClient
     *
     * @return \T3Dev\Trainingcaces\Domain\Model\EnterpriseClient enterpriceClient
     */
    public function getEnterpriceClient()
    {
        return $this->enterpriceClient;
    }

    /**
     * Sets the enterpriceClient
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\EnterpriseClient $enterpriceClient
     * @return void
     */
    public function setEnterpriceClient(\T3Dev\Trainingcaces\Domain\Model\EnterpriseClient $enterpriceClient)
    {
        $this->enterpriceClient = $enterpriceClient;
    }

    /**
     * Returns the theoryResultFile
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $theoryResultFile
     */
    public function getTheoryResultFile()
    {
        return $this->theoryResultFile;
    }

    /**
     * Sets the theoryResultFile
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $theoryResultFile
     * @return void
     */
    public function setTheoryResultFile(\TYPO3\CMS\Extbase\Domain\Model\FileReference $theoryResultFile)
    {
        $this->theoryResultFile = $theoryResultFile;
    }

    /**
     * Remove image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $theoryResultFile
     * @return void
     */
    public function removeTheoryResultFile(\TYPO3\CMS\Extbase\Domain\Model\FileReference $theoryResultFile)
    {
        //$this->theoryResultFile->detach($theoryResultFile);
    }

    /**
     * Returns the practiceResultFile
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $practiceResultFile
     */
    public function getPracticeResultFile()
    {
        return $this->practiceResultFile;
    }

    /**
     * Sets the practiceResultFile
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $practiceResultFile
     * @return void
     */
    public function setPracticeResultFile(\TYPO3\CMS\Extbase\Domain\Model\FileReference $practiceResultFile)
    {
        $this->practiceResultFile = $practiceResultFile;
    }

    /**
     * Returns the type
     *
     * @return \T3Dev\Trainingcaces\Domain\Model\Type $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\Type $type
     * @return void
     */
    public function setType(\T3Dev\Trainingcaces\Domain\Model\Type $type)
    {
        $this->type = $type;
    }

    /**
     * Returns the category
     *
     * @return \T3Dev\Trainingcaces\Domain\Model\Category $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\Category $category
     * @return void
     */
    public function setCategory(\T3Dev\Trainingcaces\Domain\Model\Category $category)
    {
        $this->category = $category;
    }

    /**
     * Returns the candidate
     *
     * @return \T3Dev\Trainingcaces\Domain\Model\FrontendUser candidate
     */
    public function getCandidate()
    {
        return $this->candidate;
    }

    /**
     * Sets the candidate
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\FrontendUser $candidate
     * @return void
     */
    public function setCandidate(\T3Dev\Trainingcaces\Domain\Model\FrontendUser $candidate)
    {
        $this->candidate = $candidate;
    }

    /**
     * Returns the theoryTrainer
     *
     * @return \T3Dev\Trainingcaces\Domain\Model\FrontendUser theoryTrainer
     */
    public function getTheoryTrainer()
    {
        return $this->theoryTrainer;
    }

    /**
     * Sets the theoryTrainer
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\FrontendUser $theoryTrainer
     * @return void
     */
    public function setTheoryTrainer(\T3Dev\Trainingcaces\Domain\Model\FrontendUser $theoryTrainer)
    {
        $this->theoryTrainer = $theoryTrainer;
    }

    /**
     * Returns the practiceTrainer
     *
     * @return \T3Dev\Trainingcaces\Domain\Model\FrontendUser practiceTrainer
     */
    public function getPracticeTrainer()
    {
        return $this->practiceTrainer;
    }

    /**
     * Sets the practiceTrainer
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\FrontendUser $practiceTrainer
     * @return void
     */
    public function setPracticeTrainer(\T3Dev\Trainingcaces\Domain\Model\FrontendUser $practiceTrainer)
    {
        $this->practiceTrainer = $practiceTrainer;
    }

    /**
     * @return string $theoryAnswers
     */
    public function getTheoryAnswers()
    {
        return $this->theoryAnswers;
    }

    /**
     * @param string $theoryAnswers
     * @return void
     */
    public function setTheoryAnswers($theoryAnswers)
    {
        $this->theoryAnswers = $theoryAnswers;
    }

    /**
     * @return string $practiceAnswers
     */
    public function getPracticeAnswers()
    {
        return $this->practiceAnswers;
    }

    /**
     * @param string $practiceAnswers
     * @return void
     */
    public function setPracticeAnswers($practiceAnswers)
    {
        $this->practiceAnswers = $practiceAnswers;
    }

    /**
     * Returns the theoryStatus
     *
     * @return string $theoryStatus
     */
    public function getTheoryStatus()
    {
        return $this->theoryStatus;
    }

    /**
     * Sets the theoryStatus
     *
     * @param string $theoryStatus
     * @return void
     */
    public function setTheoryStatus($theoryStatus)
    {
        $this->theoryStatus = $theoryStatus;
    }

    /**
     * Returns the practiceStatus
     *
     * @return string $practiceStatus
     */
    public function getPracticeStatus()
    {
        return $this->practiceStatus;
    }

    /**
     * Sets the practiceStatus
     *
     * @param string $practiceStatus
     * @return void
     */
    public function setPracticeStatus($practiceStatus)
    {
        $this->practiceStatus = $practiceStatus;
    }

    /**
     * @return string $theoryIsSent
     */
    public function getTheoryIsSent()
    {
        return $this->theoryIsSent;
    }

    /**
     * @param string $theoryIsSent
     * @return void
     */
    public function setTheoryIsSent($theoryIsSent)
    {
        $this->theoryIsSent = $theoryIsSent;
    }


    /**
     * @return string $practiceIsSent
     */
    public function getPracticeIsSent()
    {
        return $this->practiceIsSent;
    }

    /**
     * @param string $practiceIsSent
     * @return void
     */
    public function setPracticeIsSent($practiceIsSent)
    {
        $this->practiceIsSent = $practiceIsSent;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    /**
     * Returns the isPractice
     *
     * @return bool $isPractice
     */
    public function getIsPractice()
    {
        return $this->isPractice;
    }

    /**
     * Sets the isPractice
     *
     * @param bool $isPractice
     * @return void
     */
    public function setIsPractice($isPractice)
    {
        $this->isPractice = $isPractice;
    }

    /**
     * Returns the boolean state of isPractice
     *
     * @return bool
     */
    public function isIsPractice()
    {
        return $this->isPractice;
    }

    /**
     * @return int
     */
    public function getIsChoice(): int
    {
        return $this->isChoice;
    }

    /**
     * @param int $isChoice
     */
    public function setIsChoice(int $isChoice): void
    {
        $this->isChoice = $isChoice;
    }

    /**
     * Returns the isOption
     *
     * @return bool $isOption
     */
    public function getIsOption()
    {
        return $this->isOption;
    }

    /**
     * Sets the isOption
     *
     * @param bool $isOption
     * @return void
     */
    public function setIsOption($isOption)
    {
        $this->isOption = $isOption;
    }

    /**
     * Returns the boolean state of isOption
     *
     * @return bool
     */
    public function isIsOption()
    {
        return $this->isOption;
    }

    /**
     * @return int
     */
    public function getNextExam(): int
    {
        return $this->nextExam;
    }

    /**
     * @param int $nextExam
     */
    public function setNextExam(int $nextExam): void
    {
        $this->nextExam = $nextExam;
    }

    /**
     * Returns the subCat
     *
     * @return \T3Dev\Trainingcaces\Domain\Model\Subcategory $subCat
     */
    public function getSubCat()
    {
        return $this->subCat;
    }

    /**
     * Sets the subCat
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\Subcategory $subCat
     * @return void
     */
    public function setSubCat(\T3Dev\Trainingcaces\Domain\Model\Subcategory $subCat)
    {
        $this->subCat = $subCat;
    }
}
