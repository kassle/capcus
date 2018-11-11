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
        $this->assertEquals(CreateRequestDecoderImpl::DEFAULT_OWNER, $item->getOwner());
    }

    public function testDecodeWithOwnerShouldReturnItem() : void {
        $url = 'https://www.krybrig.org/jejak.html?token=AbCdEf';
        $owner = 'iam your father';
        $body = $this->toJsonArray('{ "owner": "' . $owner .'", "url": "' . $url . '" }');

        $item = $this->decoder->decode($body);

        $this->assertEquals($url, $item->getUrl());
        $this->assertEquals($owner, $item->getOwner());
    }

    public function testDecodeShouldReturnNullItem() : void {
        $body = $this->toJsonArray('{ "message": "unknown body" }');

        $item = $this->decoder->decode($body);

        $this->assertEquals('', $item->getUrl());
        $this->assertEquals(CreateRequestDecoderImpl::DEFAULT_OWNER, $item->getOwner());
    }

    public function testDecodeShouldInputAndReturnItem() : void {
        $url = 'https://www.krybrig.org/jejak.html?token=AbCdEf';
        $owner = 'iam your father';
        $body = $this->toJsonArray('{ "owner": "' . '  ' . $owner . '     ' . '", "url": "' . '   ' . $url . '  ' . '" }');

        $item = $this->decoder->decode($body);

        $this->assertEquals($url, $item->getUrl());
        $this->assertEquals($owner, $item->getOwner());
    }

    private function toJsonArray(string $body) : array {
        return json_decode($body, true, 2);
    }
} 