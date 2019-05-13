<?php

namespace App\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerTest extends TestCase
{

//    public function testHomePage()
//    {
//        $this->assertEquals(true, 1);
//        $this->assertSame(true, 1);
//    }

    public function testIndex_withoutGetParameter()
    {
        $controller = new Controller();
        $request = new Request();

        $result = $controller->homePage($request);
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals('Hello world', $result->getContent());
    }

    public function testIndex_withValidGetParameter()
    {
        $controller = new Controller();
        $request = new Request();
        $request->query->set('name', 'Joe');

        $result = $controller->homePage($request);
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals('Hello Joe', $result->getContent());
    }

    public function testIndex_withInvalidGetParameter()
    {
        $controller = new Controller();
        $request = new Request();
        $request->query->set('name', '');

        $result = $controller->homePage($request);
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals('Hello world', $result->getContent());
    }
}
