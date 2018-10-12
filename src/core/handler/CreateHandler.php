<?

declare(strict_types=1);

class CreateHandler {
    private $config;
    private $codegen;

    public function __construct(Config $config, CodeGenerator $codegen) {
        $this->config = $config;
        $this->codegen = $codegen;
    }

    public function execute(CreateRequest $request) : Response {
        $item = new Item();
        $item->setSourceUrl($request->getUrl());
        $item->setCode($this->codegen->generate($this->config->getCodeLength()));
        $item->setTargetUrl($this->config->getBaseUrl() . $item->getCode());

        $response = new Response();
        $response->setBody($item);

        return $response;
    }
}