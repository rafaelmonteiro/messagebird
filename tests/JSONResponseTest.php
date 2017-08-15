<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers Helpers\JSONResponse;
 */
class JSONResponseTest extends TestCase
{
    public function testJSONResponseByObjectIsFormatted()
    {
        $this->test = new Helpers\JSONResponse;
        $this->assertEquals($this->test->_composeObject('some_information'), '{"data":"some_information"}');
    }

    public function testJSONResponseByStringIsFormatted()
    {
        $this->test = new Helpers\JSONResponse;
        $this->assertEquals($this->test->_composeString('"another_information"'), '{"data":"another_information"}');
    }
}
