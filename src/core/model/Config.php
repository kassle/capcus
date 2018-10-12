<?

declare(strict_types=1);

class Config {
    private $baseUrl;
    private $codeLength;

    public function setBaseUrl(string $baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    public function getBaseUrl() : string {
        return $this->baseUrl;
    }

    public function setCodeLength(int $codeLength) {
        $this->codeLength = $codeLength;
    }

    public function getCodeLength() : int {
        return $this->codeLength;
    }
}