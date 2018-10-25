<?

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CreateHandlerTest extends TestCase {
    const sourceUrl = 'https://www.krybrig.org/jejak.html?token=SeCReT';
    const stubCode1 = '1000';
    const stubCode2 = '2000';

    private $decoder;
    private $config;
    private $codegen;
    private $storage;

    private $handler;

    public function setup() {
        $this->decoder = $this->createMock(RequestDecoder::class);
        $this->config = $this->createMock(Config::class);
        $this->codegen = $this->createMock(CodeGenerator::class);
        $this->storage = $this->createMock(Storage::class);

        $this->handler = new CreateHandler(
            $this->decoder,
            $this->config,
            $this->codegen,
            $this->storage);
    }

    public function testExecuteShouldReturnResponseSuccess() {
        $request = new CreateRequest();
        $request->setUrl(CreateHandlerTest::sourceUrl);

        $stubCodeLength = 4;
        $stubBaseUrl = 'https://capc.us/';
        $this->config->method('getCodeLength')->willReturn($stubCodeLength);
        $this->config->method('getBaseUrl')->willReturn($stubBaseUrl);

        $this->codegen->method('generate')->with($stubCodeLength)->willReturn(CreateHandlerTest::stubCode1);

        $this->storage->method('getItemByUrl')->with($request->getUrl())->willReturn(NULL);
        $this->storage->expects($this->once())
            ->method('insertItem')
            ->with($this->callback(function($item) {
                return strcmp(CreateHandlerTest::stubCode1, $item->getCode()) === 0 &&
                    strcmp(CreateHandlerTest::sourceUrl, $item->getSourceUrl()) === 0;
            }))
            ->willReturn(true);

        $response = $this->handler->execute($request);

        $this->assertEquals(200, $response->getStatusCode());

        $body = $response->getBody();
        $this->assertEquals($request->getUrl(), $body->getSourceUrl());
        $this->assertEquals(CreateHandlerTest::stubCode1, $body->getCode());
        $this->assertEquals($stubBaseUrl . CreateHandlerTest::stubCode1, $body->getTargetUrl());
    }

    public function testExecuteShouldReturnPreviousItemWhenUrlAlreadyExist() {
        $request = new CreateRequest();
        $request->setUrl(CreateHandlerTest::sourceUrl);

        $stubCodeLength = 4;
        $stubBaseUrl = 'https://capc.us/';
        $this->config->method('getCodeLength')->willReturn($stubCodeLength);
        $this->config->method('getBaseUrl')->willReturn($stubBaseUrl);

        $item = new Item();
        $item->setCode("Previous");
        $item->setSourceUrl($request->getUrl());
        $item->setTargetUrl($stubBaseUrl . $item->getCode());

        $this->storage->expects($this->once())
            ->method('getItemByUrl')
            ->with($request->getUrl())
            ->willReturn($item);

        $response = $this->handler->execute($request);

        $this->assertEquals(200, $response->getStatusCode());

        $body = $response->getBody();
        $this->assertSame($item, $body);
    }

    public function testExecuteAndFailInsertStorageShouldReturnResponseFail() {
        $request = new CreateRequest();
        $request->setUrl(CreateHandlerTest::sourceUrl);

        $stubCodeLength = 4;
        $stubBaseUrl = 'https://capc.us/';
        $this->config->method('getCodeLength')->willReturn($stubCodeLength);
        $this->config->method('getBaseUrl')->willReturn($stubBaseUrl);
        $this->codegen->method('generate')->with($stubCodeLength)->willReturn(CreateHandlerTest::stubCode1);
        $this->storage->expects($this->once())->method('insertItem')->willReturn(false);

        $response = $this->handler->execute($request);

        $this->assertEquals(503, $response->getStatusCode());
    }

    private function createStorageException(int $code) : StorageException {
        return new StorageException($code, new Exception("Stub", $code));
    }

    public function testValidateShouldReturnTrue() {
        $this->config->method('getMaxUrlLength')->willReturn(128);
        $url = 'https://very.long.com/path/so/deep/in/the/bottom/of/the/sea/index.html';
        
        $request = $this->createRequest($url);
        $this->assertTrue($this->handler->validate($request));
    }

    public function testValidateShouldReturnFalseWhenUrlIsEmpty() {
        $this->config->method('getMaxUrlLength')->willReturn(128);
        $request = $this->createRequest('');
        $this->assertFalse($this->handler->validate($request));
    }

    public function testValidateShouldReturnFalseWhenUrlContainNonHttp() {
        $this->config->method('getMaxUrlLength')->willReturn(128);
        $url = 'tcp://unsupported.procotol.com';
        
        $request = $this->createRequest($url);
        $this->assertFalse($this->handler->validate($request));
    }

    public function testValidateShouldReturnFalseWhenUrlTooLong() {
        $this->config->method('getMaxUrlLength')->willReturn(10);
        $url = 'http://not.really.long.tho';
        
        $request = $this->createRequest($url);
        $this->assertFalse($this->handler->validate($request));
    }

    private function createRequest(string $url) {
        $request = $this->createMock(CreateRequest::class);
        $request->method('getId')->willReturn('abc');
        $request->method('getType')->willReturn(CreateHandler::TYPE);
        $request->method('getUrl')->willReturn($url);

        return $request;
    }
}