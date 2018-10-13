<?

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class RouterImplTest extends TestCase {
    private $router;

    public function setup() {
        $this->router = new RouterImpl();
    }

    public function testOnRequestShouldReturn400WhenTypeEmpty() {
        $result = $this->router->onRequest(array());

        $this->assertEquals(400, $result->getStatusCode());
    }

    public function testOnRequestShouldReturn404WhenNoHandler() {
        $body = json_decode('{ "type": "unknown.type" }', true);
        $result = $this->router->onRequest($body);
        $this->assertEquals(404, $result->getStatusCode());
    }

    public function testOnRequestShouldReturn500WhenExecutionFail() {
        $type = 'sample.type.1';
        $body = json_decode('{ "type": "' . $type . '" }', true);

        $request = $this->createMock(Request::class);

        $handler = $this->createMock(Handler::class);
        $handler->method('getType')->willReturn($type);
        $handler->method('decode')->with($body)->willReturn($request);
        $handler->method('validate')->with($request)->willReturn(true);
        $handler->method('execute')->with($request)->will($this->throwException(new Exception()));

        $this->router->addHandler($handler);

        $result = $this->router->onRequest($body);

        $this->assertEquals(500, $result->getStatusCode());
    }

    public function testOnRequestShouldDelegateToHandlerBasedOnRequestType() {
        $type = 'sample.type.1';
        $body = json_decode('{ "type": "' . $type . '" }', true);

        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);

        $handler = $this->createMock(Handler::class);
        $handler->method('getType')->willReturn($type);
        $handler->method('decode')->with($body)->willReturn($request);
        $handler->method('validate')->with($request)->willReturn(true);
        $handler->method('execute')->with($request)->willReturn($response);
        
        $this->router->addHandler($handler);

        $result = $this->router->onRequest($body);

        $this->assertSame($response, $result);
    }
}