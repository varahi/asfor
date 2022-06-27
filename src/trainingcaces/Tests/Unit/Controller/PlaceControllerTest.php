<?php
namespace T3Dev\Trainingcaces\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Dmitry Vasilev <dmitry@t3dev.ru>
 */
class PlaceControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \T3Dev\Trainingcaces\Controller\PlaceController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\T3Dev\Trainingcaces\Controller\PlaceController::class)
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
    public function listActionFetchesAllPlacesFromRepositoryAndAssignsThemToView()
    {
        $allPlaces = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $placeRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\PlaceRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $placeRepository->expects(self::once())->method('findAll')->will(self::returnValue($allPlaces));
        $this->inject($this->subject, 'placeRepository', $placeRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('places', $allPlaces);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenPlaceToView()
    {
        $place = new \T3Dev\Trainingcaces\Domain\Model\Place();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('place', $place);

        $this->subject->showAction($place);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenPlaceToPlaceRepository()
    {
        $place = new \T3Dev\Trainingcaces\Domain\Model\Place();

        $placeRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\PlaceRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $placeRepository->expects(self::once())->method('add')->with($place);
        $this->inject($this->subject, 'placeRepository', $placeRepository);

        $this->subject->createAction($place);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenPlaceToView()
    {
        $place = new \T3Dev\Trainingcaces\Domain\Model\Place();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('place', $place);

        $this->subject->editAction($place);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenPlaceInPlaceRepository()
    {
        $place = new \T3Dev\Trainingcaces\Domain\Model\Place();

        $placeRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\PlaceRepository::class)
            ->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $placeRepository->expects(self::once())->method('update')->with($place);
        $this->inject($this->subject, 'placeRepository', $placeRepository);

        $this->subject->updateAction($place);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenPlaceFromPlaceRepository()
    {
        $place = new \T3Dev\Trainingcaces\Domain\Model\Place();

        $placeRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\PlaceRepository::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $placeRepository->expects(self::once())->method('remove')->with($place);
        $this->inject($this->subject, 'placeRepository', $placeRepository);

        $this->subject->deleteAction($place);
    }
}
