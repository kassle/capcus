<?

declare(strict_types=1);

class CreateRequest {
    private $url = '';

    public function setUrl(string $url) {
        $this->url = $url;
    }

    public function getUrl() : string {
        return $this->url;
    }
}