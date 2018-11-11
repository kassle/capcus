<?

declare(strict_types=1);

class CreateRequest extends Request {
    private $owner;
    private $url;

    public function setOwner(string $owner) {
        $this->owner = $owner;
    }

    public function getOwner() : string {
        return $this->owner;
    }

    public function setUrl(string $url) {
        $this->url = $url;
    }

    public function getUrl() : string {
        return $this->url;
    }
}