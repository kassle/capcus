<?

function capcus_autoloader($class) {
    try {
        $file = finder(__DIR__ . DIRECTORY_SEPARATOR . 'core', DIRECTORY_SEPARATOR . $class . '.php');
        require $file;
    } catch (Exception $ex) {
        error_log('Unable to load class: ' . $class);
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