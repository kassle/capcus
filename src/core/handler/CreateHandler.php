<?

declare(strict_types=1);

class CreateHandler extends AbstractHandler {
    const TYPE = 'capcus.create';

    private const SUPPORTED_URL_REGEX = '/^(http|https):\\/\\//';

    private $config;
    private $decoder;
    private $codegen;
    private $storage;
    private $google;

    public function __construct(RequestDecoder $decoder, Config $config, CodeGenerator $codegen, Storage $storage, GoogleWrapper $google) {
        parent::__construct(CreateHandler::TYPE, $decoder);
        $this->config = $config;
        $this->codegen = $codegen;
        $this->storage = $storage;
        $this->google = $google;
    }

    public function validate(Request $request) : bool {
        return parent::validate($request)
            && !empty($request->getUrl())
            && (strlen($request->getUrl()) <= $this->config->getMaxUrlLength())
            && preg_match(CreateHandler::SUPPORTED_URL_REGEX, $request->getUrl())
            && filter_var($request->getUrl(), FILTER_VALIDATE_URL)
            && !empty(trim($request->getOwner()));
    }

    public function execute(Request $request) : Response {
        $item = new Item();
        $item->setSourceUrl($request->getUrl());

        if (CreateRequestDecoderImpl::DEFAULT_OWNER === $request->getOwner()) {
            $item->setOwner($request->getOwner());
        } else {
            $email = $this->google->getEmail($request->getOwner());

            if (!is_null($email)) {
                $item->setOwner($email);
            } else {
                return StatusResponseGenerator::create(Response::CODE_FORBIDDEN);
            }
        }

        $previous = $this->storage->getItemByUrl($item->getOwner(), $item->getSourceUrl());
        if (is_null($previous)) {
            return $this->createNewEntry($item);
        } else {
            $previous->setExpireTime($this->getExpireTime($previous));

            $response = new Response();
            $response->setStatusCode(Response::CODE_OK);
            $response->setBody($previous);
            return $response;
        }
    }

    private function createNewEntry(Item $item) : Response {
        $response = new Response();
        $retry = 3;

        while ($retry > 0) {
            $item->setCode($this->codegen->generate($this->config->getCodeLength()));

            try {
                if ($this->storage->insertItem($item)) {
                    $item->setTargetUrl($this->config->getBaseUrl() . $item->getCode());
                    $item->setExpireTime($this->getExpireTime($item));
                    $response->setStatusCode(Response::CODE_OK);
                    $response->setBody($item);
                    break;
                } else {
                    $response->setStatusCode(Response::CODE_SERVICE_UNAVAILABLE);
                    break;
                }
            } catch (StorageException $ex) {
                if ($ex->getCode() === StorageException::ERR_DUPLICATE_KEY) {
                    $retry--;
                } else {
                    $response->setStatusCode(Response::CODE_SERVICE_UNAVAILABLE);
                    break;
                }
            }
        }

        return $response;
    }

    private function getExpireTime(Item $item) : string {
        $time;

        if (CreateRequestDecoderImpl::DEFAULT_OWNER === $item->getOwner()) {
            $time = DateTime::createFromFormat(Item::TIMESTAMP_FORMAT, $item->getCreateTime());
        } else {
            $time = DateTime::createFromFormat(Item::TIMESTAMP_FORMAT, $item->getAccessTime());
        }

        $time->add(new DateInterval('P' . $this->config->getMaxAge() . 'D'));
        return $time->format(Item::TIMESTAMP_FORMAT);
    }
}