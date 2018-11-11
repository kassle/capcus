<?

declare(strict_types=1);

interface GoogleWrapper {
    public function getEmail(string $token) : ?string;
}