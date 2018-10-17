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
}