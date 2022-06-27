<?php
namespace T3Dev\Trainingcaces\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Dmitry Vasilev <dmitry@t3dev.ru>
 */
class TheoryTrainerControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \T3Dev\Trainingcaces\Controller\TheoryTrainerController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\T3Dev\Trainingcaces\Controller\TheoryTrainerController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllTheoryTrainersFromRepositoryAndAssignsThemToView()
    {
        $allTheoryTrainers = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $theoryTrainerRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\TheoryTrainerRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $theoryTrainerRepository->expects(self::once())->method('findAll')->will(self::returnValue($allTheoryTrainers));
        $this->inject($this->subject, 'theoryTrainerRepository', $theoryTrainerRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('theoryTrainers', $allTheoryTrainers);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }
}
