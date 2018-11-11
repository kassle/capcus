<?

declare(strict_types=1);

class StorageImpl implements Storage {

    private $config;
    private $db;

    public function __construct(Config $config) {
        $this->config = $config;
        try {
            $this->db = new PDO($config->getDatabaseUrl(), $config->getDatabaseUser(), $config->getDatabasePassword());
            $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $ex) {
            error_log("code: " . $ex->getCode() . '; message: ' . $ex->getMessage());
        }
    }

    public function execSql(string $sql) : bool {
        try {
            $this->db->exec($sql);
            return true;
        } catch (Exception $ex) {
            error_log("code: " . $ex->getCode() . '; message: ' . $ex->getMessage());
            return false;
        }
    }

    public function insertItem(Item $item) : bool {
        $query = 'INSERT INTO items (code, owner, create_time, access_time, access_count, source) VALUES ( ?, ?, ?, ?, ?, ?);';

        try {
            $this->db->prepare($query)->execute([
                $item->getCode(),
                $item->getOwner(),
                $item->getCreateTime(),
                $item->getAccessTime(),
                0,
                base64_encode($item->getSourceUrl())
            ]);
            return true;
        } catch (PDOException $ex) {
            if (strcmp("23000", $ex->getCode()) === 0) {
                throw new StorageException(StorageException::ERR_DUPLICATE_KEY, $ex);
            } else {
                error_log("code: " . $ex->getCode() . '; message: ' . $ex->getMessage());
                return false;
            }
        }
    }

    public function getItem(string $code) : ?Item {
        $this->updateItemAccess($code);

        $query = "SELECT code, owner, create_time, access_time, access_count, source FROM items WHERE code = ? LIMIT 1";
        $statement = $this->db->prepare($query);
        if ($statement->execute([ $code ])) {
            $data = $statement->fetch(PDO::FETCH_ASSOC);
            
            return $this->convertToItem($data);
        } else {
            return NULL;
        }
    }

    private function updateItemAccess(string $code) {
        $query = "UPDATE items SET access_time = ?, access_count = access_count + 1 WHERE code = ?";
        $statement = $this->db->prepare($query);
        $statement->execute([(new DateTime())->format(Item::TIMESTAMP_FORMAT), $code]);
    }

    public function getItemByUrl(string $owner, string $url) : ?Item {
        $query = "SELECT code, owner, create_time, access_time, access_count, source FROM items WHERE owner = ? AND source = ? LIMIT 1";
        $statement = $this->db->prepare($query);
        if ($statement->execute([ $owner, base64_encode($url) ])) {
            $data = $statement->fetch(PDO::FETCH_ASSOC);
            
            return $this->convertToItem($data);
        } else {
            return NULL;
        }
    }

    private function convertToItem($data) {
        if ($data) {
            $item = new Item();
            $item->setCode($data['code']);
            $item->setOwner($data['owner']);
            $item->setCreateTime($data['create_time']);
            $item->setAccessTime($data['access_time']);
            $item->setAccessCount((int) $data['access_count']);
            $item->setSourceUrl(base64_decode($data['source']));
            $item->setTargetUrl($this->config->getBaseUrl() . $item->getCode());
            
            return $item;
        } else {
            return NULL;
        }
    }
}