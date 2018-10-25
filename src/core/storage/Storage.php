<?

interface Storage {
    public function execSql(string $sql) : bool;
    public function insertItem(Item $item) : bool;
    public function getItem(string $code) : ?Item;
    public function getItemByUrl(string $url) : ?Item;
}