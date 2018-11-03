<?

declare(strict_types=1);

require '../autoload.php';
require '../App.php';

$config = new Config();
$config->setCodeLength(8);
$config->setBaseUrl('http://192.168.56.2/');

// $config->setDatabaseUrl('sqlite::memory:');
// $config->setDatabaseUser('');
// $config->setDatabasePassword('');

$config->setDatabaseUrl('mysql:host=localhost;dbname=capcus_db');
$config->setDatabaseUser('capcuser');
$config->setDatabasePassword('capcuser**');
$config->setMaxUrlLength(2000);
$config->setMaxAge(14);

(new App($config))->onRequest();