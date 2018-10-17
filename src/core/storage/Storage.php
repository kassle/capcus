<?

interface Storage {
    public function execSql(string $sql) : bool;
    public function insertItem(Item $item) : bool;
}