<?php
namespace T3Dev\Trainingcaces\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Dmitry Vasilev <dmitry@t3dev.ru>
 */
class EnterpriseClientControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \T3Dev\Trainingcaces\Controller\EnterpriseClientController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\T3Dev\Trainingcaces\Controller\EnterpriseClientController::class)
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
    public function listActionFetchesAllEnterpriseClientsFromRepositoryAndAssignsThemToView()
    {
        $allEnterpriseClients = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $enterpriseClientRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\EnterpriseClientRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $enterpriseClientRepository->expects(self::once())->method('findAll')->will(self::returnValue($allEnterpriseClients));
        $this->inject($this->subject, 'enterpriseClientRepository', $enterpriseClientRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('enterpriseClients', $allEnterpriseClients);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenEnterpriseClientToView()
    {
        $enterpriseClient = new \T3Dev\Trainingcaces\Domain\Model\EnterpriseClient();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('enterpriseClient', $enterpriseClient);

        $this->subject->showAction($enterpriseClient);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenEnterpriseClientToEnterpriseClientRepository()
    {
        $enterpriseClient = new \T3Dev\Trainingcaces\Domain\Model\EnterpriseClient();

        $enterpriseClientRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\EnterpriseClientRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $enterpriseClientRepository->expects(self::once())->method('add')->with($enterpriseClient);
        $this->inject($this->subject, 'enterpriseClientRepository', $enterpriseClientRepository);

        $this->subject->createAction($enterpriseClient);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenEnterpriseClientToView()
    {
        $enterpriseClient = new \T3Dev\Trainingcaces\Domain\Model\EnterpriseClient();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('enterpriseClient', $enterpriseClient);

        $this->subject->editAction($enterpriseClient);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenEnterpriseClientInEnterpriseClientRepository()
    {
        $enterpriseClient = new \T3Dev\Trainingcaces\Domain\Model\EnterpriseClient();

        $enterpriseClientRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\EnterpriseClientRepository::class)
            ->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $enterpriseClientRepository->expects(self::once())->method('update')->with($enterpriseClient);
        $this->inject($this->subject, 'enterpriseClientRepository', $enterpriseClientRepository);

        $this->subject->updateAction($enterpriseClient);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenEnterpriseClientFromEnterpriseClientRepository()
    {
        $enterpriseClient = new \T3Dev\Trainingcaces\Domain\Model\EnterpriseClient();

        $enterpriseClientRepository = $this->getMockBuilder(\T3Dev\Trainingcaces\Domain\Repository\EnterpriseClientRepository::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $enterpriseClientRepository->expects(self::once())->method('remove')->with($enterpriseClient);
        $this->inject($this->subject, 'enterpriseClientRepository', $enterpriseClientRepository);

        $this->subject->deleteAction($enterpriseClient);
    }
}
