<?

declare(strict_types=1);

class CreateRequestDecoderImpl extends AbstractRequestDecoder {
    const DEFAULT_OWNER = "anonymous";
    private const FIELD_OWNER = "owner";
    private const FIELD_SOURCE_URL = "url";

    public function decode(array $body) : Request {
        error_log(json_encode($body));
        $request = parent::decode($body);

        if (isset($body[CreateRequestDecoderImpl::FIELD_SOURCE_URL])) {
            $request->setUrl(trim($body[CreateRequestDecoderImpl::FIELD_SOURCE_URL]));
        } else {
            $request->setUrl('');
        }

        if (isset($body[CreateRequestDecoderImpl::FIELD_OWNER])) {
            $request->setOwner(trim($body[CreateRequestDecoderImpl::FIELD_OWNER]));
        } else {
            $request->setOwner(CreateRequestDecoderImpl::DEFAULT_OWNER);
        }

        return $request;
    }

    public function create() : Request {
        return new CreateRequest();
    }
}