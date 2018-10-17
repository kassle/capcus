<?

declare(strict_types=1);

class Response {
    const CODE_OK = 200;
    const CODE_SERVICE_UNAVAILABLE = 503;

    private $statusCode = Response::CODE_OK;
    private $body;

    public function setStatusCode(int $statusCode) {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode() : int {
        return $this->statusCode;
    }

    public function setBody(JsonModel $body) {
        $this->body = $body;
    }

    public function getBody() : JsonModel {
        return $this->body;
    }
}