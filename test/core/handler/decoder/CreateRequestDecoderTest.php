<?

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CreateRequestDecoderTest extends TestCase {
    var $decoder;

    public function setup() : void {
        $this->decoder = new CreateRequestDecoderImpl();
    }

    public function testDecodeShouldReturnItem() : void {
        $url = 'https://www.krybrig.org/jejak.html?token=AbCdEf';
        $body = $this->toJsonArray('{ "url": "' . $url . '" }');

        $item = $this->decoder->decode($body);

        $this->assertEquals($url, $item->getUrl());
    }

    public function testDecodeShouldReturnNullItem() : void {
        $body = $this->toJsonArray('{ "message": "unknown body" }');

        $item = $this->decoder->decode($body);

        $this->assertEquals('', $item->getUrl());
    }

    private function toJsonArray(string $body) : array {
        return json_decode($body, true, 2);
    }
} 