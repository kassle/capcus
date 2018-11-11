<?

function capcus_autoloader($class) {
    if (loadClass('core', $class)) {
    } else {
        error_log('Unable to load class: ' . $class);
    }
}

function loadClass($path, $class) : bool {
    try {
        $file = finder(__DIR__ . DIRECTORY_SEPARATOR . $path, DIRECTORY_SEPARATOR . $class . '.php');
        require $file;
        return true;
    } catch (Exception $ex) {
        return false;
    }
}

function finder(string $path, string $lookup) : string {
    $dirs = new RecursiveDirectoryIterator(
        $path,
        FilesystemIterator::SKIP_DOTS);
    
    foreach (new RecursiveIteratorIterator($dirs) as $file) {
        if (strpos($file, $lookup) !== false) {
            return $file;
        }
    }

    throw new Exception("Unknown file: " . $lookup , 1);
    
}

spl_autoload_register('capcus_autoloader');

require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'google-api' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';