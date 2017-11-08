<?php

use PHPUnit\Framework\TestCase;
use Helpers\ServiceContainer;
use Services\BalanceMessageBird;

class BalancesControllerTest extends TestCase
{
    private $container;
    private $error;
    private $mock;

    public function setUp()
    {
        $this->container = new ServiceContainer();
        $this->error     = $this->container->get('ExceptionHandler');

        $this->mock      = $this->getMockBuilder(BalanceMessageBird::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->mock->expects($this->once())
                 ->method('getBalance')
                 ->with($this->equalTo($this->error))
                 ->willReturn([
                       'payment' => 'prepaid',
                       'type' => 'dollars',
                       'amount' => mt_rand(),
                    ]
                );
    }

    public function testIfBalanceHasKeys()
    {
        $balance = $this->mock->getBalance($this->error);
        $this->assertArrayHasKey('payment', $balance);
        $this->assertArrayHasKey('type', $balance);
        $this->assertArrayHasKey('amount', $balance);
    }

}
