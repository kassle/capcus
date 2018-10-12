<?

function capcus_autoloader($class) {
    include 'core/' . $class . '.php';
}

spl_autoload_register('capcus_autoloader');