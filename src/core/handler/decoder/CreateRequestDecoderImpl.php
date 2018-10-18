<?

declare(strict_types=1);

class CreateRequestDecoderImpl extends AbstractRequestDecoder {
    private const FIELD_SOURCE_URL = "url";

    public function decode(array $body) : Request {
        $request = parent::decode($body);

        if (isset($body[CreateRequestDecoderImpl::FIELD_SOURCE_URL])) {
            $request->setUrl($body[CreateRequestDecoderImpl::FIELD_SOURCE_URL]);
        } else {
            $request->setUrl('');
        }

        return $request;
    }

    public function create() : Request {
        return new CreateRequest();
    }
}