<?

class RouterFactory {
    public static function create(Config $config) : Router {
        $router = new RouterImpl();

        $router->addHandler(
            new CreateHandler(
                new CreateRequestDecoderImpl(),
                $config,
                new CodeGeneratorImpl()));

        return $router;
    }
}