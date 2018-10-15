<?

declare(strict_types=1);

class StatusResponseGenerator {
    public static function create(int $statusCode) : Response {
        $response = new Response();
        $response->setStatusCode($statusCode);
        return $response;
    }
}