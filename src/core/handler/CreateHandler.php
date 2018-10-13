<?

declare(strict_types=1);

class CreateHandler extends AbstractHandler {
    private $config;
    private $decoder;
    private $codegen;

    public function __construct(RequestDecoder $decoder, Config $config, CodeGenerator $codegen) {
        parent::__construct('capcus.create', $decoder);
        $this->config = $config;
        $this->codegen = $codegen;
    }

    public function execute(Request $request) : Response {
        $item = new Item();
        $item->setSourceUrl($request->getUrl());
        $item->setCode($this->codegen->generate($this->config->getCodeLength()));
        $item->setTargetUrl($this->config->getBaseUrl() . $item->getCode());

        $response = new Response();
        $response->setBody($item);

        return $response;
    }
}