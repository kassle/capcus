<?

declare(strict_types=1);

class CreateRequest extends Request {
    private $url;

    public function setUrl(string $url) {
        $this->url = $url;
    }

    public function getUrl() : string {
        return $this->url;
    }
}