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
        $body = '{ "url": "' . $url . '" }';

        $item = $this->decoder->decode($body);

        $this->assertEquals($url, $item->getUrl());
    }

    public function testDecodeShouldReturnNullItem() : void {
        $body = '{ "message": "unknown body" }';

        $item = $this->decoder->decode($body);

        $this->assertEquals('', $item->getUrl());
    }
} 