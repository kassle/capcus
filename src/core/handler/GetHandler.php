<?

declare(strict_types=1);

class GetHandler extends AbstractHandler {
    const TYPE = 'capcus.get';

    private $config;
    private $storage;

    public function __construct(Config $config, GetRequestDecoder $decoder, Storage $storage) {
        parent::__construct(GetHandler::TYPE, $decoder);

        $this->config = $config;
        $this->storage = $storage;
    }

    public function validate(Request $request) : bool {
        return parent::validate($request)
            && !empty($request->getCode())
            && strlen($request->getCode()) === $this->config->getCodeLength()
            && ctype_alnum($request->getCode());
    }

    public function execute(Request $request) : Response {
        $response = new Response();

        $item = $this->storage->getItem($request->getCode());
        if (!is_null($item)) {
            $createTime = DateTime::createFromFormat(Item::TIMESTAMP_FORMAT, $item->getCreateTime());
            $interval = (int) ($createTime->diff(new DateTime()))->format('%a');

            if ($this->config->getMaxAge() >= $interval) {
                $response->setStatusCode(Response::CODE_REDIRECT);
                $response->setBody($item);
            } else {
                $response->setStatusCode(Response::CODE_GONE);
            }
        } else {
            $response->setStatusCode(Response::CODE_NOT_FOUND);
        }
        
        return $response;
    }
}