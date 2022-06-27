<?php
namespace T3Dev\Trainingcaces\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Dmitry Vasilev <dmitry@t3dev.ru>
 */
class ExamTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \T3Dev\Trainingcaces\Domain\Model\Exam
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \T3Dev\Trainingcaces\Domain\Model\Exam();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getSessionDateReturnsInitialValueForDateTime()
    {
        self::assertEquals(
            null,
            $this->subject->getSessionDate()
        );
    }

    /**
     * @test
     */
    public function setSessionDateForDateTimeSetsSessionDate()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setSessionDate($dateTimeFixture);

        self::assertAttributeEquals(
            $dateTimeFixture,
            'sessionDate',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTheoryTestDateReturnsInitialValueForDateTime()
    {
        self::assertEquals(
            null,
            $this->subject->getTheoryTestDate()
        );
    }

    /**
     * @test
     */
    public function setTheoryTestDateForDateTimeSetsTheoryTestDate()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setTheoryTestDate($dateTimeFixture);

        self::assertAttributeEquals(
            $dateTimeFixture,
            'theoryTestDate',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTheoryResultReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTheoryResult()
        );
    }

    /**
     * @test
     */
    public function setTheoryResultForStringSetsTheoryResult()
    {
        $this->subject->setTheoryResult('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'theoryResult',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTheoryResultFileReturnsInitialValueForFileReference()
    {
        self::assertEquals(
            null,
            $this->subject->getTheoryResultFile()
        );
    }

    /**
     * @test
     */
    public function setTheoryResultFileForFileReferenceSetsTheoryResultFile()
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setTheoryResultFile($fileReferenceFixture);

        self::assertAttributeEquals(
            $fileReferenceFixture,
            'theoryResultFile',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPracticeTestDateReturnsInitialValueForDateTime()
    {
        self::assertEquals(
            null,
            $this->subject->getPracticeTestDate()
        );
    }

    /**
     * @test
     */
    public function setPracticeTestDateForDateTimeSetsPracticeTestDate()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setPracticeTestDate($dateTimeFixture);

        self::assertAttributeEquals(
            $dateTimeFixture,
            'practiceTestDate',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPracticeResultReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getPracticeResult()
        );
    }

    /**
     * @test
     */
    public function setPracticeResultForStringSetsPracticeResult()
    {
        $this->subject->setPracticeResult('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'practiceResult',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPracticeResultFileReturnsInitialValueForFileReference()
    {
        self::assertEquals(
            null,
            $this->subject->getPracticeResultFile()
        );
    }

    /**
     * @test
     */
    public function setPracticeResultFileForFileReferenceSetsPracticeResultFile()
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setPracticeResultFile($fileReferenceFixture);

        self::assertAttributeEquals(
            $fileReferenceFixture,
            'practiceResultFile',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getSelectionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSelection()
        );
    }

    /**
     * @test
     */
    public function setSelectionForStringSetsSelection()
    {
        $this->subject->setSelection('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'selection',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getEnterpriceClientReturnsInitialValueForEnterpriseClient()
    {
        self::assertEquals(
            null,
            $this->subject->getEnterpriceClient()
        );
    }

    /**
     * @test
     */
    public function setEnterpriceClientForEnterpriseClientSetsEnterpriceClient()
    {
        $enterpriceClientFixture = new \T3Dev\Trainingcaces\Domain\Model\EnterpriseClient();
        $this->subject->setEnterpriceClient($enterpriceClientFixture);

        self::assertAttributeEquals(
            $enterpriceClientFixture,
            'enterpriceClient',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPlaceReturnsInitialValueForPlace()
    {
        self::assertEquals(
            null,
            $this->subject->getPlace()
        );
    }

    /**
     * @test
     */
    public function setPlaceForPlaceSetsPlace()
    {
        $placeFixture = new \T3Dev\Trainingcaces\Domain\Model\Place();
        $this->subject->setPlace($placeFixture);

        self::assertAttributeEquals(
            $placeFixture,
            'place',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCandidateReturnsInitialValueForCandidate()
    {
        self::assertEquals(
            null,
            $this->subject->getCandidate()
        );
    }

    /**
     * @test
     */
    public function setCandidateForCandidateSetsCandidate()
    {
        $candidateFixture = new \T3Dev\Trainingcaces\Domain\Model\Candidate();
        $this->subject->setCandidate($candidateFixture);

        self::assertAttributeEquals(
            $candidateFixture,
            'candidate',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTheoryTrainerReturnsInitialValueForTheoryTrainer()
    {
        self::assertEquals(
            null,
            $this->subject->getTheoryTrainer()
        );
    }

    /**
     * @test
     */
    public function setTheoryTrainerForTheoryTrainerSetsTheoryTrainer()
    {
        $theoryTrainerFixture = new \T3Dev\Trainingcaces\Domain\Model\TheoryTrainer();
        $this->subject->setTheoryTrainer($theoryTrainerFixture);

        self::assertAttributeEquals(
            $theoryTrainerFixture,
            'theoryTrainer',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPracticeTrainerReturnsInitialValueForPracticeTrainer()
    {
        self::assertEquals(
            null,
            $this->subject->getPracticeTrainer()
        );
    }

    /**
     * @test
     */
    public function setPracticeTrainerForPracticeTrainerSetsPracticeTrainer()
    {
        $practiceTrainerFixture = new \T3Dev\Trainingcaces\Domain\Model\PracticeTrainer();
        $this->subject->setPracticeTrainer($practiceTrainerFixture);

        self::assertAttributeEquals(
            $practiceTrainerFixture,
            'practiceTrainer',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTypeReturnsInitialValueForType()
    {
        self::assertEquals(
            null,
            $this->subject->getType()
        );
    }

    /**
     * @test
     */
    public function setTypeForTypeSetsType()
    {
        $typeFixture = new \T3Dev\Trainingcaces\Domain\Model\Type();
        $this->subject->setType($typeFixture);

        self::assertAttributeEquals(
            $typeFixture,
            'type',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCategoryReturnsInitialValueForCategory()
    {
        self::assertEquals(
            null,
            $this->subject->getCategory()
        );
    }

    /**
     * @test
     */
    public function setCategoryForCategorySetsCategory()
    {
        $categoryFixture = new \T3Dev\Trainingcaces\Domain\Model\Category();
        $this->subject->setCategory($categoryFixture);

        self::assertAttributeEquals(
            $categoryFixture,
            'category',
            $this->subject
        );
    }
}
