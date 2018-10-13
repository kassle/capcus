<?

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CreateHandlerTest extends TestCase {
    private $decoder;
    private $config;
    private $codegen;

    private $handler;

    public function setup() {
        $this->decoder = $this->createMock(RequestDecoder::class);
        $this->config = $this->createMock(Config::class);
        $this->codegen = $this->createMock(CodeGenerator::class);

        $this->handler = new CreateHandler($this->decoder, $this->config, $this->codegen);
    }

    public function testExecuteShouldReturnResponse() {
        $request = new CreateRequest();
        $request->setUrl('https://www.krybrig.org/jejak.html?token=SeCReT');

        $stubCodeLength = 4;
        $stubBaseUrl = 'https://capc.us/';
        $this->config->method('getCodeLength')->willReturn($stubCodeLength);
        $this->config->method('getBaseUrl')->willReturn($stubBaseUrl);

        $stubCode = '1234';
        $this->codegen->method('generate')->with($stubCodeLength)->willReturn($stubCode);

        $response = $this->handler->execute($request);

        $this->assertEquals(200, $response->getStatusCode());

        $body = $response->getBody();
        $this->assertEquals($request->getUrl(), $body->getSourceUrl());
        $this->assertEquals($stubCode, $body->getCode());
        $this->assertEquals($stubBaseUrl . $stubCode, $body->getTargetUrl());
    }
}