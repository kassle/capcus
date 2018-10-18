<?

declare(strict_types=1);

class GetRequestDecoder extends AbstractRequestDecoder {
    private const FIELD_CODE = "code";

    public function decode(array $body) : Request {
        $request = parent::decode($body);

        if (isset($body[GetRequestDecoder::FIELD_CODE])) {
            $request->setCode($body[GetRequestDecoder::FIELD_CODE]);
        } else {
            $request->setCode('');
        }

        return $request;
    }

    public function create() : Request {
        return new GetRequest();
    }
}