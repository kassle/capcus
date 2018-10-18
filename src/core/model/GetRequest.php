<?

declare(strict_types=1);

class GetRequest extends Request {
    private $code;

    public function setCode(string $code) {
        $this->code = $code;
    }

    public function getCode() : string {
        return $this->code;
    }
}