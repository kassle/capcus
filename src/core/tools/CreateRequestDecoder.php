<?

declare(strict_types=1);

interface CreateRequestDecoder {
    public function decode(string $body) : CreateRequest;
}