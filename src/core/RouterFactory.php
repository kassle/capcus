<?

class RouterFactory {
    public static function create(Config $config) : Router {
        $router = new RouterImpl();
        $storage = new StorageImpl($config);
        $google = new GoogleWrapperImpl(new Google_Client(['client_id' => $config->getGoogleClientId()]));

        $router->addHandler(
            new CreateHandler(
                new CreateRequestDecoderImpl(),
                $config,
                new CodeGeneratorImpl(),
                $storage,
                $google));
        $router->addHandler(new GetHandler($config, new GetRequestDecoder(), $storage));

        return $router;
    }
}