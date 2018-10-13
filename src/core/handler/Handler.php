<?

interface Handler {
    public function getType() : string;
    public function decode(array $body) : Request;
    public function validate(Request $request) : bool;
    public function execute(Request $request) : Response;
}