<?

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class GetHandlerTest extends TestCase {
    private $decoder;
    private $storage;
    private $handler;

    public function setup() {
        $config = $this->createMock(Config::class);
        $config->method('getCodeLength')->willReturn(9);
        $config->method('getMaxAge')->willReturn(14);

        $this->decoder = $this->createMock(GetRequestDecoder::class);
        $this->storage = $this->createMock(Storage::class);
        $this->handler = new GetHandler($config, $this->decoder, $this->storage);
    }

    public function testValidateShouldReturnTrue() {
        $code = 'abc123yxz';
        $request = $this->createRequest($code);
        
        $this->assertTrue($this->handler->validate($request));
    }

    public function testValidateShouldReturnFalseWhenCodeEmpty() {
        $code = '';
        $request = $this->createRequest($code);
        
        $this->assertFalse($this->handler->validate($request));
    }

    public function testValidateShouldReturnFalseWhenCodeLengthMatch() {
        $code = 'abc123yxz00';
        $request = $this->createRequest($code);
        
        $this->assertFalse($this->handler->validate($request));
    }

    public function testValidateShouldReturnFalseWhenCodeNotAlphanumeric() {
        $code = 'abc!@#yxz';
        $request = $this->createRequest($code);
        
        $this->assertFalse($this->handler->validate($request));
    }

    public function testExecuteShouldReturnResponse() {
        $code = 'somecodes';
        $request = $this->createRequest($code);

        $item = $this->createMock(Item::class);
        $item->method('getCreateTime')->willReturn((new DateTime())->format(Item::TIMESTAMP_FORMAT));
        $this->storage->method('getItem')->with($code)->willReturn($item);

        $result = $this->handler->execute($request);

        $this->assertEquals(Response::CODE_REDIRECT, $result->getStatusCode());
        $this->assertSame($item, $result->getBody());
    }

    public function testExecuteShouldReturnResponseNotFoundWhenItemNull() {
        $code = 'somecodes';
        $request = $this->createRequest($code);

        $this->storage->method('getItem')->with($code)->willReturn(NULL);

        $result = $this->handler->execute($request);

        $this->assertSame(Response::CODE_NOT_FOUND, $result->getStatusCode());
    }

    public function testExecuteShouldReturnResponseGoneWhenItemExpired() {
        $code = 'somecodes';
        $request = $this->createRequest($code);

        $dt = new DateTime();
        $dt->sub(new DateInterval('P15D'));
        $createTime = $dt->format(Item::TIMESTAMP_FORMAT);
        $item = $this->createMock(Item::class);
        $item->method('getCreateTime')->willReturn($createTime);

        $this->storage->method('getItem')->with($code)->willReturn($item);

        $result = $this->handler->execute($request);

        $this->assertSame(Response::CODE_GONE, $result->getStatusCode());
    }

    private function createRequest(string $code) : GetRequest {
        $request = $this->createMock(GetRequest::class);
        $request->method('getType')->willReturn(GetHandler::TYPE);
        $request->method('getId')->willReturn('1');
        $request->method('getCode')->willReturn($code);

        return $request;
    }
}