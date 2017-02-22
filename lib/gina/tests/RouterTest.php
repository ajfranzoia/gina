<?php

use PHPUnit\Framework\TestCase;
use Gina\Router;
use Gina\Request;

/**
 * @covers Router
 */
final class RouterTest extends TestCase
{

    public function setUp() {
        $this->router = new Router();
    }

    public function testSimpleParseRequest() {
        $request = Request::create(
            '/my-controller/index',
            'GET'
        );

        $route = $this->router->parseRequest($request);

        $this->assertEquals([
            'controller' => 'my-controller',
            'action' => 'index',
            'namedParams' => []
        ], $route);

        $request = Request::create(
            '/my_controller/view',
            'GET'
        );

        $route = $this->router->parseRequest($request);

        $this->assertEquals([
            'controller' => 'my_controller',
            'action' => 'view',
            'namedParams' => []
        ], $route);
    }

    public function testParseRequestWithParams() {
        $request = Request::create(
            '/my-controller/process/active/123',
            'POST'
        );

        $route = $this->router->parseRequest($request);

        $this->assertEquals([
            'controller' => 'my-controller',
            'action' => 'process',
            'namedParams' => ['active', '123']
        ], $route);
    }

}
