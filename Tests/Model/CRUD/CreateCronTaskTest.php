<?php
namespace Trovit\CronManagerBundle\Tests\Model\CRUD;

use Trovit\CronManagerBundle\Entity\TblCronTask;
use Trovit\CronManagerBundle\Model\CRUD\CreateCronTask;
use Trovit\CronManagerBundle\Tests\Mocks\Entity\TblCronTaskMocks;
use Trovit\CronManagerBundle\Tests\Mocks\External\EntityManagerMocks;
use Trovit\CronManagerBundle\Tests\Mocks\Model\CommandValidatorMocks;

/**
 * CreateCronTaskTest
 *
 * @package Trovit\CronManagerBundle\Tests\Model\CRUD
 */
class CreateCronTaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManagerMocks
     */
    private $_entityManagerMocks;
    /**
     * @var CommandValidatorMocks
     */
    private $_commandValidatorMocks;
    /**
     * @var TblCronTaskMocks
     */
    private $_tblCronTaskMocks;

    public function setUp()
    {
        $this->_entityManagerMocks = new EntityManagerMocks($this);
        $this->_commandValidatorMocks = new CommandValidatorMocks($this);
        $this->_tblCronTaskMocks = new TblCronTaskMocks();
    }

    public function testCreateOk()
    {
        $cronTaskMock = $this->_tblCronTaskMocks->getCustomMock(
            $name = 'test',
            $description = 'test description',
            $command = 'cron:test',
            $cronExpression = '*/5 * * * *'
        );
        $sut = $this->getSut($cronTaskMock, $persistedAndFlushedNumCalls = 1, $commandExists = true);
        $sut->create($name, $description, $command, $cronExpression);
    }

    public function testCreateCommandDoesntExists()
    {
        $cronTaskMock = $this->_tblCronTaskMocks->getCustomMock(
            $name = 'test',
            $description = 'test description',
            $command = 'cron:test',
            $cronExpression = '*/5 * * * *'
        );
        $sut = $this->getSut($cronTaskMock, $persistedAndFlushedNumCalls = 0, $commandExists =  false);
        $this->setExpectedException('Trovit\CronManagerBundle\Exception\CommandNotExistsException');
        $sut->create($name, $description, $command, $cronExpression);
    }

    private function getSut(TblCronTask $cronTask, $persistedAndFlushedNumCalls, $commandExists)
    {
        return new CreateCronTask(
            $this->_entityManagerMocks->getCreateCronTaskTestMock($cronTask, $persistedAndFlushedNumCalls),
            $this->_commandValidatorMocks->getCreateCronTaskTestMock($commandExists)
        );
    }


}