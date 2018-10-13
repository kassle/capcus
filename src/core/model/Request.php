<?

declare(strict_types=1);

class Request {
    private $type = '';
    private $id = '';

    public function setType(string $type) {
        $this->type = $type;
    }

    public function getType() : string {
        return $this->type;
    }

    public function setId(string $id) {
        $this->id = $id;
    }

    public function getId() : string {
        return $this->id;
    }
}