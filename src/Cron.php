<?

declare(strict_types=1);

require 'autoload.php';
require 'Settings.php';

$cleaner = new ItemsCleaner(new StorageImpl($config), $config);
$cleaner->execute(new DateTime());