<?

declare(strict_types=1);

interface RequestDecoder {
    public function decode(array $body) : Request;
}