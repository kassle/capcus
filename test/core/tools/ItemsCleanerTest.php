<?

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ItemsCleanerTest extends TestCase {
    private $storage;
    private $config;
    private $cleaner;

    public function setup() {
        $this->storage = $this->createMock(Storage::class);
        $this->config = new Config();
        $this->config->setMaxAge(4);
        $this->cleaner = new ItemsCleaner($this->storage, $this->config);
    }

    public function testExecuteShouldExecuteSqlQueryToRemoveExpiredItems() {
        $timestamp = '2018-10-30 13:28:45';
        $now = new DateTime($timestamp);
        $expire = (new DateTime($timestamp))->sub(new DateInterval('P' . $this->config->getMaxAge() . 'D'));
        $expectAnonymous = 'DELETE FROM items WHERE owner = \'anonymous\' AND create_time < \'' . $expire->format('Y-m-d H:i:s') . '\';';
        $expectRegistered = 'DELETE FROM items WHERE owner != \'anonymous\' AND access_time < \'' . $expire->format('Y-m-d H:i:s') . '\';';

        $this->storage->expects($this->at(0))
            ->method('execSql')
            ->with($expectAnonymous)
            ->willReturn(true);

        $this->storage->expects($this->at(1))
            ->method('execSql')
            ->with($expectRegistered)
            ->willReturn(true);
        
        $this->cleaner->execute($now);
    }
}