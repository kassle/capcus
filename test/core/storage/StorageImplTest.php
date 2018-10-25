<?

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class StorageImplTest extends TestCase {
    private $config;
    private $storage;

    public function setup() {
        $this->config = new Config();
        $this->config->setBaseUrl('https://capc.us/');
        $this->config->setDatabaseUrl("sqlite::memory:");
        $this->config->setDatabaseUser('');
        $this->config->setDatabasePassword('');

        $this->storage = new StorageImpl($this->config);
        $this->storage->execSql("CREATE TABLE items (code VARCHAR[128] PRIMARY KEY, owner VARCHAR[256], createTime VARCHAR[25], source VARCHAR[2048]);");
    }

    public function testInsertItemShouldTrue() {
        $item = $this->createItem('00000000');
        $this->assertTrue($this->storage->insertItem($item));
    }

    /**
     * @expectedException StorageException
     * @expectedExceptionCode StorageException::ERR_DUPLICATE_KEY
     */
    public function testDuplicateInsertItemShouldThrowException() {
        $item = $this->createItem('00000001');
        $this->assertTrue($this->storage->insertItem($item));
        $this->storage->insertItem($item);
    }

    public function testGetItemShouldReturnItem() {
        $item = $this->createItem('00000002');
        $this->assertTrue($this->storage->insertItem($item));

        $result = $this->storage->getItem($item->getCode());
        $this->assertEquals($item->getCode(), $result->getCode());
        $this->assertEquals($item->getOwner(), $result->getOwner());
        $this->assertEquals($item->getCreateTime(), $result->getCreateTime());
        $this->assertEquals($item->getSourceUrl(), $result->getSourceUrl());
        $this->assertEquals($this->config->getBaseUrl() . $item->getCode(), $result->getTargetUrl());
    }

    public function testGetItemShouldReturnNullWhenNotFound() {
        $code = '00000003';
        $result = $this->storage->getItem($code);
        $this->assertNull($result);
    }

    public function testGetItemByUrlShouldReturnItem() {
        $item = $this->createItem('00000004', 'https://previous.long.url/summon/again.html');
        $this->assertTrue($this->storage->insertItem($item));

        $result = $this->storage->getItemByUrl($item->getSourceUrl());
        $this->assertEquals($item->getCode(), $result->getCode());
        $this->assertEquals($item->getOwner(), $result->getOwner());
        $this->assertEquals($item->getCreateTime(), $result->getCreateTime());
        $this->assertEquals($item->getSourceUrl(), $result->getSourceUrl());
        $this->assertEquals($this->config->getBaseUrl() . $item->getCode(), $result->getTargetUrl());
    }

    public function testGetItemByUrlShouldReturnNullWhenNotFound() {
        $url = 'http://unknown.found.you/not/exist.html';
        $result = $this->storage->getItem($url);
        $this->assertNull($result);
    }

    private function createItem(
        string $code,
        string $url = 'https://www.long.url/very/far/far/away/index.html') {

        $item = new Item();
        $item->setCode($code);
        $item->setOwner('anonymous');
        $item->setSourceUrl($url);

        return $item;
    }
}