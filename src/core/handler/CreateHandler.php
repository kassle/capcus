<?

declare(strict_types=1);

class CreateHandler extends AbstractHandler {
    private $config;
    private $decoder;
    private $codegen;
    private $storage;

    public function __construct(RequestDecoder $decoder, Config $config, CodeGenerator $codegen, Storage $storage) {
        parent::__construct('capcus.create', $decoder);
        $this->config = $config;
        $this->codegen = $codegen;
        $this->storage = $storage;
    }

    public function execute(Request $request) : Response {
        $item = new Item();
        $item->setSourceUrl($request->getUrl());
        $item->setOwner('anonymous');

        $response = new Response();
        $retry = 3;

        while ($retry > 0) {
            $item->setCode($this->codegen->generate($this->config->getCodeLength()));

            try {
                if ($this->storage->insertItem($item)) {
                    $item->setTargetUrl($this->config->getBaseUrl() . $item->getCode());
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
}