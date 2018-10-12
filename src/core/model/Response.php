<?

declare(strict_types=1);

class Response {
    private $statusCode = 200;
    private $body;

    public function setStatusCode(int $statusCode) {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode() : int {
        return $this->statusCode;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function getBody() {
        return $this->body;
    }
}