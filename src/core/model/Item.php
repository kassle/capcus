<?

declare(strict_types=1);

class Item {
    private $sourceUrl = '';
    private $targetUrl = '';
    private $owner = '';
    private $code = '';
    private $createTime;

    public function __construct() {
        $timestamp = new DateTime();
        $this->createTime = $timestamp->format(DateTime::ATOM);
    }

    public function setSourceUrl(string $sourceUrl) {
        $this->sourceUrl = $sourceUrl;
    }

    public function getSourceUrl() : string {
        return $this->sourceUrl;
    }

    public function setTargetUrl(string $targetUrl) {
        return $this->targetUrl = $targetUrl;
    }

    public function getTargetUrl() : string {
        return $this->targetUrl;
    }

    public function setOwner(string $owner) {
        $this->owner = $owner;
    }

    public function getOwner() : string {
        return $owner;
    }

    public function setCode(string $code) {
        $this->code = $code;
    }

    public function getCode() : string {
        return $this->code;
    }

    public function setCreateTime(string $timestamp) {
        $this->createTime = $timestamp;
    }

    public function getCreateTime() : string {
        return $this->createTime;
    }
}
