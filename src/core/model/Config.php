<?

declare(strict_types=1);

class Config {
    private $baseUrl;
    private $codeLength;
    private $databaseURl;
    private $databaseUser;
    private $databasePassword;
    private $maxUrlLength;

    public function setBaseUrl(string $baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    public function getBaseUrl() : string {
        return $this->baseUrl;
    }

    public function setCodeLength(int $codeLength) {
        $this->codeLength = $codeLength;
    }

    public function getCodeLength() : int {
        return $this->codeLength;
    }

    public function setDatabaseUrl(string $databaseUrl) {
        $this->databaseUrl = $databaseUrl;
    }

    public function getDatabaseUrl() : string {
        return $this->databaseUrl;
    }

    public function setDatabaseUser(string $databaseUser) {
        $this->databaseUser = $databaseUser;
    }

    public function getDatabaseUser() : string {
        return $this->databaseUser;
    }

    public function setDatabasePassword(string $databasePassword) {
        $this->databasePassword = $databasePassword;
    }

    public function getDatabasePassword() : string {
        return $this->databasePassword;
    }

    public function setMaxUrlLength(int $maxUrlLength) {
        $this->maxUrlLength = $maxUrlLength;
    }

    public function getMaxUrlLength() : int {
        return $this->maxUrlLength;
    }
}