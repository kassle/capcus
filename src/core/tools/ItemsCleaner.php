<?

declare(strict_types=1);

class ItemsCleaner {
    private $storage;
    private $config;

    public function __construct(Storage $storage, Config $config) {
        $this->storage = $storage;
        $this->config = $config;
    }

    public function execute(DateTime $now) {
        // DELETE FROM items WHERE create_time <= '2018-11-01 10:47:00';
        $now->sub(new DateInterval('P' . $this->config->getMaxAge() . 'D'));
        $timestamp = $now->format('Y-m-d H:i:s');
        $sql = 'DELETE FROM items WHERE owner = \'' . CreateRequestDecoderImpl::DEFAULT_OWNER . '\' AND create_time < \'' . $timestamp . '\';';

        if (!$this->storage->execSql($sql)) {
            error_log('Clean-Up anonymous expired items failed: ' . $sql);
        }

        $sql = 'DELETE FROM items WHERE owner != \'' . CreateRequestDecoderImpl::DEFAULT_OWNER . '\' AND access_time < \'' . $timestamp . '\';';

        if (!$this->storage->execSql($sql)) {
            error_log('Clean-Up registered-user expired items failed: ' . $sql);
        }
    }
}