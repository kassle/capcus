<?

declare(strict_types=1);

class CreateRequestDecoderImpl implements CreateRequestDecoder {
    private const FIELD_SOURCE_URL = "url";

    public function decode(string $body) : CreateRequest {
        $array = json_decode($body, true, 2);
        $request = new CreateRequest();

        if (isset($array[CreateRequestDecoderImpl::FIELD_SOURCE_URL])) {
            $request->setUrl($array[CreateRequestDecoderImpl::FIELD_SOURCE_URL]);
        }

        return $request;
    }
}