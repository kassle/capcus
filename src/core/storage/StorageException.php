<?

declare(strict_types=1);

class StorageException extends Exception {
    const ERR_DUPLICATE_KEY = 101;

    public function __construct(int $code, Exception $ex) {
        parent::__construct($ex->getMessage(), $code, $ex);
    }
}