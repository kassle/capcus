<?

declare(strict_types=1);

class Item implements JsonModel {
    const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';

    private $sourceUrl = '';
    private $targetUrl = '';
    private $owner = '';
    private $code = '';
    private $createTime;
    private $accessTime;
    private $accessCount;
    private $expireTime;

    public function __construct() {
        $timestamp = new DateTime();
        $this->createTime = $timestamp->format(Item::TIMESTAMP_FORMAT);
        $this->accessTime = $this->createTime;
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
        return $this->owner;
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

    public function setAccessTime(string $timestamp) {
        $this->accessTime = $timestamp;
    }

    public function getAccessTime() : string {
        return $this->accessTime;
    }

    public function setAccessCount(int $accessCount) {
        $this->accessCount = $accessCount;
    }

    public function getAccessCount() : int {
        return $this->accessCount;
    }

    public function setExpireTime(string $expireTime) {
        $this->expireTime = $expireTime;
    }

    public function getExpireTime() : string {
        return $this->expireTime;
    }

    public function getJson() : String {
        return json_encode([
            'source_url' => $this->sourceUrl,
            'target_url' => $this->targetUrl,
            'create_time' => $this->createTime,
            'expire_time' => $this->expireTime
        ]);
    }
}
