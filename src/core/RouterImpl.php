<?

declare(strict_types=1);

class RouterImpl implements Router {
    const FIELD_TYPE = 'type';
    private $handlers = array();

    public function addHandler(Handler $handler) {
        $this->$handlers[$handler->getType()] = $handler;
    }

    public function onRequest(array $body) : Response {
        if (isset($body[RouterImpl::FIELD_TYPE])) {
            return $this->processRequest($body);
        } else {
            return StatusResponseGenerator::create(400);
        }
    }

    private function processRequest(array $body) : Response {
        $handler = $this->$handlers[$body[RouterImpl::FIELD_TYPE]];

        if (!is_null($handler)) {
            try {
                return $this->delegateRequestToHandler($handler, $body);
            } catch (Exception $ex) {
                error_log($ex->getTraceAsString());
                return StatusResponseGenerator::create(500);
            }
        } else {
            return StatusResponseGenerator::create(404);
        }
    }

    private function delegateRequestToHandler(Handler $handler, array $body) : Response {
        $request = $handler->decode($body);
        if ($handler->validate($request)) {
            return $handler->execute($request);
        } else {
            return $this->createResponse(400);
        }
    }
}