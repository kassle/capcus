<?

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class GetRequestDecoderTest extends TestCase {
    private $decoder;

    public function setup() {
        $this->decoder = new GetRequestDecoder();
    }

    public function testDecodeShouldReturnRequest() {
        $code = 'wuchaka';
        $body = [ 'code' => $code ];

        $result = $this->decoder->decode($body);

        $this->assertEquals($code, $result->getCode());
    }

    public function testDecodeShouldReturnRequestWithEmptyCodeWhenNoCodeInBody() {
        $code = 'wuchaka';
        $body = [ 'nocode' => $code ];

        $result = $this->decoder->decode($body);

        $this->assertEquals('', $result->getCode());
    }
}