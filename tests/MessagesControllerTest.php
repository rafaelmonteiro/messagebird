<?php

use PHPUnit\Framework\TestCase;
use Helpers\ServiceContainer;

class MessagesControllerTest extends TestCase
{
    private $container;
    private $messaging;
    private $message;

    public function setUp()
    {
        $this->container = new ServiceContainer();
        $this->messaging = $this->container->get('Messaging')->use('messageBird');
    }

    public function setDefaultMessage()
    {
        $this->messaging->composeMessage(
            'a sample message',
            [5511999999999,551188888888],
            'MessageBird'
        );                         

        $this->messaging->prepareMessage();
        $this->message = $this->messaging->Message;
    }

    public function testIfMessageHasMandatoryKeys()
    {        
        $this->setDefaultMessage();
        $this->assertObjectHasAttribute('body', $this->message);
        $this->assertObjectHasAttribute('originator', $this->message);
        $this->assertObjectHasAttribute('recipients', $this->message);
    }

    public function testIfMessageIsSet()
    {        
        $this->setDefaultMessage();
        $this->assertEquals('a sample message', $this->message->body);
    }

    public function testIfOriginatorIsSet()
    {        
        $this->setDefaultMessage();
        $this->assertEquals('MessageBird', $this->message->originator);
    }

    public function testIfRecipientsAreSet()
    {        
        $this->setDefaultMessage();
        $this->assertEquals([5511999999999,551188888888], $this->message->recipients);
    }

    /**
     * @expectedException  InvalidArgumentException
     */
    public function testIfNoMessageIsSet()
    {      
        $this->messaging->composeMessage(
            '',
            [5511999999999,551188888888],
            'MessageBird'
        );   
        $this->messaging->prepareMessage();
    }    

    /**
     * @expectedException  InvalidArgumentException
     */
    public function testIfRecipientIsEmpty()
    {      
        $this->messaging->composeMessage(
            'test message',
            [],
            'MessageBird'
        );   
        $this->messaging->prepareMessage();
    }    

    /**
     * @expectedException  TypeError
     */
    public function testIfRecipientContainerIsInvalid()
    {      
        $this->messaging->composeMessage(
            'test message',
            '',
            'MessageBird'
        );   
        $this->messaging->prepareMessage();
    }    

    /**
     * @expectedException  InvalidArgumentException
     */
    public function testIfRecipientIsNaN()
    {      
        $this->messaging->composeMessage(
            'test message',
            ['recipient1','recipient2'],
            'MessageBird'
        );   
        $this->messaging->prepareMessage();
    }

    public function testIfConcatenatedMessageIsSet()
    {      
        $this->messaging->composeMessage(
            'This is a message that surpasses 160 characters. It will be broken into several parts (after character 153) to be joined as one at defined recipients. It is called concatenated short messages.',
            [5511999999999,551188888888],
            'MessageBird'
        );   
        $this->messaging->prepareMessage();
        $this->message = $this->messaging->Message;

        $this->assertEquals(' is called concatenated short messages.', $this->message->body);
        $this->assertEquals([5511999999999,551188888888], $this->message->recipients);
        $this->assertEquals('MessageBird', $this->message->originator);
    }

}
