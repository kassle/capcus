<?

declare(strict_types=1);

abstract class AbstractRequestDecoder implements RequestDecoder {
    private const FIELD_TYPE = "type";
    private const FIELD_ID = "id";

    public function decode(array $body) : Request {
        $request = $this->create();

        if (isset($body[AbstractRequestDecoder::FIELD_TYPE])) {
            $request->setType($body[AbstractRequestDecoder::FIELD_TYPE]);
        }

        if (isset($body[AbstractRequestDecoder::FIELD_ID])) {
            $request->setId($body[AbstractRequestDecoder::FIELD_ID]);
        }

        return $request;
    }

    public abstract function create() : Request;
}