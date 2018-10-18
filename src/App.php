<?

declare(strict_types=1);

class App {
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    private $config;

    public function __construct(Config $config) {
        $this->config = $config;
    }

    public function onRequest() {
        try {
            $body = $this->prepareBody();
            $response = $this->delegateToRouter($body);
            $this->postResponse($response);
        } catch (Exception $ex) {
            $this->postResponse(StatusResponseGenerator::create($ex->getCode()));
        }
    }

    private function prepareBody() : array {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        if ($method === App::METHOD_POST) {
            return json_decode(file_get_contents('php://input'), true, 2);
        } else if ($method === App::METHOD_GET) {
            if (sizeof($_GET) === 0) {
                throw new Exception("Redirect", 302);
            } else {
                return $_GET;
            }
        } else {
            throw new Exception("Bad Request", 400);
        }
    }

    private function delegateToRouter(array $body) : Response {
        $router = $this->prepareRouter();

        try {
            $response = $router->onRequest($body);
        } catch (Exception $ex) {
            error_log($ex->getTraceAsString());
            $response = StatusResponseGenerator::create(500);
        }

        return $response;
    }

    private function prepareRouter() {
        return RouterFactory::create($this->config);
    }

    private function postResponse(Response $response) {
        $statusCode = $response->getStatusCode();

        if ($statusCode === 200) {
            http_response_code($statusCode);
            header('Content-Type: application/json');
            echo $response->getBody()->getJson();
        } else if ($statusCode === 302) {
            header('Location: ' . $this->config->getBaseUrl() . 'index.html', true, $statusCode);
        } else {
            http_response_code($statusCode);
        }

        die();
    }
}
