<?

declare(strict_types=1);

class StorageImpl implements Storage {

    private $config;
    private $db;

    public function __construct(Config $config) {
        $this->config = $config;
        try {
            $this->db = new PDO($config->getDatabaseUrl());
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
        $query = 'insert into items ("code", "owner", "createTime", "source") values ( ?, ?, ?, ?);';

        try {
            $this->db->prepare($query)->execute([
                $item->getCode(),
                $item->getOwner(),
                $item->getCreateTime(),
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
        $query = "SELECT code, owner, createTime, source FROM items WHERE code = ? LIMIT 1";
        $statement = $this->db->prepare($query);
        if ($statement->execute([ $code ])) {
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
            $item->setCreateTime($data['createTime']);
            $item->setSourceUrl(base64_decode($data['source']));
            $item->setTargetUrl($this->config->getBaseUrl() . $item->getCode());
            
            return $item;
        } else {
            return NULL;
        }
    }
}