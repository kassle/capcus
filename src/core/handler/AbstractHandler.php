<?

declare(strict_types=1);

abstract class AbstractHandler implements Handler {
    private $type;
    private $decoder;

    public function __construct(string $type, RequestDecoder $decoder) {
        $this->type = $type;
        $this->decoder = $decoder;
    }

    public function getType() : string {
        return $this->type;
    }

    public function decode(array $body) : Request {
        return $this->decoder->decode($body);
    }

    public function validate(Request $request) : bool {
        return !empty($request->getType()) && !empty($request->getId());
    }
}