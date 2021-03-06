<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers Helpers\Router;
 */
class RouterTest extends TestCase
{
    public function testRouterReturnsArraySuccessfulBalancesController()
    {
        $_SERVER['REQUEST_URI']     = '/api/user/balance';
        $_SERVER['REQUEST_METHOD']  = 'GET';

        $this->test = new Helpers\Router;

        $result = ["Controllers\BalancesController", "getBalance"];

        $this->assertEquals($this->test->useController(), $result);
    }

    public function testRouterReturnsArrayNotFoundControllerIfUriIsNotSet()
    {
        $_SERVER['REQUEST_URI']     = '/api/notExists';
        $_SERVER['REQUEST_METHOD']  = 'GET';

        $this->test = new Helpers\Router;

        $result = ["Controllers\NotFoundController", "NotFoundMethod"];

        $this->assertEquals($this->test->useController(), $result);
    }
}
