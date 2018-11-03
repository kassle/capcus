<?

class RouterFactory {
    public static function create(Config $config) : Router {
        $router = new RouterImpl();
        $storage = new StorageImpl($config);

        $router->addHandler(
            new CreateHandler(
                new CreateRequestDecoderImpl(),
                $config,
                new CodeGeneratorImpl(),
                $storage));
        $router->addHandler(new GetHandler($config, new GetRequestDecoder(), $storage));

        return $router;
    }
}