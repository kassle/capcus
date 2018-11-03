<?

declare(strict_types=1);

require '../autoload.php';
require '../App.php';
require '../Settings.php';

(new App($config))->onRequest();