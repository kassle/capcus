<?

declare(strict_types=1);

interface Router {
    public function addHandler(Handler $handler);
    public function onRequest(array $body) : Response;
}