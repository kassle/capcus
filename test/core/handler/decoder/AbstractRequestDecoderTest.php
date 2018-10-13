<?

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class AbstractRequestDecoderTest extends TestCase {
    private $decoder;

    public function setup() {
        $this->decoder = new AbstractRequestDecoderImpl();
    }

    public function testDecodeShouldFillTypeAndId() {
        $type = "dummy.type";
        $id = "dummy.id";

        $body = $this->toJsonArray('{ "type": "' . $type . '", "id": "' . $id . '" }');

        $request = $this->decoder->decode($body);

        $this->assertEquals($type, $request->getType());
        $this->assertEquals($id, $request->getId());
    }

    public function testDecodeShouldLeaveTypeEmpty() {
        $id = "dummy.id";

        $body = $this->toJsonArray('{ "id": "' . $id . '" }');

        $request = $this->decoder->decode($body);

        $this->assertTrue(empty($request->getType()));
        $this->assertEquals($id, $request->getId());
    }

    public function testDecodeShouldLeaveIdEmpty() {
        $type = "dummy.type";

        $body = $this->toJsonArray('{ "type": "' . $type . '" }');

        $request = $this->decoder->decode($body);

        $this->assertEquals($type, $request->getType());
        $this->assertTrue(empty($request->getId()));
    }

    public function testDecodeShouldLeaveTypeAndIdEmpty() {
        $request = $this->decoder->decode(array());

        $this->assertTrue(empty($request->getType()));
        $this->assertTrue(empty($request->getId()));
    }

    private function toJsonArray(string $body) : array {
        return json_decode($body, true, 2);
    }
}

class AbstractRequestDecoderImpl extends AbstractRequestDecoder {
    public function create() : Request {
        return new Request();
    }
}