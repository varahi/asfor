<?php
namespace T3Dev\Trainingcaces\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Dmitry Vasilev <dmitry@t3dev.ru>
 */
class PracticeTrainerControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \T3Dev\Trainingcaces\Controller\PracticeTrainerController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\T3Dev\Trainingcaces\Controller\PracticeTrainerController::class)
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
    public function listActionFetchesAllPracticeTrainersFromRepositoryAndAssignsThemToView()
    {
        $allPracticeTrainers = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $practiceTrainerRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\PracticeTrainerRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $practiceTrainerRepository->expects(self::once())->method('findAll')->will(self::returnValue($allPracticeTrainers));
        $this->inject($this->subject, 'practiceTrainerRepository', $practiceTrainerRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('practiceTrainers', $allPracticeTrainers);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }
}
