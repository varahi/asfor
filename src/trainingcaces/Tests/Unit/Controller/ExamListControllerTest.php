<?php
namespace T3Dev\Trainingcaces\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Dmitry Vasilev <dmitry@t3dev.ru>
 */
class ExamControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \T3Dev\Trainingcaces\Controller\ExamController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\T3Dev\Trainingcaces\Controller\ExamController::class)
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
    public function listActionFetchesAllExamsFromRepositoryAndAssignsThemToView()
    {
        $allExams = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $examRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\ExamRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $examRepository->expects(self::once())->method('findAll')->will(self::returnValue($allExams));
        $this->inject($this->subject, 'examRepository', $examRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('exams', $allExams);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenExamToView()
    {
        $exam = new \T3Dev\Trainingcaces\Domain\Model\Exam();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('exam', $exam);

        $this->subject->showAction($exam);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenExamToExamRepository()
    {
        $exam = new \T3Dev\Trainingcaces\Domain\Model\Exam();

        $examRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\ExamRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $examRepository->expects(self::once())->method('add')->with($exam);
        $this->inject($this->subject, 'examRepository', $examRepository);

        $this->subject->createAction($exam);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenExamToView()
    {
        $exam = new \T3Dev\Trainingcaces\Domain\Model\Exam();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('exam', $exam);

        $this->subject->editAction($exam);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenExamInExamRepository()
    {
        $exam = new \T3Dev\Trainingcaces\Domain\Model\Exam();

        $examRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\ExamRepository::class)
            ->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $examRepository->expects(self::once())->method('update')->with($exam);
        $this->inject($this->subject, 'examRepository', $examRepository);

        $this->subject->updateAction($exam);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenExamFromExamRepository()
    {
        $exam = new \T3Dev\Trainingcaces\Domain\Model\Exam();

        $examRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\ExamRepository::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $examRepository->expects(self::once())->method('remove')->with($exam);
        $this->inject($this->subject, 'examRepository', $examRepository);

        $this->subject->deleteAction($exam);
    }
}
