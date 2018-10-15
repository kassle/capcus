<?

declare(strict_types=1);

require '../autoload.php';
require '../App.php';

$config = new Config();
        $config->setCodeLength(8);
        $config->setBaseUrl('http://192.168.56.2/');

(new App($config))->onRequest();