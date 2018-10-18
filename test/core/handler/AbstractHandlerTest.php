<?

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class AbstractHandlerTest extends TestCase {
    const TYPE = 'capcus.example.handler.type';

    private $type;
    private $decoder;
    private $handler;

    public function setup() {
        $this->decoder = $this->createMock(RequestDecoder::class);

        $this->handler = new AbstractHandlerImpl(AbstractHandlerTest::TYPE, $this->decoder);
    }

    public function testGetTypeShouldReturnPassedType() {
        $this->assertEquals(AbstractHandlerTest::TYPE, $this->handler->getType());
    }

    public function testDecodeShouldPassToDecoder() {
        $body = array();
        $request = new Request();
        $this->decoder->method('decode')->with($body)->willReturn($request);

        $output = $this->handler->decode($body);

        $this->assertSame($request, $output);
    }

    public function testValidateShouldDoBasicRequestValidation() {
        $request = $this->createMock(Request::class);
        $request->method('getType')->willReturn(AbstractHandlerTest::TYPE);
        $request->method('getId')->willReturn('request_id');

        $this->assertTrue($this->handler->validate($request));
    }

    public function testValidationShouldReturnFalseWhenTypeNotMatch() {
        $request = $this->createMock(Request::class);
        $request->method('getType')->willReturn('invalid.type');
        $request->method('getId')->willReturn('request_id');

        $this->assertFalse($this->handler->validate($request));
    }

    public function testValidationShouldReturnFalseWhenTypeEmpty() {
        $request = $this->createMock(Request::class);
        $request->method('getType')->willReturn('');
        $request->method('getId')->willReturn('request_id');

        $this->assertFalse($this->handler->validate($request));
    }

    public function testValidateShouldReturnFalseWhenIdEmpty() {
        $request = $this->createMock(Request::class);
        $request->method('getType')->willReturn(AbstractHandlerTest::TYPE);
        $request->method('getId')->willReturn('');

        $this->assertFalse($this->handler->validate($request));
    }

    public function testValidateShouldReturnFalseWhenTypeAndIdEmpty() {
        $request = $this->createMock(Request::class);
        $request->method('getType')->willReturn('');
        $request->method('getId')->willReturn('');

        $this->assertFalse($this->handler->validate($request));
    }
}

class AbstractHandlerImpl extends AbstractHandler {
    public function execute(Request $request) : Response {
        return new Response();
    }
}